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
                        <h3 class="mb-0 fw-bold" style="color: #4f46e5;">{{ $totalRecordings }}</h3>
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
        <span class="badge bg-white text-primary rounded-pill px-3" style="font-size: 0.85rem;">
            {{ $totalRecordings }} grabaciones
        </span>
    </div>

    <div class="card-body p-0">
        @if(count($recordings) > 0)
            @foreach($recordings as $index => $rec)
            @php
                $durationMin = round(($rec['duration'] ?? 0) / 60);
                $durationSec = ($rec['duration'] ?? 0) % 60;
                $createdAt = $rec['created_at'] ? \Carbon\Carbon::createFromTimestamp($rec['created_at'])->timezone('America/El_Salvador') : null;
                $initials = collect(explode(' ', $rec['patient_name']))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');
            @endphp
            <div class="recording-item">
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
                    <div class="col-md-3 text-end">
                        <a href="{{ route('telemedicine.recording.play', $rec['id']) }}"
                           class="play-btn btn btn-sm">
                            <i class="bx bx-play me-1"></i>Reproducir
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="bx bx-video-off d-block"></i>
                <h5 class="text-muted fw-semibold mb-2">No hay grabaciones disponibles</h5>
                <p class="text-muted mb-0">Las grabaciones aparecerán aquí cuando se realicen teleconsultas con la grabación activada.</p>
            </div>
        @endif
    </div>
</div>
@endsection
