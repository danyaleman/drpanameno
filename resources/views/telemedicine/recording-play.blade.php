@extends('layouts.master-layouts')
@section('title') Reproducir Grabación @endsection

@section('css')
<style>
    .player-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .video-container {
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        aspect-ratio: 16/9;
    }
    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .info-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6c757d;
        margin-bottom: 2px;
    }
    .info-value {
        font-weight: 600;
        color: #344054;
        font-size: 0.95rem;
    }
    .transcript-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .transcript-text {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        font-size: 0.9rem;
        line-height: 1.7;
        max-height: 400px;
        overflow-y: auto;
        white-space: pre-wrap;
    }
    .btn-download {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s;
    }
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40,167,69,0.35);
        color: #fff;
    }
    .btn-back {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        color: #344054;
        transition: all 0.3s;
    }
    .btn-back:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: rgba(99,102,241,0.05);
    }
    .no-video-msg {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        color: #999;
    }
    .no-video-msg i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
</style>
@endsection

@section('content')
{{-- Encabezado --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🎬 Reproducir Grabación</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('telemedicine.recordings') }}">Grabaciones</a></li>
                    <li class="breadcrumb-item active">Reproducir</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Columna izquierda: Video Player --}}
    <div class="col-lg-8">
        <div class="card player-card mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-play-circle text-white font-size-22 me-2"></i>
                        <h5 class="mb-0 text-white fw-bold">{{ $patientName }}</h5>
                    </div>
                    @if($appointmentDate)
                    <span class="badge bg-white text-primary rounded-pill px-3">
                        {{ \Carbon\Carbon::parse($appointmentDate)->format('d/m/Y') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                @if($accessLink && isset($accessLink['download_link']))
                    <div class="video-container mb-3">
                        <video controls preload="metadata" controlsList="nodownload"
                               poster=""
                               style="width:100%;height:100%;background:#000;">
                            <source src="{{ $accessLink['download_link'] }}" type="video/mp4">
                            Tu navegador no soporta el reproductor de video.
                        </video>
                    </div>

                    {{-- Action buttons --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ $accessLink['download_link'] }}" target="_blank" class="btn btn-download">
                            <i class="bx bx-download me-1"></i>Descargar Video
                        </a>
                        <a href="{{ route('telemedicine.recordings') }}" class="btn btn-back">
                            <i class="bx bx-arrow-back me-1"></i>Volver a Grabaciones
                        </a>
                    </div>
                @else
                    <div class="no-video-msg">
                        <i class="bx bx-video-off"></i>
                        <h5 class="fw-semibold mb-2">Video no disponible</h5>
                        <p class="text-muted mb-3">
                            No se pudo obtener el enlace de reproducción. Puede que la grabación aún se esté procesando
                            o que haya expirado.
                        </p>
                        <a href="{{ route('telemedicine.recordings') }}" class="btn btn-back">
                            <i class="bx bx-arrow-back me-1"></i>Volver a Grabaciones
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Transcripción --}}
        <div class="card transcript-card mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #1a73e8, #0d47a1); border-radius: 14px 14px 0 0;">
                <div class="d-flex align-items-center">
                    <i class="bx bx-text text-white font-size-22 me-2"></i>
                    <h5 class="mb-0 text-white fw-bold">Transcripción</h5>
                </div>
            </div>
            <div class="card-body p-4">
                @if($transcript && !empty($transcript))
                    <div class="transcript-text">{{ is_array($transcript) ? json_encode($transcript, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $transcript }}</div>
                @else
                    <div class="text-center py-4">
                        <i class="bx bx-message-alt-detail font-size-48 text-muted opacity-50 d-block mb-2"></i>
                        <p class="text-muted mb-0">No hay transcripción disponible para esta grabación.</p>
                        <small class="text-muted">
                            La transcripción se genera automáticamente si la función está habilitada en Daily.co
                        </small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Columna derecha: Info de la grabación --}}
    <div class="col-lg-4">
        <div class="card info-card mb-4">
            <div class="card-header py-3" style="background: linear-gradient(135deg, #fd7e14, #e55a00); border-radius: 14px 14px 0 0;">
                <div class="d-flex align-items-center">
                    <i class="bx bx-info-circle text-white font-size-22 me-2"></i>
                    <h5 class="mb-0 text-white fw-bold">Detalles</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="info-item">
                    <div class="info-label">Paciente</div>
                    <div class="info-value">
                        <i class="bx bx-user text-primary me-1"></i>{{ $patientName }}
                    </div>
                </div>

                @if($doctorName)
                <div class="info-item">
                    <div class="info-label">Doctor</div>
                    <div class="info-value">
                        <i class="bx bx-user-circle text-success me-1"></i>{{ $doctorName }}
                    </div>
                </div>
                @endif

                @if($appointmentDate)
                <div class="info-item">
                    <div class="info-label">Fecha de Consulta</div>
                    <div class="info-value">
                        <i class="bx bx-calendar text-info me-1"></i>{{ \Carbon\Carbon::parse($appointmentDate)->format('d/m/Y') }}
                    </div>
                </div>
                @endif

                <div class="info-item">
                    <div class="info-label">Duración</div>
                    <div class="info-value">
                        @php
                            $durMin = floor(($recording['duration'] ?? 0) / 60);
                            $durSec = ($recording['duration'] ?? 0) % 60;
                        @endphp
                        <i class="bx bx-time text-warning me-1"></i>{{ $durMin }}:{{ str_pad($durSec, 2, '0', STR_PAD_LEFT) }} minutos
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Estado</div>
                    <div class="info-value">
                        @if(($recording['status'] ?? '') === 'finished')
                            <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                <i class="bx bx-check me-1"></i>Finalizada
                            </span>
                        @elseif(($recording['status'] ?? '') === 'in-progress')
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3">
                                <i class="bx bx-loader-alt me-1"></i>En proceso
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">
                                {{ $recording['status'] ?? 'Desconocido' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Fecha de Grabación</div>
                    <div class="info-value">
                        @if(isset($recording['start_ts']))
                            <i class="bx bx-video-recording text-danger me-1"></i>
                            {{ \Carbon\Carbon::createFromTimestamp($recording['start_ts'])->timezone('America/El_Salvador')->format('d/m/Y h:i A') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">ID de Sala</div>
                    <div class="info-value text-muted" style="font-size: 0.8rem; word-break: break-all;">
                        {{ $recording['room_name'] ?? '—' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">ID de Grabación</div>
                    <div class="info-value text-muted" style="font-size: 0.75rem; word-break: break-all;">
                        {{ $recording['id'] ?? '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
