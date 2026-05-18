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
        if (!in_array($role, ['admin', 'doctor', 'patient', 'receptionist'])) {
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

        $teleconsultation = Teleconsultation::with(['appointment', 'appointment.patient'])->findOrFail($id);

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

        // Verificar si la sala sigue activa en Daily.co; si no, recrearla
        $dailyService = new \App\Services\DailyService();
        if (!$dailyService->roomExists($teleconsultation->daily_room_name)) {
            \Log::info("Sala Daily.co expirada ({$teleconsultation->daily_room_name}), recreando...");
            $newRoomName = 'teleconsulta-' . $teleconsultation->appointment_id . '-' . time();
            $newRoom = $dailyService->createRoom($newRoomName);
            if ($newRoom) {
                $teleconsultation->daily_room_url  = $newRoom['url'];
                $teleconsultation->daily_room_name = $newRoom['name'];
                $teleconsultation->save();
                \Log::info("Sala recreada: {$newRoom['url']}");
            } else {
                return redirect()->back()->with('error', 'No se pudo crear la sala de telemedicina. Intenta de nuevo.');
            }
        }

        // Marcar como en curso si entraron
        if ($teleconsultation->status == 'pending') {
            $teleconsultation->status = 'in-progress';
            if (!$teleconsultation->started_at) {
                $teleconsultation->started_at = now();
            }
            $teleconsultation->save();
        }

        return view('telemedicine.room', compact('teleconsultation', 'role'));
    }

    public function destroy($id)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        // Solo admin o doctor pueden borrar (o ajustarlo según necesidades)
        if (!in_array($role, ['admin', 'doctor', 'receptionist'])) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar esta sala.');
        }

        $teleconsultation = Teleconsultation::findOrFail($id);

        // Eliminar sala en Daily.co
        if ($teleconsultation->daily_room_name) {
            $dailyService = new \App\Services\DailyService();
            $deleted = $dailyService->deleteRoom($teleconsultation->daily_room_name);
            if ($deleted) {
                \Log::info("Sala Daily.co eliminada: {$teleconsultation->daily_room_name}");
            } else {
                \Log::warning("No se pudo eliminar o ya no existía la sala en Daily.co: {$teleconsultation->daily_room_name}");
            }
        }

        // Eliminar registro de BD
        $teleconsultation->delete();

        return redirect()->route('telemedicine.index')->with('success', 'Sala de telemedicina eliminada correctamente.');
    }

    /**
     * Listado de grabaciones de teleconsultas desde Daily.co API
     */
    public function recordings()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if (!in_array($role, ['admin', 'doctor'])) {
            return redirect('/')->with('error', 'No tienes permiso para ver grabaciones.');
        }

        $dailyService = new \App\Services\DailyService();
        $apiResponse = $dailyService->getRecordings(100);

        $recordings = [];

        if ($apiResponse && isset($apiResponse['data'])) {
            // Pre-load all teleconsultations with their appointments for cross-referencing
            $teleconsultations = Teleconsultation::with(['appointment', 'appointment.patient', 'appointment.doctor', 'appointment.doctor.user'])
                ->get()
                ->keyBy('daily_room_name');

            foreach ($apiResponse['data'] as $rec) {
                $roomName = $rec['room_name'] ?? '';
                $teleconsultation = $teleconsultations->get($roomName);

                // If doctor, only show their own recordings
                if ($role === 'doctor' && $teleconsultation) {
                    if ($user->doctor && $teleconsultation->appointment->appointment_with != $user->doctor->id) {
                        continue;
                    }
                }

                $patientName = 'Paciente desconocido';
                $doctorName = '';
                $appointmentDate = null;
                $appointmentTime = null;
                $appointmentId = null;

                if ($teleconsultation && $teleconsultation->appointment) {
                    $apt = $teleconsultation->appointment;
                    if ($apt->patient) {
                        $patientName = trim(($apt->patient->first_name ?? '') . ' ' . ($apt->patient->last_name ?? ''));
                    }
                    if ($apt->doctor && $apt->doctor->user) {
                        $doctorName = 'Dr. ' . trim(($apt->doctor->user->first_name ?? '') . ' ' . ($apt->doctor->user->last_name ?? ''));
                    }
                    $appointmentDate = $apt->appointment_date;
                    $appointmentId = $apt->id;
                }

                $recordings[] = [
                    'id' => $rec['id'],
                    'room_name' => $roomName,
                    'duration' => $rec['duration'] ?? 0,
                    'status' => $rec['status'] ?? 'unknown',
                    'created_at' => $rec['start_ts'] ?? ($rec['created_at'] ?? null),
                    'patient_name' => $patientName,
                    'doctor_name' => $doctorName,
                    'appointment_date' => $appointmentDate,
                    'appointment_id' => $appointmentId,
                    'tracks' => $rec['tracks'] ?? [],
                ];
            }
        }

        return view('telemedicine.recordings', compact('recordings', 'role'));
    }

    /**
     * Reproducir/ver una grabación específica
     */
    public function recordingPlay($recordingId)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if (!in_array($role, ['admin', 'doctor'])) {
            return redirect('/')->with('error', 'No tienes permiso para ver grabaciones.');
        }

        $dailyService = new \App\Services\DailyService();

        // Get recording details
        $recording = $dailyService->getRecording($recordingId);
        if (!$recording) {
            return redirect()->route('telemedicine.recordings')->with('error', 'No se pudo cargar la grabación.');
        }

        // Get temporary access link for playback
        $accessLink = $dailyService->getRecordingAccessLink($recordingId);

        // Try to get transcript
        $transcript = $dailyService->getTranscript($recordingId);

        // Cross-reference with local teleconsultation
        $roomName = $recording['room_name'] ?? '';
        $teleconsultation = Teleconsultation::with(['appointment', 'appointment.patient', 'appointment.doctor', 'appointment.doctor.user'])
            ->where('daily_room_name', $roomName)
            ->first();

        $patientName = 'Paciente desconocido';
        $doctorName = '';
        $appointmentDate = null;

        if ($teleconsultation && $teleconsultation->appointment) {
            $apt = $teleconsultation->appointment;
            if ($apt->patient) {
                $patientName = trim(($apt->patient->first_name ?? '') . ' ' . ($apt->patient->last_name ?? ''));
            }
            if ($apt->doctor && $apt->doctor->user) {
                $doctorName = 'Dr. ' . trim(($apt->doctor->user->first_name ?? '') . ' ' . ($apt->doctor->user->last_name ?? ''));
            }
            $appointmentDate = $apt->appointment_date;
        }

        return view('telemedicine.recording-play', compact(
            'recording', 'accessLink', 'transcript',
            'patientName', 'doctorName', 'appointmentDate', 'role'
        ));
    }
}
