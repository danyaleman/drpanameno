<?php

namespace App\Http\Controllers;

use App\Patient;
use App\VaccineCatalog;
use App\VaccineRecord;
use App\VaccineSchedule;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel.auth');
    }

    /* =========================================================
     |  HELPERS PRIVADOS
     ========================================================= */

    /** Obtiene user + role del usuario autenticado. */
    private function authData(): array
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug ?? 'admin';
        return compact('user', 'role');
    }

    /* =========================================================
     |  CATÁLOGO DE VACUNAS
     ========================================================= */

    public function catalogIndex()
    {
        ['user' => $user, 'role' => $role] = $this->authData();

        $vaccines = VaccineCatalog::withCount('records')->orderBy('name')->get();

        return view('vaccines.catalog.index', compact('user', 'role', 'vaccines'));
    }

    public function catalogCreate()
    {
        ['user' => $user, 'role' => $role] = $this->authData();

        return view('vaccines.catalog.create', compact('user', 'role'));
    }

    public function catalogStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'code'        => 'nullable|string|max:30|unique:vaccine_catalogs,code',
            'total_doses' => 'required|integer|min:1',
        ]);

        $vaccine = VaccineCatalog::create([
            'name'         => $request->name,
            'code'         => $request->code,
            'description'  => $request->description,
            'manufacturer' => $request->manufacturer,
            'total_doses'  => $request->total_doses,
            'is_active'    => true,
        ]);

        // Guardar esquema de dosis si viene
        if ($request->has('doses') && is_array($request->doses)) {
            foreach ($request->doses as $dose) {
                if (!empty($dose['label'])) {
                    VaccineSchedule::create([
                        'vaccine_catalog_id'  => $vaccine->id,
                        'dose_number'         => $dose['dose_number'],
                        'dose_label'          => $dose['label'],
                        'interval_days'       => $dose['interval_days'] ?? null,
                        'recommended_age_min' => $dose['age_min'] ?? null,
                        'recommended_age_max' => $dose['age_max'] ?? null,
                        'notes'               => $dose['notes'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('vaccines.catalog.index')
            ->with('success', "Vacuna «{$vaccine->name}» creada correctamente.");
    }

    public function catalogEdit($id)
    {
        ['user' => $user, 'role' => $role] = $this->authData();

        $vaccine = VaccineCatalog::with('schedules')->findOrFail($id);

        return view('vaccines.catalog.edit', compact('user', 'role', 'vaccine'));
    }

    public function catalogUpdate(Request $request, $id)
    {
        $vaccine = VaccineCatalog::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:150',
            'code'        => 'nullable|string|max:30|unique:vaccine_catalogs,code,' . $id,
            'total_doses' => 'required|integer|min:1',
        ]);

        $vaccine->update([
            'name'         => $request->name,
            'code'         => $request->code,
            'description'  => $request->description,
            'manufacturer' => $request->manufacturer,
            'total_doses'  => $request->total_doses,
        ]);

        // Reemplazar esquema de dosis
        if ($request->has('doses') && is_array($request->doses)) {
            $vaccine->schedules()->delete();
            foreach ($request->doses as $dose) {
                if (!empty($dose['label'])) {
                    VaccineSchedule::create([
                        'vaccine_catalog_id'  => $vaccine->id,
                        'dose_number'         => $dose['dose_number'],
                        'dose_label'          => $dose['label'],
                        'interval_days'       => $dose['interval_days'] ?? null,
                        'recommended_age_min' => $dose['age_min'] ?? null,
                        'recommended_age_max' => $dose['age_max'] ?? null,
                        'notes'               => $dose['notes'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('vaccines.catalog.index')
            ->with('success', "Vacuna «{$vaccine->name}» actualizada correctamente.");
    }

    public function catalogToggle($id)
    {
        $vaccine = VaccineCatalog::findOrFail($id);
        $vaccine->update(['is_active' => !$vaccine->is_active]);

        $estado = $vaccine->is_active ? 'activada' : 'desactivada';
        return redirect()->route('vaccines.catalog.index')
            ->with('success', "Vacuna «{$vaccine->name}» {$estado}.");
    }

    /* =========================================================
     |  REGISTROS DE VACUNACIÓN
     ========================================================= */

    public function recordsIndex(Request $request)
    {
        ['user' => $user, 'role' => $role] = $this->authData();

        $query = VaccineRecord::with(['patient', 'vaccine']);

        // Filtros
        if ($request->filled('patient_search')) {
            $search = $request->patient_search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('dui',        'like', "%{$search}%");
            });
        }
        if ($request->filled('vaccine_id')) {
            $query->where('vaccine_catalog_id', $request->vaccine_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        $records  = $query->orderBy('scheduled_date', 'asc')->paginate(20)->withQueryString();
        $vaccines = VaccineCatalog::active()->orderBy('name')->get();

        // Stats
        $stats = [
            'total'    => VaccineRecord::count(),
            'applied'  => VaccineRecord::where('status', 'applied')->count(),
            'pending'  => VaccineRecord::where('status', 'pending')->count(),
            'today'    => VaccineRecord::where('status', 'pending')
                            ->whereDate('scheduled_date', Carbon::today())->count(),
            'upcoming' => VaccineRecord::where('status', 'pending')
                            ->whereBetween('scheduled_date', [
                                Carbon::today()->toDateString(),
                                Carbon::today()->addDays(7)->toDateString(),
                            ])->count(),
        ];

        return view('vaccines.records.index', compact('user', 'role', 'records', 'vaccines', 'stats'));
    }

    public function recordsCreate(Request $request)
    {
        ['user' => $user, 'role' => $role] = $this->authData();

        $patients = Patient::where('is_deleted', 0)->orderBy('first_name')->get();
        $vaccines = VaccineCatalog::active()->orderBy('name')->get();

        // Precargar paciente si viene por query string
        $selectedPatient = null;
        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        return view('vaccines.records.create', compact('user', 'role', 'patients', 'vaccines', 'selectedPatient'));
    }

    public function recordsStore(Request $request)
    {
        $request->validate([
            'patient_id'        => 'required|integer',
            'vaccine_catalog_id' => 'required|integer',
            'dose_label'        => 'required|string|max:100',
            'applied_date'      => 'required|date',
        ]);

        VaccineRecord::create([
            'patient_id'        => $request->patient_id,
            'vaccine_catalog_id' => $request->vaccine_catalog_id,
            'dose_number'       => $request->dose_number ?? 1,
            'dose_label'        => $request->dose_label,
            'status'            => 'applied',
            'applied_date'      => $request->applied_date,
            'scheduled_date'    => $request->applied_date,
            'lot_number'        => $request->lot_number,
            'applied_by'        => $request->applied_by,
            'notes'             => $request->notes,
        ]);

        return redirect()->route('vaccines.records.index')
            ->with('success', 'Vacuna registrada correctamente.');
    }

    public function recordsApply(Request $request, $id)
    {
        $request->validate([
            'applied_date' => 'required|date',
        ]);

        $record = VaccineRecord::findOrFail($id);
        $record->update([
            'status'       => 'applied',
            'applied_date' => $request->applied_date,
            'lot_number'   => $request->lot_number,
            'applied_by'   => $request->applied_by,
            'notes'        => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Dosis marcada como aplicada.');
    }

    public function recordsCancel($id)
    {
        $record = VaccineRecord::findOrFail($id);
        $record->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Registro cancelado.');
    }

    public function patientHistory($patientId)
    {
        ['user' => $user, 'role' => $role] = $this->authData();

        $patient = Patient::findOrFail($patientId);

        $records = VaccineRecord::with('vaccine')
            ->where('patient_id', $patientId)
            ->orderBy('vaccine_catalog_id')
            ->orderBy('dose_number')
            ->get()
            ->groupBy('vaccine_catalog_id');

        return view('vaccines.records.patient-history', compact('user', 'role', 'patient', 'records'));
    }

    /** AJAX: devuelve el esquema de dosis de una vacuna */
    public function getSchedule($vaccineId)
    {
        $vaccine   = VaccineCatalog::with('schedules')->findOrFail($vaccineId);
        $schedules = $vaccine->schedules;

        return response()->json([
            'vaccine'   => $vaccine,
            'schedules' => $schedules,
        ]);
    }
}
