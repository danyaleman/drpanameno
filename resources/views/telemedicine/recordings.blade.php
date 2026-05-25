@extends('layouts.master-layouts')
@section('title') Grabaciones de Teleconsultas @endsection

@section('css')
<style>
    .recording-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .recording-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .recording-item {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f2f5;
        transition: background 0.2s, border-left 0.2s;
        border-left: 3px solid transparent;
    }
    .recording-item:hover {
        background: #f8f9ff;
        border-left-color: #1a73e8;
    }
    .recording-item:last-child {
        border-bottom: none;
    }
    .duration-badge {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .play-btn {
        background: linear-gradient(135deg, #1a73e8, #0d47a1);
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #fff;
        transition: all 0.3s;
    }
    .play-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(26,115,232,0.35);
        color: #fff;
    }
    .delete-btn {
        border: none;
        border-radius: 10px;
        padding: 8px 14px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #fff;
        background: linear-gradient(135deg, #dc3545, #b02a37);
        transition: all 0.3s;
    }
    .delete-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(220,53,69,0.35);
        color: #fff;
    }
    .stat-recording {
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .stat-recording:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state i {
        font-size: 72px;
        color: #e0e0e0;
        margin-bottom: 16px;
    }
    /* Fade-out animation on delete */
    .recording-item.deleting {
        animation: fadeOutRow 0.4s ease forwards;
    }
    @keyframes fadeOutRow {
        from { opacity: 1; transform: translateX(0); }
        to   { opacity: 0; transform: translateX(40px); }
    }
</style>
@endsection

@section('content')
{{-- Encabezado --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🎥 Grabaciones de Teleconsultas</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('telemedicine.index') }}">Teleconsultas</a></li>
                    <li class="breadcrumb-item active">Grabaciones</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Estadísticas --}}
@php
    $totalRecordings = count($recordings);
    $totalDuration = collect($recordings)->sum('duration');
    $totalMinutes = round($totalDuration / 60);
