@extends('layouts.master-layouts')
@section('title') Expediente: {{ $patient->first_name }} {{ $patient->last_name }} @endsection

@section('css')
<style>
/* ── Variables ── */
:root {
    --c-primary: #556ee6;
    --c-success: #34c38f;
    --c-warning: #f1b44c;
    --c-danger:  #f46a6a;
    --c-info:    #50a5f1;
}

/* ── Hero ── */
.patient-hero {
    background: linear-gradient(135deg, #556ee6 0%, #34469d 100%);
    border-radius: 16px;
    padding: 30px 32px;
    color: #fff;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.patient-hero::before {
    content: '';
    position: absolute;
    right: -60px; top: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}
.patient-hero::after {
    content: '';
    position: absolute;
    right: 60px; bottom: -80px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}
.hero-avatar {
    width: 90px; height: 90px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.5);
    object-fit: cover;
    flex-shrink: 0;
}
.hero-avatar-initials {
    width: 90px; height: 90px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.hero-badge {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 50px;
    padding: 4px 13px;
    font-size: 12px; font-weight: 600;
}

/* ── Stats cards ── */
.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 22px 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    display: flex; align-items: center; gap: 16px;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.1); }
.stat-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; flex-shrink: 0;
}
.stat-label { font-size: 12px; color: #adb5bd; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
.stat-value { font-size: 24px; font-weight: 700; color: #343a40; line-height: 1; }

/* ── Tabs ── */
.exp-tabs {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 6px;
    display: flex;
    gap: 4px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.exp-tabs .nav-link {
    border-radius: 8px;
    color: #6c757d;
    font-weight: 600;
    font-size: 13px;
    padding: 10px 16px;
    border: none;
    transition: all .2s;
    display: flex; align-items: center; gap: 7px;
}
.exp-tabs .nav-link.active {
    background: #556ee6;
    color: #fff;
    box-shadow: 0 4px 12px rgba(85,110,230,.35);
}
.exp-tabs .nav-link:not(.active):hover {
    background: #e9ecef;
    color: #495057;
}
.exp-tabs .badge-count {
    background: rgba(255,255,255,0.25);
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 20px;
    font-weight: 700;
}
.exp-tabs .nav-link:not(.active) .badge-count {
    background: #dee2e6;
    color: #495057;
}

/* ── Section headers ── */
.sec-hdr {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 700;
    padding: 12px 18px;
    border-radius: 8px 8px 0 0;
    color: #fff;
}

/* ── Info blocks (sidebar) ── */
.info-blk {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f2f5;
}
.info-blk:last-child { border-bottom: none; }
.info-blk .ib-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}
.info-blk .ib-lbl { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #adb5bd; font-weight: 600; line-height: 1; margin-bottom: 3px; }
.info-blk .ib-val { font-size: 13px; font-weight: 600; color: #343a40; }

/* ── Signos vitales ── */
.signo-chip {
    background: #fff;
    border-radius: 12px;
    padding: 16px 14px;
    border: 1px solid #e9ecef;
    text-align: center;
}
.signo-chip:hover { box-shadow: 0 4px 14px rgba(0,0,0,.08); }
.signo-chip .sc-icon { font-size: 26px; margin-bottom: 6px; display: block; }
.signo-chip .sc-val { font-size: 20px; font-weight: 700; line-height: 1; }
.signo-chip .sc-unit { font-size: 11px; color: #adb5bd; }
.signo-chip .sc-label { font-size: 12px; color: #6c757d; margin-top: 3px; }

/* ── Timeline citas ── */
.appt-item {
    display: flex; gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #f0f2f5;
}
.appt-item:last-child { border-bottom: none; }
.appt-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: #556ee6; flex-shrink: 0; margin-top: 6px;
}

/* ── Consultas cards ── */
.consult-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 14px;
    transition: box-shadow .2s;
}
.consult-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.consult-card-header {
    background: linear-gradient(90deg, #f8f9fa, #fff);
    padding: 12px 16px;
    display: flex; align-items: center; justify-content: between;
    gap: 10px;
    border-bottom: 1px solid #e9ecef;
}
.consult-card-body { padding: 14px 16px; }

/* ── Empty ── */
.empty-exp {
    text-align: center;
    padding: 40px 20px;
    color: #adb5bd;
}
.empty-exp i { font-size: 46px; display: block; margin-bottom: 10px; opacity: 0.5; }

/* ── Alergias highlight ── */
.alergy-box {
    background: #fff5f5;
    border: 1px solid #f5c6cb;
    border-left: 4px solid #f46a6a;
    border-radius: 8px;
    padding: 12px 14px;
    margin-top: 8px;
}
</style>
@endsection

@section('content')
@php
    $patientName = trim($patient->first_name . ' ' . $patient->last_name);
    $initials = strtoupper(mb_substr($patient->first_name, 0, 1) . mb_substr($patient->last_name, 0, 1));
    $photoUrl = $patient->photo
        ? URL::asset('storage/images/patients/' . $patient->photo)
        : null;
    $age = $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->age : null;
@endphp

{{-- Breadcrumb --}}
@component('components.breadcrumb')
    @slot('title') Expediente del Paciente @endslot
    @slot('li_1') Dashboard @endslot
    @slot('li_2') Pacientes @endslot
    @slot('li_3') {{ $patientName }} @endslot
@endcomponent

{{-- Actions --}}
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ url('patient') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Regresar
    </a>
    <a href="{{ url('patient/'.$patient->id.'/edit') }}" class="btn btn-warning btn-sm">
        <i class="bx bx-edit me-1"></i> Editar Paciente
    </a>
    <a href="{{ route('appointment.create') }}?patient_id={{ $patient->id }}" class="btn btn-primary btn-sm ms-auto">
        <i class="bx bx-calendar-plus me-1"></i> Nueva Cita
    </a>
    @if($role == 'doctor')
    <a href="{{ route('prescription.create') }}?patient_id={{ $patient->id }}" class="btn btn-success btn-sm">
        <i class="bx bx-notepad me-1"></i> Nueva Consulta
    </a>
    @endif
</div>

{{-- ─── HERO HEADER ─── --}}
<div class="patient-hero">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            @if($photoUrl)
                <img src="{{ $photoUrl }}" alt="{{ $patientName }}" class="hero-avatar">
            @else
                <div class="hero-avatar-initials">{{ $initials }}</div>
            @endif
            <div>
                <div class="d-flex gap-2 mb-2 flex-wrap">
                    <span class="hero-badge">Paciente #{{ $patient->id }}</span>
                    @if($patient->blood_group ?? $medical_Info?->b_group)
                    <span class="hero-badge"><i class="bx bx-donate-blood me-1"></i>{{ $patient->blood_group ?? $medical_Info?->b_group }}</span>
                    @endif
                    @if($patient->gender)
                    <span class="hero-badge">{{ $patient->gender }}</span>
                    @endif
                </div>
                <h2 class="mb-1 fw-bold text-white" style="font-size:24px; text-transform:uppercase;">{{ $patientName }}</h2>
                <p class="mb-0 opacity-75" style="font-size:13px;">
                    @if($age) <i class="bx bx-calendar me-1"></i>{{ $age }} años &nbsp;·&nbsp; @endif
                    @if($patient->birth_date) {{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }} &nbsp;·&nbsp; @endif
                    @if($patient->phone_primary) <i class="bx bx-phone me-1"></i>{{ $patient->phone_primary }} @endif
                </p>
            </div>
        </div>
        <div class="text-end text-white-75 d-none d-md-block">
            @if($patient->email)
            <p class="mb-1 opacity-75" style="font-size:12px;"><i class="bx bx-envelope me-1"></i>{{ $patient->email }}</p>
            @endif
            @if($patient->address)
            <p class="mb-0 opacity-75" style="font-size:12px;"><i class="bx bx-map me-1"></i>{{ Str::limit($patient->address, 50) }}</p>
            @endif
        </div>
    </div>
</div>

{{-- ─── STATS ─── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eef1fd;"><i class="bx bx-calendar text-primary" style="font-size:26px;"></i></div>
            <div>
                <div class="stat-label">Citas</div>
                <div class="stat-value">{{ $data['total_appointment'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#edfaf4;"><i class="bx bx-notepad text-success" style="font-size:26px;"></i></div>
            <div>
                <div class="stat-label">Consultas</div>
                <div class="stat-value">{{ $data['total_prescriptions'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff8ec;"><i class="bx bx-time-five text-warning" style="font-size:26px;"></i></div>
            <div>
                <div class="stat-label">Ctas. Pendientes</div>
                <div class="stat-value">{{ $data['pending_bill'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef0f0;"><i class="bx bx-dollar text-danger" style="font-size:26px;"></i></div>
            <div>
                <div class="stat-label">Total Facturado</div>
                <div class="stat-value" style="font-size:18px;">${{ number_format($data['revenue'], 2) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ─── MAIN LAYOUT ─── --}}
<div class="row g-4">

    {{-- ─── LEFT: TABS ─── --}}
    <div class="col-lg-8">

        {{-- Tab Nav --}}
        <ul class="exp-tabs nav" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#ep-signos" role="tab">
                    <i class="bx bx-heart-circle"></i> Signos Vitales
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#ep-consultas" role="tab">
                    <i class="bx bx-file-medical"></i> Consultas
                    <span class="badge-count">{{ $data['total_prescriptions'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#ep-citas" role="tab">
                    <i class="bx bx-calendar"></i> Citas
                    <span class="badge-count">{{ $data['total_appointment'] }}</span>
                </a>
            </li>
            @if($vaccineRecords->count())
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#ep-vacunas" role="tab">
                    <i class="fas fa-syringe"></i> Vacunas
                    <span class="badge-count">{{ $vaccineRecords->count() }}</span>
                </a>
            </li>
            @endif
            @if($role != 'receptionist')
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#ep-facturas" role="tab">
                    <i class="bx bx-receipt"></i> Facturas
                </a>
            </li>
            @endif
        </ul>

        <div class="tab-content">

            {{-- ── TAB: SIGNOS VITALES ── --}}
            <div class="tab-pane fade show active" id="ep-signos" role="tabpanel">
                @if($signos)
                @php
                    $pesoKg = $signos->peso ? round($signos->peso * 0.453592, 1) : null;
                    $imc = null; $imcClass = ''; $imcLabel = '';
                    if ($signos->peso && $signos->talla > 0) {
                        $imc = round($pesoKg / ($signos->talla * $signos->talla), 2);
                        if ($imc < 18.5)      { $imcClass = 'text-warning'; $imcLabel = 'Bajo peso'; }
                        elseif ($imc < 25)    { $imcClass = 'text-success'; $imcLabel = 'Normal'; }
                        elseif ($imc < 30)    { $imcClass = 'text-warning fw-bold'; $imcLabel = 'Sobrepeso'; }
                        else                  { $imcClass = 'text-danger fw-bold'; $imcLabel = 'Obesidad'; }
                    }
                    $paClass = 'text-success';
                    if ($signos->presion_arterial_sistolica >= 140 || $signos->presion_arterial_diastolica >= 90)
                        $paClass = 'text-danger fw-bold';
                    elseif ($signos->presion_arterial_sistolica >= 120 || $signos->presion_arterial_diastolica >= 80)
                        $paClass = 'text-warning fw-bold';
                    $spoClass = 'text-success';
                    if ($signos->spo < 90) $spoClass = 'text-danger fw-bold';
                    elseif ($signos->spo < 95) $spoClass = 'text-warning fw-bold';
                    function vr($v, $min, $max) {
                        if ($v === null) return '';
                        return ($v < $min || $v > $max) ? 'text-danger fw-bold' : 'text-success';
                    }
                @endphp
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="sec-hdr" style="background:linear-gradient(90deg,#34c38f,#1a9e72);">
                        <i class="bx bx-heart-circle"></i> Últimos Signos Vitales Registrados
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">⚖️</span>
                                    <div class="sc-val text-primary">{{ $signos->peso ?? '—' }}</div>
                                    <div class="sc-unit">lb{{ $pesoKg ? ' · '.$pesoKg.' kg' : '' }}</div>
                                    <div class="sc-label">Peso</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">📏</span>
                                    <div class="sc-val text-info">{{ $signos->talla ?? '—' }}</div>
                                    <div class="sc-unit">m</div>
                                    <div class="sc-label">Talla</div>
                                </div>
                            </div>
                            @if($imc)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">🏃</span>
                                    <div class="sc-val {{ $imcClass }}">{{ $imc }}</div>
                                    <div class="sc-unit">IMC</div>
                                    <div class="sc-label {{ $imcClass }}">{{ $imcLabel }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">🌡️</span>
                                    <div class="sc-val {{ vr($signos->temperatura, 36.1, 37.2) }}">{{ $signos->temperatura ?? '—' }}</div>
                                    <div class="sc-unit">°C</div>
                                    <div class="sc-label">Temperatura</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">❤️</span>
                                    <div class="sc-val {{ vr($signos->frec_cardiaca, 60, 100) }}">{{ $signos->frec_cardiaca ?? '—' }}</div>
                                    <div class="sc-unit">bpm</div>
                                    <div class="sc-label">Frec. Cardíaca</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">🫁</span>
                                    <div class="sc-val {{ vr($signos->frec_respiratoria, 12, 20) }}">{{ $signos->frec_respiratoria ?? '—' }}</div>
                                    <div class="sc-unit">rpm</div>
                                    <div class="sc-label">Frec. Respiratoria</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">🩸</span>
                                    <div class="sc-val {{ $paClass }}">{{ $signos->presion_arterial_sistolica ?? '—' }}/{{ $signos->presion_arterial_diastolica ?? '—' }}</div>
                                    <div class="sc-unit">mmHg</div>
                                    <div class="sc-label">Presión Arterial</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-chip">
                                    <span class="sc-icon">💧</span>
                                    <div class="sc-val {{ $spoClass }}">{{ $signos->spo ?? '—' }}</div>
                                    <div class="sc-unit">%</div>
                                    <div class="sc-label">SpO₂</div>
                                </div>
                            </div>
                        </div>
                        @if($signos->examen || $signos->observaciones_adicionales)
                        <hr class="my-4">
                        @if($signos->examen)
                        <h6 class="fw-semibold mb-2"><i class="bx bx-search-alt text-primary me-1"></i> Examen Físico</h6>
                        <p class="mb-3" style="white-space:pre-wrap; color:#495057;">{{ $signos->examen }}</p>
                        @endif
                        @if($signos->observaciones_adicionales)
                        <h6 class="fw-semibold mb-2"><i class="bx bx-note text-warning me-1"></i> Observaciones</h6>
                        <p class="mb-0" style="white-space:pre-wrap; color:#495057;">{{ $signos->observaciones_adicionales }}</p>
                        @endif
                        @endif
                    </div>
                </div>
                @else
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-body">
                        <div class="empty-exp"><i class="bx bx-heart"></i><p class="mb-0">Sin signos vitales registrados aún.</p></div>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── TAB: CONSULTAS ── --}}
            <div class="tab-pane fade" id="ep-consultas" role="tabpanel">
                @if($prescriptions->count())
                    @foreach($prescriptions as $p)
                    @php
                        $pDoctor = optional(optional($p->doctor)->user);
                        $pDoctorName = $pDoctor->first_name ? trim($pDoctor->first_name . ' ' . $pDoctor->last_name) : '—';
                    @endphp
                    <div class="consult-card">
                        <div class="consult-card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:38px;height:38px;background:#eef1fd;border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bx bx-file-medical text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size:14px;">Consulta #{{ $p->id }}</div>
                                    <div class="text-muted" style="font-size:12px;">
                                        <i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}
                                        &nbsp;·&nbsp; Dr. {{ $pDoctorName }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ url('prescription/'.$p->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show me-1"></i> Ver
                                </a>
                                @if($role == 'doctor')
                                <a href="{{ url('prescription/'.$p->id.'/edit') }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bx bx-edit"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="consult-card-body">
                            <div class="row g-3">
                                @if($p->consulta_por)
                                <div class="col-md-6">
                                    <p class="text-uppercase fw-semibold mb-1" style="font-size:10px;color:#adb5bd;letter-spacing:.6px;"><i class="bx bx-message-rounded-dots me-1"></i> Motivo</p>
                                    <p class="mb-0 text-dark" style="font-size:13px;">{{ Str::limit($p->consulta_por, 120) }}</p>
                                </div>
                                @endif
                                @if($p->evaluacion && $p->evaluacion->diagnostico)
                                <div class="col-md-6">
                                    <p class="text-uppercase fw-semibold mb-1" style="font-size:10px;color:#adb5bd;letter-spacing:.6px;"><i class="bx bx-file me-1"></i> Diagnóstico</p>
                                    <p class="mb-0 text-dark" style="font-size:13px;">{{ Str::limit($p->evaluacion->diagnostico, 120) }}</p>
                                </div>
                                @elseif($p->diagnosis)
                                <div class="col-md-6">
                                    <p class="text-uppercase fw-semibold mb-1" style="font-size:10px;color:#adb5bd;letter-spacing:.6px;"><i class="bx bx-file me-1"></i> Diagnóstico</p>
                                    <p class="mb-0 text-dark" style="font-size:13px;">{{ Str::limit($p->diagnosis, 120) }}</p>
                                </div>
                                @endif
                            </div>
                            @php $hasExtras = ($p->vacunas && $p->vacunas->count()) || ($p->archivos && $p->archivos->count()); @endphp
                            @if($hasExtras)
                            <div class="d-flex gap-2 mt-2 flex-wrap">
                                @if($p->vacunas && $p->vacunas->count())
                                <span class="badge bg-warning-subtle text-warning"><i class="fas fa-syringe me-1"></i> {{ $p->vacunas->count() }} vacuna(s)</span>
                                @endif
                                @if($p->archivos && $p->archivos->count())
                                <span class="badge bg-secondary-subtle text-secondary"><i class="bx bx-paperclip me-1"></i> {{ $p->archivos->count() }} archivo(s)</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    {{-- Paginación --}}
                    @if($prescriptions->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Mostrando {{ $prescriptions->firstItem() }}-{{ $prescriptions->lastItem() }} de {{ $prescriptions->total() }}</small>
                        {{ $prescriptions->links() }}
                    </div>
                    @endif
                @else
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-body">
                        <div class="empty-exp"><i class="bx bx-notepad"></i><p class="mb-0">Sin consultas registradas.</p></div>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── TAB: CITAS ── --}}
            <div class="tab-pane fade" id="ep-citas" role="tabpanel">
                @if($appointments->count())
                <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
                    <div class="sec-hdr" style="background:linear-gradient(90deg,#556ee6,#3f52c4);">
                        <i class="bx bx-calendar"></i> Historial de Citas
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Doctor</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appt)
                                    <tr>
                                        <td class="fw-semibold text-muted">{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d/m/Y') }}</td>
                                        <td class="text-muted">{{ optional($appt->timeSlot)->from ?? '—' }}{{ optional($appt->timeSlot)->to ? ' - '.optional($appt->timeSlot)->to : '' }}</td>
                                        <td>{{ optional(optional($appt->doctor)->user)->first_name ?? 'N/A' }} {{ optional(optional($appt->doctor)->user)->last_name ?? '' }}</td>
                                        <td>
                                            @php $status = $appt->status ?? 'pending'; @endphp
                                            @if($status == 'completed')
                                                <span class="badge bg-success-subtle text-success">Completada</span>
                                            @elseif($status == 'cancelled')
                                                <span class="badge bg-danger-subtle text-danger">Cancelada</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if($appointments->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">{{ $appointments->firstItem() }}-{{ $appointments->lastItem() }} de {{ $appointments->total() }}</small>
                    {{ $appointments->links() }}
                </div>
                @endif
                @else
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-body">
                        <div class="empty-exp"><i class="bx bx-calendar-x"></i><p class="mb-0">Sin citas registradas.</p></div>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── TAB: VACUNAS ── --}}
            @if($vaccineRecords->count())
            <div class="tab-pane fade" id="ep-vacunas" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
                    <div class="sec-hdr" style="background:linear-gradient(90deg,#fd7e14,#d6630d);">
                        <i class="fas fa-syringe"></i> Registro de Vacunación
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Vacuna</th>
                                        <th>Dosis</th>
                                        <th>Fecha Admin.</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vaccineRecords as $vr)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ optional($vr->vaccine)->name ?? $vr->vaccine_name ?? '—' }}</td>
                                        <td class="text-muted">{{ $vr->dose ?? '—' }}</td>
                                        <td>{{ $vr->administered_at ? \Carbon\Carbon::parse($vr->administered_at)->format('d/m/Y') : '—' }}</td>
                                        <td>
                                            @if(($vr->status ?? '') == 'completed')
                                                <span class="badge bg-success-subtle text-success">Aplicada</span>
                                            @elseif(($vr->status ?? '') == 'pending')
                                                <span class="badge bg-warning-subtle text-warning">Pendiente</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">{{ $vr->status ?? '—' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── TAB: FACTURAS ── --}}
            @if($role != 'receptionist')
            <div class="tab-pane fade" id="ep-facturas" role="tabpanel">
                @if($invoices->count())
                <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
                    <div class="sec-hdr" style="background:linear-gradient(90deg,#6c757d,#495057);">
                        <i class="bx bx-receipt"></i> Facturas
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr><th>#</th><th>Fecha</th><th>Estado</th><th>Opción</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $inv)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($inv->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($inv->payment_status == 'Paid')
                                                <span class="badge bg-success-subtle text-success">Pagado</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Pendiente</span>
                                            @endif
                                        </td>
                                        <td><a href="{{ url('invoice/'.$inv->id) }}" class="btn btn-sm btn-outline-primary"><i class="bx bx-show"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if($invoices->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">{{ $invoices->firstItem() }}-{{ $invoices->lastItem() }} de {{ $invoices->total() }}</small>
                    {{ $invoices->links() }}
                </div>
                @endif
                @else
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-body">
                        <div class="empty-exp"><i class="bx bx-receipt"></i><p class="mb-0">Sin facturas registradas.</p></div>
                    </div>
                </div>
                @endif
            </div>
            @endif

        </div>{{-- end tab-content --}}
    </div>{{-- end col-lg-8 --}}

    {{-- ─── RIGHT SIDEBAR ─── --}}
    <div class="col-lg-4">

        {{-- Datos demográficos --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header bg-primary text-white" style="border-radius:12px 12px 0 0; padding:14px 18px;">
                <h6 class="mb-0 text-white fw-bold"><i class="bx bx-user me-2"></i>Datos Personales</h6>
            </div>
            <div class="card-body p-3">
                @if($patient->dui)
                <div class="info-blk">
                    <div class="ib-icon bg-primary-subtle"><i class="bx bx-id-card text-primary"></i></div>
                    <div><div class="ib-lbl">DUI / Identificación</div><div class="ib-val">{{ $patient->dui }}</div></div>
                </div>
                @endif
                @if($patient->birth_date)
                <div class="info-blk">
                    <div class="ib-icon bg-info-subtle"><i class="bx bx-calendar text-info"></i></div>
                    <div><div class="ib-lbl">Nacimiento / Edad</div><div class="ib-val">{{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }} <span class="text-muted fw-normal">({{ $age }} años)</span></div></div>
                </div>
                @endif
                @if($patient->gender)
                <div class="info-blk">
                    <div class="ib-icon bg-success-subtle"><i class="bx bx-male-female text-success"></i></div>
                    <div><div class="ib-lbl">Género / Estado Civil</div><div class="ib-val">{{ $patient->gender }}@if($patient->marital_status) · {{ $patient->marital_status }}@endif</div></div>
                </div>
                @endif
                @if($patient->phone_primary)
                <div class="info-blk">
                    <div class="ib-icon bg-success-subtle"><i class="bx bx-phone text-success"></i></div>
                    <div><div class="ib-lbl">Teléfono Principal</div><div class="ib-val">{{ $patient->phone_primary }}</div></div>
                </div>
                @endif
                @if($patient->phone_secondary)
                <div class="info-blk">
                    <div class="ib-icon bg-secondary-subtle"><i class="bx bx-phone-call text-secondary"></i></div>
                    <div><div class="ib-lbl">Teléfono Secundario</div><div class="ib-val">{{ $patient->phone_secondary }}</div></div>
                </div>
                @endif
                @if($patient->email)
                <div class="info-blk">
                    <div class="ib-icon bg-info-subtle"><i class="bx bx-envelope text-info"></i></div>
                    <div><div class="ib-lbl">Email</div><div class="ib-val" style="word-break:break-all;font-size:12px;">{{ $patient->email }}</div></div>
                </div>
                @endif
                @if($patient->address)
                <div class="info-blk">
                    <div class="ib-icon bg-warning-subtle"><i class="bx bx-map text-warning"></i></div>
                    <div><div class="ib-lbl">Dirección</div><div class="ib-val" style="font-size:12px;">{{ $patient->address }}</div></div>
                </div>
                @endif
                @if($patient->occupation)
                <div class="info-blk">
                    <div class="ib-icon bg-primary-subtle"><i class="bx bx-briefcase text-primary"></i></div>
                    <div><div class="ib-lbl">Ocupación / Trabajo</div><div class="ib-val">{{ $patient->occupation }}@if($patient->workplace) · <span class="text-muted fw-normal">{{ $patient->workplace }}</span>@endif</div></div>
                </div>
                @endif
                @if($patient->referred_by)
                <div class="info-blk">
                    <div class="ib-icon bg-secondary-subtle"><i class="bx bx-user-plus text-secondary"></i></div>
                    <div><div class="ib-lbl">Referido por</div><div class="ib-val">{{ $patient->referred_by }}</div></div>
                </div>
                @endif
            </div>
        </div>

        {{-- Contacto de emergencia --}}
        @if($patient->emergency_contact_name)
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header" style="background:linear-gradient(90deg,#f1b44c,#d49830); border-radius:12px 12px 0 0; padding:14px 18px;">
                <h6 class="mb-0 text-white fw-bold"><i class="bx bx-first-aid me-2"></i>Contacto de Emergencia</h6>
            </div>
            <div class="card-body p-3">
                <div class="info-blk">
                    <div class="ib-icon bg-warning-subtle"><i class="bx bx-user-pin text-warning"></i></div>
                    <div><div class="ib-lbl">Nombre</div><div class="ib-val">{{ $patient->emergency_contact_name }}</div></div>
                </div>
                @if($patient->emergency_contact_phone)
                <div class="info-blk">
                    <div class="ib-icon bg-warning-subtle"><i class="bx bx-phone text-warning"></i></div>
                    <div><div class="ib-lbl">Teléfono</div><div class="ib-val">{{ $patient->emergency_contact_phone }}</div></div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Antecedentes y Alergias --}}
        @if($patient->pathological_history || $patient->non_pathological_history || $patient->medications_allergies)
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header" style="background:linear-gradient(90deg,#dc3545,#a71d2a); border-radius:12px 12px 0 0; padding:14px 18px;">
                <h6 class="mb-0 text-white fw-bold"><i class="bx bx-history me-2"></i>Antecedentes Clínicos</h6>
            </div>
            <div class="card-body p-3">
                @if($patient->pathological_history)
                <div class="mb-3">
                    <p class="text-uppercase fw-semibold mb-1" style="font-size:11px;color:#adb5bd;letter-spacing:.6px;">Patológicos</p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;">{{ $patient->pathological_history }}</p>
                </div>
                @endif
                @if($patient->non_pathological_history)
                <div class="mb-3">
                    <p class="text-uppercase fw-semibold mb-1" style="font-size:11px;color:#adb5bd;letter-spacing:.6px;">Familiares / No Patológicos</p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;">{{ $patient->non_pathological_history }}</p>
                </div>
                @endif
                @if($patient->medications_allergies)
                <div>
                    <p class="text-danger text-uppercase fw-semibold mb-1" style="font-size:11px;letter-spacing:.6px;"><i class="bx bx-error-circle me-1"></i>Alergias</p>
                    <div class="alergy-box">
                        <p class="mb-0 text-danger fw-semibold" style="font-size:13px; white-space:pre-wrap;">{{ $patient->medications_allergies }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Accesos rápidos --}}
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-dark text-white" style="border-radius:12px 12px 0 0; padding:14px 18px;">
                <h6 class="mb-0 text-white fw-bold"><i class="bx bx-zap me-2"></i>Acciones Rápidas</h6>
            </div>
            <div class="card-body p-3 d-grid gap-2">
                <a href="{{ route('appointment.create') }}?patient_id={{ $patient->id }}" class="btn btn-outline-primary btn-sm">
                    <i class="bx bx-calendar-plus me-1"></i> Agendar Nueva Cita
                </a>
                @if($role == 'doctor')
                <a href="{{ route('prescription.create') }}?patient_id={{ $patient->id }}" class="btn btn-outline-success btn-sm">
                    <i class="bx bx-notepad me-1"></i> Nueva Consulta
                </a>
                @endif
                <a href="{{ url('patient/'.$patient->id.'/edit') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bx bx-edit me-1"></i> Editar Expediente
                </a>
            </div>
        </div>

    </div>{{-- end col-lg-4 --}}

</div>{{-- end row --}}
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
@endsection
