<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Invoice;
use App\InvoiceDetail;
use App\Patient;
use App\User;
use App\MedicalInfo;
use App\Prescription;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class PatientController extends Controller
{
    protected $patient_info, $medical_info, $MedicalInfo;
    public $limit;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('sentinel.auth');
        $this->patient_info = new Patient();
        $this->medical_info = new MedicalInfo();
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
        if ($user->hasAccess('patient.list')) {
            $role = $user->roles[0]->slug;

            // Load Datatables
            if ($request->ajax()) {
                $patients = Patient::where('is_deleted', 0)->orderByDesc('id')->get();
                return Datatables::of($patients)
                    ->addIndexColumn()
                    ->addColumn('name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                    ->addColumn('mobile', function ($row) {
                    return $row->phone_primary ?? 'N/A';
                })
                    ->addColumn('email', function ($row) {
                    return $row->email ?? 'N/A';
                })
                    ->addColumn('option', function ($row) {
                    $option = '
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="patient/' . $row->id . '" class="btn btn-sm btn-outline-primary waves-effect" title="Ver Perfil">
                                    <i class="bx bx-show"></i>
                                </a>
                                <a href="patient/' . $row->id . '/edit" class="btn btn-sm btn-success waves-effect" title="Editar Perfil">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger waves-effect" title="Eliminar Paciente" data-id="' . $row->id . '" id="delete-patient">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>';
                    return $option;
                })->rawColumns(['option'])->make(true);
            }
            // Para carga inicial (no AJAX), pasar datos vacíos
            $patients = collect();
            return view('patient.patients', compact('user', 'role', 'patients'));
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
    public function create()
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('patient.create')) {
            $role = $user->roles[0]->slug;
            $patient = null;
            $patient_info = null;
            $medical_info = null;
            return view('patient.patient-details', compact('user', 'role', 'patient', 'patient_info', 'medical_info'));
        }
        else {
            return view('error.403');

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('patient.create')) {
            $validatedData = $request->validate([
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'phone_primary' => 'required|numeric|digits_between:8,20',
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:50',
                'birth_date' => 'required|date',
                'address' => 'required|max:100',
                'gender' => 'required',
                'photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:500'
            ]);

            if ($request->photo != null) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $extension;
                $file->move(public_path('storage/images/patients'), $imageName);
                $validatedData['photo'] = $imageName;
            }

            try {
                // Crear paciente directamente sin crear usuario
                $patient = Patient::create($validatedData);

                // Crear información médica si se proporciona
                if ($request->has(['height', 'weight', 'b_group', 'b_pressure', 'pulse', 'respiration', 'allergy', 'diet'])) {
                    MedicalInfo::create([
                        'patient_id' => $patient->id,
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'b_group' => $request->b_group,
                        'b_pressure' => $request->b_pressure,
                        'pulse' => $request->pulse,
                        'respiration' => $request->respiration,
                        'allergy' => $request->allergy,
                        'diet' => $request->diet,
                    ]);
                }

                // Enviar email de bienvenida
                $app_name = AppSetting('title');
                $verify_mail = trim($request->email);
                try {
                    Mail::send('emails.PatientWelcomeEmail', ['patient' => $patient], function ($message) use ($verify_mail, $app_name) {
                        $message->to($verify_mail);
                        $message->subject($app_name . ' - Nuevo paciente registrado');
                    });
                }
                catch (\Exception $e) {
                    // Log email error pero continuar
                    \Log::error('Error sending patient welcome email: ' . $e->getMessage());
                }

                return redirect('/patient')->with('success', '¡Paciente creado exitosamente!');
            }
            catch (Exception $e) {
                return redirect('patient')->with('error', 'Algo salió mal: ' . $e->getMessage());
            }
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('patient.view')) {
            $role = $user->roles[0]->slug;
            $patient = Patient::where('id', $id)->where('is_deleted', 0)->first();

            if ($patient) {
                $medical_Info = $patient->medicalInfo;

                // Buscar citas por patient_id (nuevas) O por appointment_for si existe un usuario con el mismo email
                $appointmentQuery = Appointment::where('patient_id', $patient->id);

                // Para citas antiguas: buscar por appointment_for (user_id) del usuario con mismo email
                $userWithSameEmail = User::where('email', $patient->email)->first();
                if ($userWithSameEmail) {
                    $appointmentQuery->orWhere('appointment_for', $userWithSameEmail->id);
                }

                $appointments = $appointmentQuery->with('doctor')->orderBy('id', 'desc')->paginate($this->limit, '*', 'appointment');

                $prescriptions = $patient->prescriptions()->with('doctor')->orderBy('id', 'desc')->paginate($this->limit, '*', 'prescriptions');
                $invoices = $patient->invoices()->orderBy('id', 'desc')->paginate($this->limit, '*', 'invoice');

                // Contar todas las citas (nuevas + antiguas)
                $tot_appointment = Appointment::where('patient_id', $patient->id);
                if ($userWithSameEmail) {
                    $tot_appointment->orWhere('appointment_for', $userWithSameEmail->id);
                }
                $tot_appointment = $tot_appointment->count();

                $invoice = $patient->invoices()->where('is_deleted', 0)->pluck('id');
                $revenue = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');
                $pending_bill = $patient->invoices()->where('payment_status', 'Unpaid')->count();

                $data = [
                    'total_appointment' => $tot_appointment,
                    'revenue' => $revenue,
                    'pending_bill' => $pending_bill
                ];

                return view('patient.patient-profile', compact('user', 'role', 'patient', 'medical_Info', 'data', 'appointments', 'prescriptions', 'invoices'));
            }
            else {
                return redirect('/dashboard')->with('error', 'Paciente no encontrado');
            }
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Sentinel::getUser();
        $patient = Patient::where('id', $id)->where('is_deleted', 0)->first();

        if ($patient) {
            if ($user->hasAccess('patient.update')) {
                $role = $user->roles[0]->slug;
                $medical_info = $patient->medicalInfo;
                $patient_info = $patient;
                return view('patient.patient-details', compact('user', 'role', 'patient', 'patient_info', 'medical_info'));
            }
            else {
                return view('error.403');
            }
        }
        else {
            return redirect('/dashboard')->with('error', 'Paciente no encontrado');
        }
    }

    public function patientClinicalInfo(Request $request)
    {
        $patient = Patient::findOrFail($request->patient_id);

        // Si no encontramos el perfil, devolver error
        if (!$patient) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'Perfil de paciente no encontrado'
            ]);
        }

        // Buscamos las citas del paciente
        $appointments = $patient->appointments()->orderBy('appointment_date', 'desc')->get();

        // Opción predeterminada y opción "Sin Cita"
        $options = '<option value="" selected disabled>Seleccionar Cita</option>';
        $options .= '<option value="0">⚠️ Sin Cita (Atención Inmediata)</option>';

        foreach ($appointments as $appointment) {
            $options .= '<option value="' . $appointment->id . '">'
                . \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') . ' ' .
                ($appointment->AvailableTime ? ' - ' . $appointment->AvailableTime->from . ' a ' . $appointment->AvailableTime->to : '') .
                '</option>';
        }

        $age = $patient->age; // Ahora disponible como accessor

        return response()->json([
            'isSuccess' => true,
            'options' => $options,
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->first_name . ' ' . $patient->last_name,
                'info' => trim(
                ($age ? $age . ' años · ' : '') .
                ($patient->occupation ?? '') .
                ($patient->address ? ' · ' . $patient->address : '')
            ),
                // Datos adicionales para el Tab de Información General
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'gender' => $patient->gender,
                'dui' => $patient->dui,
                'dob' => $patient->birth_date, // Date of Birth
                'age' => $age,
                'mobile' => $patient->phone_primary,
                'email' => $patient->email,
                'blood_group' => $patient->medicalInfo->b_group ?? null,
                'marital_status' => $patient->marital_status,
                'address' => $patient->address,
                'occupation' => $patient->occupation ?? 'No especificada',
                'workplace' => $patient->workplace ?? 'No especificado',
                'referred_by' => $patient->referred_by ?? 'Ninguno',
                'profile_photo_url' => $patient->photo ? asset('storage/images/patients/' . $patient->photo) : null, // Si existe

                // Antecedentes
                'pathological_history' => $patient->pathological_history,
                'non_pathological_history' => $patient->non_pathological_history,
                'medications_allergies' => $patient->medications_allergies,
            ]
        ]);
    }

    public function patientByAppointment(Request $request)
    {
        // Esta función hace básicamente lo mismo
        return $this->patientClinicalInfo($request);
    }

    public function headerInfo(Request $request)
    {
        $patient = Patient::findOrFail($request->patient_id);

        return response()->json([
            'name' => $patient->full_name,
            'phone' => $patient->phone_primary ?? '',
            'email' => $patient->email ?? '',
            'age' => $patient->age ?? '—',
            'address' => $patient->address
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('patient.update')) {
            $patient = Patient::findOrFail($id);

            $validatedData = $request->validate([
                'first_name' => 'required|alpha',
                'last_name' => 'required|alpha',
                'phone_primary' => 'required|numeric|digits_between:8,20',
                'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|max:50',
                'birth_date' => 'required|date',
                'address' => 'required|max:100',
                'gender' => 'required',
                'photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:500'
            ]);

            try {
                // Manejar foto
                if ($request->hasFile('photo')) {
                    if ($patient->photo && File::exists(public_path('storage/images/patients/' . $patient->photo))) {
                        File::delete(public_path('storage/images/patients/' . $patient->photo));
                    }
                    $file = $request->file('photo');
                    $extension = $file->getClientOriginalExtension();
                    $imageName = time() . '.' . $extension;
                    $file->move(public_path('storage/images/patients'), $imageName);
                    $validatedData['photo'] = $imageName;
                }

                // Actualizar paciente
                $patient->update($validatedData);

                // Actualizar información médica
                $medical_info = $patient->medicalInfo;
                if ($request->has(['height', 'weight', 'b_group', 'b_pressure', 'pulse', 'respiration', 'allergy', 'diet'])) {
                    $medicalData = [
                        'height' => $request->height,
                        'weight' => $request->weight,
                        'b_group' => $request->b_group,
                        'b_pressure' => $request->b_pressure,
                        'pulse' => $request->pulse,
                        'respiration' => $request->respiration,
                        'allergy' => $request->allergy,
                        'diet' => $request->diet,
                    ];

                    if ($medical_info) {
                        $medical_info->update($medicalData);
                    }
                    else {
                        $medicalData['patient_id'] = $patient->id;
                        MedicalInfo::create($medicalData);
                    }
                }

                $role = $user->roles[0]->slug;
                if ($role == 'patient') {
                    return redirect('/dashboard')->with('success', '¡Perfil actualizado exitosamente!');
                }
                else {
                    return redirect('patient')->with('success', '¡Perfil del paciente actualizado exitosamente!');
                }
            }
            catch (Exception $e) {
                return redirect('patient')->with('error', 'Algo salió mal: ' . $e->getMessage());
            }
        }
        else {
            return view('error.403');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Sentinel::getUser();
        $patient = Patient::where('id', $request->id)->where('is_deleted', 0)->first();

        if ($patient) {
            if ($user->hasAccess('patient.delete')) {
                try {
                    // Soft delete del paciente
                    $patient->is_deleted = 1;
                    $patient->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Paciente eliminado exitosamente.',
                        'data' => $patient,
                    ], 200);
                }
                catch (Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Algo salió mal: ' . $e->getMessage(),
                        'data' => [],
                    ], 409);
                }
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para eliminar pacientes',
                    'data' => [],
                ], 409);
            }
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Paciente no encontrado',
                'data' => [],
            ], 404);
        }
    }
}