@endphp
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm stat-recording">
            <div class="card-body p-0">
                <div class="d-flex align-items-stretch">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 80px; background: linear-gradient(180deg, #6366f1, #4f46e5);">
                        <i class="bx bx-video text-white" style="font-size: 32px;"></i>
                    </div>
                    <div class="flex-grow-1 p-3">
                        <p class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.2px; color: #6366f1;">Total Grabaciones</p>
                        <h3 class="mb-0 fw-bold" id="stat-total" style="color: #4f46e5;">{{ $totalRecordings }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm stat-recording">
            <div class="card-body p-0">
                <div class="d-flex align-items-stretch">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 80px; background: linear-gradient(180deg, #1a73e8, #0d47a1);">
                        <i class="bx bx-time-five text-white" style="font-size: 32px;"></i>
                    </div>
                    <div class="flex-grow-1 p-3">
                        <p class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.2px; color: #1a73e8;">Minutos Grabados</p>
                        <h3 class="mb-0 fw-bold" style="color: #0d47a1;">{{ $totalMinutes }} min</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-xl-4">
        <div class="card border-0 shadow-sm stat-recording">
            <div class="card-body p-0">
                <div class="d-flex align-items-stretch">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 80px; background: linear-gradient(180deg, #28a745, #1e7e34);">
                        <i class="bx bx-cloud text-white" style="font-size: 32px;"></i>
                    </div>
                    <div class="flex-grow-1 p-3">
                        <p class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.2px; color: #28a745;">Almacenamiento</p>
                        <h3 class="mb-0 fw-bold" style="color: #1e7e34;">Daily.co Cloud</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lista de grabaciones --}}
<div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
    <div class="card-header d-flex align-items-center justify-content-between py-3"
         style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
        <div class="d-flex align-items-center">
            <i class="bx bx-film text-white font-size-20 me-2"></i>
            <h5 class="mb-0 text-white fw-bold">Grabaciones Disponibles</h5>
        </div>
        <span class="badge bg-white text-primary rounded-pill px-3" id="badge-count" style="font-size: 0.85rem;">
            {{ $totalRecordings }} grabaciones
        </span>
    </div>

    <div class="card-body p-0" id="recordings-list">
        @if(count($recordings) > 0)
            @foreach($recordings as $index => $rec)
            @php
                $durationMin = round(($rec['duration'] ?? 0) / 60);
                $durationSec = ($rec['duration'] ?? 0) % 60;
                $createdAt = $rec['created_at'] ? \Carbon\Carbon::createFromTimestamp($rec['created_at'])->timezone('America/El_Salvador') : null;
                $initials = collect(explode(' ', $rec['patient_name']))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');
            @endphp
            <div class="recording-item" id="recording-row-{{ $rec['id'] }}" data-recording-id="{{ $rec['id'] }}">
                <div class="row align-items-center">
                    {{-- Patient info --}}
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3 flex-shrink-0">
                                <div class="avatar-title rounded-circle fw-bold"
                                     style="width: 46px; height: 46px; font-size: 0.9rem; background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff;">
                                    {{ $initials ?: 'P' }}
                                </div>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold text-dark" style="font-size: 0.95rem;">{{ $rec['patient_name'] }}</p>
                                @if($rec['doctor_name'])
                                    <small class="text-muted"><i class="bx bx-user-circle me-1"></i>{{ $rec['doctor_name'] }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Date/Time --}}
                    <div class="col-md-3">
                        @if($createdAt)
                            <div>
                                <p class="mb-0 fw-medium text-dark" style="font-size: 0.88rem;">
                                    <i class="bx bx-calendar text-primary me-1"></i>{{ $createdAt->format('d/m/Y') }}
                                </p>
                                <small class="text-muted">
                                    <i class="bx bx-time me-1"></i>{{ $createdAt->format('h:i A') }}
                                </small>
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>

                    {{-- Duration --}}
                    <div class="col-md-2 text-center">
                        <span class="duration-badge">
                            <i class="bx bx-time-five"></i>
                            {{ $durationMin }}:{{ str_pad($durationSec, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="col-md-3 text-end d-flex align-items-center justify-content-end gap-2">
                        <a href="{{ route('telemedicine.recording.play', $rec['id']) }}"
                           class="play-btn btn btn-sm">
                            <i class="bx bx-play me-1"></i>Reproducir
                        </a>
                        <button type="button"
                                class="delete-btn btn btn-sm btn-delete-recording"
                                data-id="{{ $rec['id'] }}"
                                data-patient="{{ $rec['patient_name'] }}"
                                data-date="{{ $createdAt ? $createdAt->format('d/m/Y H:i') : 'Fecha desconocida' }}"
                                title="Eliminar grabación">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state" id="empty-state">
                <i class="bx bx-video-off d-block"></i>
                <h5 class="text-muted fw-semibold mb-2">No hay grabaciones disponibles</h5>
                <p class="text-muted mb-0">Las grabaciones aparecerán aquí cuando se realicen teleconsultas con la grabación activada.</p>
            </div>
        @endif
    </div>
</div>

{{-- Modal de confirmación de eliminación --}}
<div class="modal fade" id="deleteRecordingModal" tabindex="-1" aria-labelledby="deleteRecordingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #dc3545, #b02a37); padding: 20px 24px;">
                <h5 class="modal-title fw-bold" id="deleteRecordingModalLabel">
                    <i class="bx bx-trash me-2"></i>Eliminar Grabación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div style="width:70px;height:70px;background:rgba(220,53,69,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="bx bx-error-circle" style="font-size:36px;color:#dc3545;"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">¿Estás seguro de eliminar esta grabación?</h6>
                    <p class="text-muted mb-0" style="font-size:14px;">Esta acción es <strong>irreversible</strong> y eliminará el archivo de la nube de Daily.co.</p>
                </div>
                <div class="alert alert-light border" style="border-radius:10px; font-size:13px;">
                    <div class="mb-1"><i class="bx bx-user text-primary me-1"></i><span id="modal-patient-name" class="fw-semibold"></span></div>
                    <div><i class="bx bx-calendar text-muted me-1"></i><span id="modal-rec-date" class="text-muted"></span></div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-light fw-semibold px-4" data-bs-dismiss="modal" style="border-radius:10px;">
                    <i class="bx bx-x me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger fw-bold px-4" id="btn-confirm-delete" style="border-radius:10px;">
                    <span id="delete-btn-text"><i class="bx bx-trash me-1"></i>Sí, eliminar</span>
                    <span id="delete-btn-spinner" class="spinner-border spinner-border-sm ms-1" style="display:none;"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var recordingIdToDelete = null;
    var totalCount = {{ $totalRecordings }};

    // Abrir modal al hacer click en botón eliminar
    $(document).on('click', '.btn-delete-recording', function() {
        recordingIdToDelete = $(this).data('id');
        var patientName    = $(this).data('patient');
        var recDate        = $(this).data('date');

        $('#modal-patient-name').text(patientName);
        $('#modal-rec-date').text(recDate);

        // Resetear botón
        $('#delete-btn-text').show();
        $('#delete-btn-spinner').hide();
        $('#btn-confirm-delete').prop('disabled', false);

        $('#deleteRecordingModal').modal('show');
    });

    // Confirmar eliminación
    $('#btn-confirm-delete').on('click', function() {
        if (!recordingIdToDelete) return;

        // Mostrar spinner
        $('#delete-btn-text').hide();
        $('#delete-btn-spinner').show();
        $(this).prop('disabled', true);

        $.ajax({
            url: '/telemedicine/recording/' + recordingIdToDelete,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function(res) {
                if (res.success) {
                    // Cerrar modal
                    $('#deleteRecordingModal').modal('hide');

                    // Animar y remover fila
                    var $row = $('#recording-row-' + recordingIdToDelete);
                    $row.addClass('deleting');
                    setTimeout(function() {
                        $row.remove();
                        totalCount--;

                        // Actualizar contador
                        $('#stat-total').text(totalCount);
                        $('#badge-count').text(totalCount + ' grabaciones');

                        // Mostrar estado vacío si no quedan grabaciones
                        if (totalCount <= 0) {
                            $('#recordings-list').html(
                                '<div class="empty-state" id="empty-state">' +
                                '<i class="bx bx-video-off d-block"></i>' +
                                '<h5 class="text-muted fw-semibold mb-2">No hay grabaciones disponibles</h5>' +
                                '<p class="text-muted mb-0">Las grabaciones aparecerán aquí cuando se realicen teleconsultas con la grabación activada.</p>' +
                                '</div>'
                            );
                        }

                        toastr.success(res.message || 'Grabación eliminada correctamente.');
                    }, 420);
                } else {
                    toastr.error(res.message || 'No se pudo eliminar la grabación.');
                    $('#delete-btn-text').show();
                    $('#delete-btn-spinner').hide();
                    $('#btn-confirm-delete').prop('disabled', false);
                }
            },
            error: function(xhr) {
                var msg = 'Error al conectar con el servidor.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
                $('#delete-btn-text').show();
                $('#delete-btn-spinner').hide();
                $('#btn-confirm-delete').prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
