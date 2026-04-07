<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Teleconsultation;
use App\Appointment;

class TelemedicineController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel.auth');
    }

    public function index()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        // Limitar roles que pueden ver (Opcional, middleware también serviría)
        if (!in_array($role, ['admin', 'doctor', 'patient'])) {
            return redirect('/')->with('error', 'No tienes permiso para acceder a Telemedicina.');
        }

        $query = Teleconsultation::with(['appointment', 'appointment.patient', 'appointment.doctor', 'appointment.doctor.user'])
            ->orderBy('created_at', 'DESC');

        if ($role == 'patient') {
            $query->whereHas('appointment', function ($q) use ($user) {
                $q->where('patient_id', $user->id);
            });
        } elseif ($role == 'doctor') {
            if ($user->doctor) {
                $query->whereHas('appointment', function ($q) use ($user) {
                    $q->where('appointment_with', $user->doctor->id);
                });
            }
        }

        $teleconsultations = $query->get();

        return view('telemedicine.index', compact('teleconsultations', 'role'));
    }

    public function room($id)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        $teleconsultation = Teleconsultation::with('appointment')->findOrFail($id);

        // Seguridad: verificar si el usuario es parte de la cita (omitido para admin)
        if ($role == 'patient') {
            if ($teleconsultation->appointment->patient_id != $user->id) {
                return redirect()->back()->with('error', 'Acceso denegado a esta sala.');
            }
        } elseif ($role == 'doctor') {
            if ($user->doctor && $teleconsultation->appointment->appointment_with != $user->doctor->id) {
                return redirect()->back()->with('error', 'Acceso denegado a esta sala.');
            }
        }

        // Marcar como en curso si entraron (puedes ajustar lógica luego)
        if ($teleconsultation->status == 'pending') {
            $teleconsultation->status = 'in-progress';
            if (!$teleconsultation->started_at) {
                $teleconsultation->started_at = now();
            }
            $teleconsultation->save();
        }

        return view('telemedicine.room', compact('teleconsultation', 'role'));
    }
}
