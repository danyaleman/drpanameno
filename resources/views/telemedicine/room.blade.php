@extends('layouts.master-layouts')
@section('title') Sala de Telemedicina @endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📹 Consulta de Telemedicina</h4>
            <div class="page-title-right">
                <a href="{{ route('telemedicine.index') }}" class="btn btn-secondary btn-sm"><i class="bx bx-arrow-back"></i> Volver</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if($role != 'patient')
    <!-- Columna del Video (Ocupa 8 o 9 de 12 espacios) -->
    <div class="col-lg-9 col-md-8 text-center" style="height: 75vh;">
        <div id="call-container" style="width: 100%; height: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
    </div>
    
    <!-- Ficha del Paciente (Ocupa 3 o 4 de 12 espacios) -->
    <div class="col-lg-3 col-md-4">
        <div class="card shadow-sm" style="height: 75vh; overflow-y: auto; border-radius: 12px;">
            <div class="card-header bg-primary text-white text-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="mb-0 text-white"><i class="bx bx-user-circle me-1"></i> Ficha del Paciente</h5>
            </div>
            <div class="card-body">
                @php 
                    $patient = $teleconsultation->appointment->patient; 
                    $patientInfo = $patient ? $patient->patient_info : null;
                @endphp
                
                @if($patient)
                    <div class="text-center mb-4">
                        <img src="{{ $patient->profile_photo ? url('storage/images/users/' . $patient->profile_photo) : url('assets/images/users/m-0.jpg') }}" 
                             alt="Profile" 
                             class="rounded-circle avatar-lg img-thumbnail mb-2"
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="font-size-16 mb-1">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                        <p class="text-muted mb-0">Paciente</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row"><i class="bx bx-id-card text-muted"></i> DUI:</th>
                                    <td>{{ $patient->dui ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="bx bx-phone text-muted"></i> Tel:</th>
                                    <td>{{ $patient->mobile ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="bx bx-envelope text-muted"></i> Correo:</th>
                                    <td>{{ $patient->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="bx bx-donate-blood text-muted"></i> Sangre:</th>
                                    <td>{{ $patientInfo && $patientInfo->blood_group ? $patientInfo->blood_group : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="bx bx-calendar text-muted"></i> Edad:</th>
                                    <td>
                                        @if($patientInfo && $patientInfo->dob)
                                            {{ \Carbon\Carbon::parse($patientInfo->dob)->age }} años
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><i class="bx bx-body text-muted"></i> Género:</th>
                                    <td>{{ $patientInfo && $patientInfo->gender ? ucfirst($patientInfo->gender) : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <a href="{{ url('patient/' . $patient->id) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bx bx-folder-open"></i> Ver Expediente Completo
                        </a>
                    </div>
                @else
                    <p class="text-muted text-center mt-4">Información no disponible</p>
                @endif
            </div>
        </div>
    </div>
    @else
    <!-- Si es paciente, que vea el video completo -->
    <div class="col-12 text-center" style="height: 75vh;">
        <!-- Daily Prebuilt IFrame Container -->
        <div id="call-container" style="width: 100%; height: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
    </div>
    @endif
</div>

@endsection

@section('script')
    <script crossorigin src="https://unpkg.com/@daily-co/daily-js"></script>
    <script>
        // Use an IIFE or just inline since scripts are at bottom
        (function() {
            var container = document.getElementById('call-container');
            if(!container) return;

            var callFrame = window.DailyIframe.createFrame(
                container, {
                    showLeaveButton: true,
                    iframeStyle: {
                        width: '100%',
                        height: '100%',
                        border: '0',
                    }
                }
            );

            callFrame.join({ url: '{{ $teleconsultation->daily_room_url }}' }).catch(function(e) {
                console.error("Error uniendo a sala:", e);
                alert("Ocurrió un error al cargar la sala de videollamada. Revisa la consola.");
            });

            callFrame.on('joined-meeting', (event) => {
                @if($role == 'doctor')
                    // Iniciar grabación automáticamente si es el doctor
                    callFrame.startRecording().then(() => {
                        console.log("Grabación en la nube iniciada.");
                    }).catch((err) => {
                        console.error("Error al iniciar grabación:", err);
                    });

                    // Iniciar transcripción por IA (si está soportado en la cuenta Daily)
                    if (typeof callFrame.startTranscription === 'function') {
                        callFrame.startTranscription().then(() => {
                            console.log("Transcripción iniciada.");
                        }).catch((err) => {
                            console.error("Error al iniciar transcripción:", err);
                        });
                    }
                @endif
            });

            callFrame.on('left-meeting', (event) => {
                window.location.href = "{{ route('telemedicine.index') }}";
            });
        })();
    </script>
@endsection
