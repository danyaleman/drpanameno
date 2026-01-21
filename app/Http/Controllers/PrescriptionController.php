<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Invoice;
use App\Prescription;
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
            } else {
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
    public function index()
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('prescription.list')) {
            $role = $user->roles[0]->slug;
            if ($role == 'doctor') {
                $prescriptions = Prescription::with('patient', 'appointment', 'appointment.timeSlot')->where('created_by', '=', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')->paginate($this->limit);
            } elseif ($role == 'patient') {
                $prescriptions = Prescription::with('doctor', 'appointment', 'appointment.timeSlot')->where('patient_id', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')->paginate($this->limit);
            } else {
                $prescriptions = Prescription::with('patient', 'doctor', 'appointment', 'appointment.timeSlot')->where('is_deleted', 0)->orderBy('id', 'desc')->paginate($this->limit);
            }
            return view('prescription.prescriptions', compact('user', 'role', 'prescriptions'));
        } else {
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
        if ($user->hasAccess('prescription.create')) {
            $role = $user->roles[0]->slug;
            $patient_role = Sentinel::findRoleBySlug('patient');
            $patients = $patient_role->users()->with('roles')->get();
            return view('prescription.prescription-details', compact('user', 'role', 'patients'));
        } else {
            return view('error.403');
        }
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
                'patient_id' => 'required',
                'appointment_id' => 'required',
                'symptoms' => 'required',
                'diagnosis' => 'required'
            ]);

            try {
                $user = Sentinel::getUser();
                if ($request->medicines[0]['medicine'] == null && $request->medicines[0]['notes'] == null) {
                    return redirect()->back()->with('error', 'Add at least one medicine to create prescription!!!');
                } else {
                    $this->prescription->patient_id = $request->patient_id;
                    $this->prescription->appointment_id = $request->appointment_id;
                    $this->prescription->symptoms = $request->symptoms;
                    $this->prescription->diagnosis = $request->diagnosis;
                    $this->prescription->created_by = $request->created_by;
                    $this->prescription->updated_by = $user->id;
                    $this->prescription->save();
                    Signos::create([
                        'prescription_id' => $this->prescription->id,
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
                    ]);
                    foreach ($request->medicines as $item) {
                        $this->medicine = new Medicine();
                        $this->medicine->prescription_id = $this->prescription->id;
                        $this->medicine->name = $item['medicine'];
                        $this->medicine->notes = $item['notes'];
                        $this->medicine->save();
                    }
                    if ($request->test_reports[0]['test_report'] != null && $request->test_reports[0]['notes'] != null) {
                        foreach ($request->test_reports as $item) {
                            $this->test_report = new TestReport();
                            $this->test_report->prescription_id = $this->prescription->id;
                            $this->test_report->name = $item['test_report'];
                            $this->test_report->notes = $item['notes'];
                            $this->test_report->save();
                        }
                    }
                    if ($request->has('archivos')) {
                            foreach ($request->archivos as $archivo) {

                                // si no subieron archivo, saltar
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

                    if ($request->has('vacunas')) {
                        foreach ($request->vacunas as $item) {
                            if (!empty($item['tipo'])) {
                                Vacuna::create([
                                    'prescription_id' => $this->prescription->id,
                                    'tipo' => $item['tipo'],
                                    'dosis' => $item['dosis'],
                                ]);
                            }
                        }
                    }


                    return redirect('prescription')->with('success', 'Prescription created successfully!');
                }
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong!!! ' . $e->getMessage());
            }
        } else {
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
            $vacunas = Vacuna::where('prescription_id', $prescription->id)->get();
            $user_details = Prescription::with('patient', 'appointment', 'appointment.doctor.user')->where('id', $prescription->id)->where('is_deleted', 0)->first();
            if ($user_details) {
                $medicines = Medicine::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                $test_reports = TestReport::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                $signos = Signos::where('prescription_id', $prescription->id)->first();
                return view('prescription.view-prescription', compact('user', 'role', 'prescription', 'medicines', 'test_reports', 'user_details','signos','vacunas'));
            } else {
                return redirect('/dashboard')->with('error', 'prescription not found');
            }
        } else {
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
            $prescription = Prescription::with('patient','doctor','appointment','signos')->where('id', $prescription->id)->where('is_deleted', 0)->first();

            if ($prescription) {
                $patient_role = Sentinel::findRoleBySlug('patient');
                $patients = $patient_role->users()->with('roles')->get();
                $appointment = Appointment::where('appointment_for', $prescription->patient->id)->where('is_deleted', 0)->get();
                $medicines = Medicine::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                $test_reports = TestReport::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
                $vacunas = Vacuna::where('prescription_id', $prescription->id)->get();
                $signos = Signos::where('prescription_id', $prescription->id)->first();
                return view('prescription.prescription-edit', compact('user', 'prescription', 'medicines', 'test_reports', 'role', 'patients', 'appointment','signos','vacunas'));
            } else {
                return redirect('/dashboard')->with('error', 'Prescription not found');
            }
        } else {
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
                'patient_id' => 'required',
                'appointment_id' => 'required',
                'symptoms' => 'required',
                'diagnosis' => 'required'
            ]);
            try {
                if ($request->medicines[0]['medicine'] == null && $request->medicines[0]['notes'] == null) {
                    return redirect()->back()->with('error', 'Add at least one medicine to create prescription!!!');
                } else {
                    $prescription = Prescription::find($prescription->id);
                    $prescription->patient_id = $request->patient_id;
                    $prescription->appointment_id = $request->appointment_id;
                    $prescription->symptoms = $request->symptoms;
                    $prescription->diagnosis = $request->diagnosis;
                    $prescription->updated_by = $user->id;
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
                        ['prescription_id' => $prescription->id],
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
                    $medicine = Medicine::where('prescription_id', $prescription->id)->update(['is_deleted' => 1]);
                    $test_report = TestReport::where('prescription_id', $prescription->id)->update(['is_deleted' => 1]);

                    foreach ($request->medicines as $item) {
                        $medicine = new Medicine();
                        $medicine->prescription_id = $request->prescription->id;
                        $medicine->name = $item['medicine'];
                        $medicine->notes = $item['notes'];
                        $medicine->save();
                    }
                    if ($request->test_reports[0]['test_report'] != null && $request->test_reports[0]['notes'] != null) {
                        foreach ($request->test_reports as $item) {
                            $test_report = new TestReport();
                            $test_report->prescription_id = $request->prescription->id;
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

                    return redirect('prescription')->with('success', 'Prescription Updated successfully!');
                }
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Something went wrong!!! ' . $e->getMessage());
            }
        } else {
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
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'prescription not found.',
                        'data' => [],
                    ], 409);
                }
            } catch (Exception $e) {
                return response()->json([
                    'success' =>false,
                    'message' => 'Something went wrong!!!'.$e->getMessage(),
                    'data' =>[],
                ],409);
            }
        } else {
            return response()->json([
                'success' =>false,
                'message'=>'You have no permission to delete doctor',
                'data'=>[],
            ],409);
        }
    }
    public function prescription_list()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        // $prescriptions = Prescription::with(['doctor', 'appointment', 'appointment.timeSlot','appointment.invoice'])
        // ->where('patient_id', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')->paginate($this->limit);
        $prescription = Invoice::where('payment_status','Paid')
        ->with('doctor', 'appointment','appointment.timeSlot','appointment.invoice','appointment.prescription')
        ->where('patient_id', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')
        ->paginate($this->limit);
        $prescriptions = collect();
        foreach ($prescription as $key => $value) {
            if($value['appointment']['prescription']){
                $prescriptions->push($value['id']);
            }
            else{
                $pre = $prescriptions;
            }
        }
        // $prescriptions_details = Prescription::with('doctor','appointment','appointment.timeSlot','appointment.invoice')->where('patient_id',$user->id)->paginate($this->limit);
        $prescriptions_details = Invoice::where('payment_status','Paid')->with('doctor','appointment', 'appointment.timeSlot','appointment.prescription')
            ->WhereIn('id',$prescriptions)->orderBy('id', 'desc')
            ->paginate($this->limit);
        // return $prescriptions_details;
        return view('patient.patient-prescriptions', compact('user', 'role', 'prescriptions_details'));
    }
    public function prescription_view($id)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $user_details = Prescription::with(['patient', 'appointment', 'appointment.doctor','appointment.invoice'])->where('patient_id', $user->id)
        ->where('id', $id)->where('is_deleted', 0)->first();
        // $user_details = Prescription::with('patient','appointment','appointment.timeSlot','appointment.invoice')
        // ->where('patient_id', $user->id)->orWhere('id', $id)->where('is_deleted', 0)->first();
        // return $user_details;
        if ($user_details) {
            if($user_details->appointment->invoice){
                $medicines = Medicine::where('prescription_id', $id)->where('is_deleted', 0)->get();
                $test_reports = TestReport::where('prescription_id', $id)->where('is_deleted', 0)->get();
                return view('patient.patient-prescription-view', compact('user', 'role', 'medicines', 'test_reports', 'user_details'));
            }
            else{
                return redirect()->back()->with('error', 'Invoice details not found');
            }
        } else {
            return redirect()->back()->with('error', 'Prescription not found');
        }
    }
}
