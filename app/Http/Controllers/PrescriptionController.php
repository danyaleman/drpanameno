<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Invoice;
use App\Prescription;
use App\Patient;
use Illuminate\Http\Request;
use App\Medicine;
use App\TestReport;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Support\Facades\Config;
use App\User;
use App\Signos;
use App\Archivo;
use Illuminate\Support\Facades\Storage;
use App\Vacuna;

class PrescriptionController extends Controller
{
    protected $prescription, $medicine, $test_report, $TestReport;
    public $limit;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('sentinel.auth');
        $this->prescription = new Prescription();
        $this->middleware(function ($request, $next) {
            if (session()->has('page_limit')) {
                $this->limit = session()->get('page_limit');
            }
            else {
                $this->limit = Config::get('app.page_limit');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.list')) {
            $role = $user->roles[0]->slug;

            $query = Prescription::with('patient', 'doctor', 'appointment', 'appointment.timeSlot')->where('is_deleted', 0);

            if ($role == 'patient') {
                // Si es paciente solo ve lo suyo, asumimos se vincula por patient_id o sigue en -1 hasta que se configure bien.
                $query->where('patient_id', $user->id);
            }

            // Aplicar Filtros
            if ($request->has('patient_name') && $request->patient_name != '') {
                $query->whereHas('patient', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->patient_name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->patient_name . '%');
                });
            }

            if ($request->has('prescription_date') && $request->prescription_date != '') {
                $query->whereDate('created_at', $request->prescription_date);
            }

            $prescriptions = $query->orderBy('id', 'desc')->paginate($this->limit);
            $prescriptions->appends($request->all());

            return view('prescription.prescriptions', compact('user', 'role', 'prescriptions'));
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.create')) {
            $role = $user->roles[0]->slug;
            $patients = Patient::where('is_deleted', 0)->get();
            $vaccines = \App\VaccineCatalog::active()->orderBy('name')->get();
            $tipoConsultas = \App\TipoConsulta::where('estado', 1)->orderBy('nombre')->get();

            // Obtener parámetros opcionales de la URL
            $preloadPatientId = $request->query('patient_id');
            $preloadAppointmentId = $request->query('appointment_id');

            $preloadPatient = null;
            $preloadAppointment = null;

            if ($preloadAppointmentId) {
                // VERIFICAR SI YA EXISTE UNA CONSULTA PARA ESTA CITA
                // Si la enfermera ya creó la consulta, enviamos al doctor a editar esa misma consulta.
                $existingPrescription = Prescription::where('appointment_id', $preloadAppointmentId)->where('is_deleted', 0)->first();
                if ($existingPrescription) {
                    return redirect('prescription/' . $existingPrescription->id . '/edit')->with('info', 'Esta consulta ya fue iniciada. Continuando edición.');
                }
                
                $preloadAppointment = Appointment::with('patient')->find($preloadAppointmentId);
            }

            if ($preloadPatientId) {
                $preloadPatient = Patient::find($preloadPatientId);
            }

            return view('prescription.prescription-details', compact(
                'user',
                'role',
                'patients',
                'vaccines',
                'tipoConsultas',
                'preloadPatientId',
                'preloadAppointmentId',
                'preloadPatient',
                'preloadAppointment'
            ));
        }
        else {
            return view('error.403');
        }
    }

    /**
     * API: Devuelve los códigos activos de un tipo de consulta (JSON para AJAX)
     */
    public function getCodigosByTipo($tipoConsultaId)
    {
        $codigos = \App\Codigo::where('tipo_consulta_id', $tipoConsultaId)
            ->where('estado', 1)
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'precio']);

        return response()->json($codigos);
    }

    /**
     * Store a newly created resource in storage.
     * dd($request->all(), $request->files->all());
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivos.*.file' => 'nullable|file|max:10240',
            'archivos.*.observaciones' => 'nullable|string|max:255',
        ]);

        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.create')) {
            $request->validate([
                'patient_id_hidden' => 'required',
                'appointment_id'    => 'required',
                'consulta_por'      => 'nullable|string',
                'diagnostico'       => 'nullable|string',
            ]);

            try {
                $user = Sentinel::getUser();

                $this->prescription->patient_id      = $request->patient_id_hidden;
                $this->prescription->appointment_id   = $request->appointment_id;
                $this->prescription->tipo_consulta_id = $request->tipo_consulta_id ?: null;
                $this->prescription->codigo_id        = $request->codigo_id ?: null;
                $this->prescription->precio_consulta  = $request->precio_consulta ?: null;
                $this->prescription->consulta_por     = $request->consulta_por;
                $this->prescription->diagnosis        = $request->diagnosis; // Historia Clínica
                $this->prescription->created_by       = $request->created_by;
                $this->prescription->updated_by       = $user->id;
                $this->prescription->save();

                Signos::updateOrCreate(
                ['patient_id' => $request->patient_id_hidden],
                [
                    'peso' => $request->peso,
                    'talla' => $request->talla,
                    'frec_respiratoria' => $request->frec_respiratoria,
                    'presion_arterial_sistolica' => $request->presion_arterial_sistolica,
                    'presion_arterial_diastolica' => $request->presion_arterial_diastolica,
                    'temperatura' => $request->temperatura,
                    'frec_cardiaca' => $request->frec_cardiaca,
                    'spo' => $request->spo,
                    'examen' => $request->examen,
                    'observaciones_adicionales' => $request->observaciones_adicionales,
                ]
                );

                // Crear Evaluación Mapeando al schema Evaluacion.php y DB
                \App\Evaluacion::create([
                    'prescription_id' => $this->prescription->id,
                    'diagnostico' => $request->diagnostico,
                    'estudios_laboratorios' => $request->estudios_laboratorios,
                    'medicamentos' => $request->tratamiento // Se guarda la receta texto completo
                ]);

                if ($request->has('medicines') && is_array($request->medicines)) {
                    foreach ($request->medicines as $item) {
                        if (!empty($item['name'])) {
                            $medicine = new Medicine();
                            $medicine->prescription_id = $this->prescription->id;
                            $medicine->name = $item['name'];
                            $medicine->notes = $item['notes'];
                            $medicine->save();
                        }
                    }
                }

                if ($request->has('test_reports') && $request->test_reports[0]['test_report'] != null) {
                    foreach ($request->test_reports as $item) {
                        $test_report = new TestReport();
                        $test_report->prescription_id = $this->prescription->id;
                        $test_report->name = $item['test_report'];
                        $test_report->notes = $item['notes'];
                        $test_report->save();
                    }
                }

                if ($request->has('archivos')) {
                    foreach ($request->archivos as $archivo) {
                        if (!isset($archivo['file'])) {
                            continue;
                        }
                        $file = $archivo['file'];
                        if (!$file instanceof \Illuminate\Http\UploadedFile) {
                            continue;
                        }
                        $path = $file->store('clinical_files', 'public');
                        Archivo::create([
                            'prescription_id' => $this->prescription->id,
                            'url_file' => $path,
                            'observaciones' => $archivo['observaciones'] ?? null,
                        ]);
                    }
                }

                // Guardar Vacuna si fue seleccionada (usando nuevo módulo VaccineRecord)
                if ($request->has('vaccine_catalog_id') && !empty($request->vaccine_catalog_id)) {
                    \App\VaccineRecord::create([
                        'patient_id'         => $request->patient_id_hidden,
                        'prescription_id'    => $this->prescription->id,
                        'vaccine_catalog_id' => $request->vaccine_catalog_id,
                        'dose_number'        => $request->dose_number ?? 1,
                        'dose_label'         => $request->dose_label ?? 'Dosis',
                        'status'             => 'applied',
                        'applied_date'       => $request->applied_date ?? date('Y-m-d'),
                        'scheduled_date'     => $request->applied_date ?? date('Y-m-d'),
                        'lot_number'         => $request->lot_number,
                        'applied_by'         => $request->applied_by,
                        'notes'              => $request->vaccine_notes,
                    ]);
                }

                return redirect('prescription')->with('success', 'Prescription created successfully!');
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong!!! ' . $e->getMessage());
            }
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function show(Prescription $prescription)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.show')) {
            $role = $user->roles[0]->slug;
            $oldVacunas = Vacuna::where('prescription_id', $prescription->id)->get()->map(function($v) {
                return (object)[ 'tipo' => $v->tipo, 'dosis' => $v->dosis ];
            });
            $newVacunas = \App\VaccineRecord::with('vaccine')->where('prescription_id', $prescription->id)->get()->map(function($v) {
                return (object)[ 'tipo' => $v->vaccine ? $v->vaccine->name : 'Vacuna', 'dosis' => $v->dose_label ];
            });
            $vacunas = $oldVacunas->concat($newVacunas);
            $user_details = Prescription::with('patient', 'appointment', 'appointment.doctor.user', 'evaluacion', 'archivos')->where('id', $prescription->id)->where('is_deleted', 0)->first();
            if ($user_details) {
                $medicines = Medicine::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                $test_reports = TestReport::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                $signos = Signos::where('patient_id', $prescription->patient_id)->first();
                $evaluacion = $user_details->evaluacion;
                return view('prescription.view-prescription', compact('user', 'role', 'prescription', 'medicines', 'test_reports', 'user_details', 'signos', 'vacunas', 'evaluacion'));
            }
            else {
                return redirect('/dashboard')->with('error', 'prescription not found');
            }
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Prescription $prescription)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.update')) {
            $role = $user->roles[0]->slug;
            $prescription = Prescription::with('patient', 'doctor', 'appointment', 'signos')->where('id', $prescription->id)->where('is_deleted', 0)->first();

            if ($prescription) {
                $patients = Patient::where('is_deleted', 0)->get();
                $appointment = Appointment::where('appointment_for', $prescription->patient_id)->where('is_deleted', 0)->get();
                $medicines = Medicine::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                $test_reports = TestReport::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                
                $oldVacunas = Vacuna::where('prescription_id', $prescription->id)->get()->map(function($v) {
                    return (object)[ 'tipo' => $v->tipo, 'dosis' => $v->dosis ];
                });
                $newVacunas = \App\VaccineRecord::with('vaccine')->where('prescription_id', $prescription->id)->get()->map(function($v) {
                    return (object)[ 'tipo' => $v->vaccine ? $v->vaccine->name : 'Vacuna', 'dosis' => $v->dose_label ];
                });
                $vacunas = $oldVacunas->concat($newVacunas);
                
                $vaccines = \App\VaccineCatalog::active()->orderBy('name')->get();
                $tipoConsultas = \App\TipoConsulta::where('estado', 1)->orderBy('nombre')->get();
                $signos = Signos::where('patient_id', $prescription->patient_id)->first();
                return view('prescription.prescription-edit', compact('user', 'prescription', 'medicines', 'test_reports', 'role', 'patients', 'appointment', 'signos', 'vacunas', 'vaccines', 'tipoConsultas'));
            }
            else {
                return redirect('/dashboard')->with('error', 'Prescription not found');
            }
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prescription $prescription)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.update')) {
            $request->validate([
                'patient_id_hidden' => 'required',
                'appointment_id'    => 'nullable',
                'consulta_por'      => 'nullable|string',
                'diagnostico'       => 'nullable|string',
                'archivos.*.file'   => 'nullable|file|max:10240',
                'archivos.*.observaciones' => 'nullable|string|max:255',
            ]);
            try {
                $prescription = Prescription::find($prescription->id);
                $prescription->patient_id      = $request->patient_id_hidden;
                $prescription->appointment_id   = $request->appointment_id ?: $prescription->appointment_id;
                $prescription->tipo_consulta_id = $request->tipo_consulta_id ?: null;
                $prescription->codigo_id        = $request->codigo_id ?: null;
                $prescription->precio_consulta  = $request->precio_consulta ?: null;
                $prescription->consulta_por     = $request->consulta_por;
                $prescription->diagnosis        = $request->diagnosis; // Historia Clínica
                $prescription->updated_by       = $user->id;
                $prescription->save();

                Vacuna::where('prescription_id', $prescription->id)->delete();
                if ($request->has('vacunas')) {
                    foreach ($request->vacunas as $item) {
                        if (!empty($item['tipo'])) {
                            Vacuna::create([
                                'prescription_id' => $prescription->id,
                                'tipo' => $item['tipo'],
                                'dosis' => $item['dosis'],
                            ]);
                        }
                    }
                }

                Signos::updateOrCreate(
                ['patient_id' => $request->patient_id_hidden],
                [
                    'peso' => $request->peso,
                    'talla' => $request->talla,
                    'frec_respiratoria' => $request->frec_respiratoria,
                    'presion_arterial_sistolica' => $request->presion_arterial_sistolica,
                    'presion_arterial_diastolica' => $request->presion_arterial_diastolica,
                    'temperatura' => $request->temperatura,
                    'frec_cardiaca' => $request->frec_cardiaca,
                    'spo' => $request->spo,
                    'examen' => $request->examen,
                    'observaciones_adicionales' => $request->observaciones_adicionales,
                ]
                );

                \App\Evaluacion::updateOrCreate(
                ['prescription_id' => $prescription->id],
                [
                    'diagnostico' => $request->diagnostico,
                    'estudios_laboratorios' => $request->estudios_laboratorios,
                    'medicamentos' => $request->tratamiento
                ]
                );

                Medicine::where('prescription_id', $prescription->id)->update(['is_deleted' => 1]);
                TestReport::where('prescription_id', $prescription->id)->update(['is_deleted' => 1]);

                if ($request->has('medicines') && is_array($request->medicines)) {
                    foreach ($request->medicines as $item) {
                        if (!empty($item['name'])) {
                            $medicine = new Medicine();
                            $medicine->prescription_id = $prescription->id;
                            $medicine->name = $item['name'];
                            $medicine->notes = $item['notes'];
                            $medicine->save();
                        }
                    }
                }

                if ($request->has('test_reports') && $request->test_reports[0]['test_report'] != null) {
                    foreach ($request->test_reports as $item) {
                        $test_report = new TestReport();
                        $test_report->prescription_id = $prescription->id;
                        $test_report->name = $item['test_report'];
                        $test_report->notes = $item['notes'];
                        $test_report->save();
                    }
                }

                if ($request->has('archivos')) {
                    foreach ($request->archivos as $item) {
                        if (isset($item['file'])) {
                            $path = $item['file']->store('clinical_files', 'public');
                            Archivo::create([
                                'prescription_id' => $prescription->id,
                                'url_file' => $path,
                                'observaciones' => $item['observaciones'] ?? null
                            ]);
                        }
                    }
                }

                // Guardar Vacuna si fue seleccionada (usando nuevo módulo VaccineRecord)
                if ($request->has('vaccine_catalog_id') && !empty($request->vaccine_catalog_id)) {
                    \App\VaccineRecord::create([
                        'patient_id'         => $request->patient_id_hidden,
                        'prescription_id'    => $prescription->id,
                        'vaccine_catalog_id' => $request->vaccine_catalog_id,
                        'dose_number'        => $request->dose_number ?? 1,
                        'dose_label'         => $request->dose_label ?? 'Dosis',
                        'status'             => 'applied',
                        'applied_date'       => $request->applied_date ?? date('Y-m-d'),
                        'scheduled_date'     => $request->applied_date ?? date('Y-m-d'),
                        'lot_number'         => $request->lot_number,
                        'applied_by'         => $request->applied_by,
                        'notes'              => $request->vaccine_notes,
                    ]);
                }

                return redirect('prescription')->with('success', 'Prescription Updated successfully!');
            }
            catch (Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong!!! ' . $e->getMessage());
            }
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescription $prescription)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.delete')) {
            try {
                $prescription = Prescription::find($prescription->id);
                if ($prescription) {
                    $prescription->is_deleted = 1;
                    $prescription->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'prescription find successfully.',
                        'data' => $prescription,
                    ], 200);
                }
                else {
                    return response()->json([
                        'success' => false,
                        'message' => 'prescription not found.',
                        'data' => [],
                    ], 409);
                }
            }
            catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!!!' . $e->getMessage(),
                    'data' => [],
                ], 409);
            }
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'You have no permission to delete doctor',
                'data' => [],
            ], 409);
        }
    }
    public function prescription_list()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        // $prescriptions = Prescription::with(['doctor', 'appointment', 'appointment.timeSlot','appointment.invoice'])
        // ->where('patient_id', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')->paginate($this->limit);
        $prescription = Invoice::where('payment_status', 'Paid')
            ->with('doctor', 'appointment', 'appointment.timeSlot', 'appointment.invoice', 'appointment.prescription')
            ->where('patient_id', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')
            ->paginate($this->limit);
        $prescriptions = collect();
        foreach ($prescription as $key => $value) {
            if ($value['appointment']['prescription']) {
                $prescriptions->push($value['id']);
            }
            else {
                $pre = $prescriptions;
            }
        }
        // $prescriptions_details = Prescription::with('doctor','appointment','appointment.timeSlot','appointment.invoice')->where('patient_id',$user->id)->paginate($this->limit);
        $prescriptions_details = Invoice::where('payment_status', 'Paid')->with('doctor', 'appointment', 'appointment.timeSlot', 'appointment.prescription')
            ->WhereIn('id', $prescriptions)->orderBy('id', 'desc')
            ->paginate($this->limit);
        // return $prescriptions_details;
        return view('patient.patient-prescriptions', compact('user', 'role', 'prescriptions_details'));
    }
    public function prescription_view($id)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $user_details = Prescription::with(['patient', 'appointment', 'appointment.doctor', 'appointment.invoice'])->where('patient_id', $user->id)
            ->where('id', $id)->where('is_deleted', 0)->first();
        // $user_details = Prescription::with('patient','appointment','appointment.timeSlot','appointment.invoice')
        // ->where('patient_id', $user->id)->orWhere('id', $id)->where('is_deleted', 0)->first();
        // return $user_details;
        if ($user_details) {
            if ($user_details->appointment->invoice) {
                $medicines = Medicine::where('prescription_id', $id)->where('is_deleted', 0)->get();
                $test_reports = TestReport::where('prescription_id', $id)->where('is_deleted', 0)->get();
                return view('patient.patient-prescription-view', compact('user', 'role', 'medicines', 'test_reports', 'user_details'));
            }
            else {
                return redirect()->back()->with('error', 'Invoice details not found');
            }
        }
        else {
            return redirect()->back()->with('error', 'Prescription not found');
        }
    }
}
