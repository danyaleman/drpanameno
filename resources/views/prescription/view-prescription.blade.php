@extends('layouts.master-layouts')
@section('title') Detalle de Consulta #{{ $prescription->id }} @endsection

@section('css')
<style>
    /* ── Hero Header ── */
    .consult-hero {
        background: linear-gradient(135deg, #556ee6 0%, #34469d 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .consult-hero::after {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .consult-hero .badge-consult {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .consult-hero .avatar-hero {
        width: 72px; height: 72px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.4);
        background: rgba(255,255,255,0.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; font-weight: 700; color: #fff;
        flex-shrink: 0;
    }
    /* ── Tabs ── */
    .view-tabs {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 6px;
        display: flex;
        gap: 4px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .view-tabs .nav-link {
        border-radius: 8px;
        color: #6c757d;
        font-weight: 600;
        font-size: 13px;
        padding: 10px 16px;
        border: none;
        transition: all .2s;
        display: flex; align-items: center; gap: 7px;
    }
    .view-tabs .nav-link.active {
        background: #556ee6;
        color: #fff;
        box-shadow: 0 4px 12px rgba(85,110,230,.35);
    }
    .view-tabs .nav-link:not(.active):hover {
        background: #e9ecef;
        color: #495057;
    }
    /* ── Info cards ── */
    .info-block {
        display: flex; align-items: flex-start; gap: 14px;
        margin-bottom: 20px;
    }
    .info-block .icon-box {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .info-block .label { font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: #adb5bd; font-weight: 600; margin-bottom: 3px; }
    .info-block .value { font-size: 14px; color: #343a40; font-weight: 600; }
    /* ── Signos vitales cards ── */
    .signo-card {
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        border: 1px solid #e9ecef;
        text-align: center;
        transition: box-shadow .2s;
    }
    .signo-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .signo-card .signo-icon { font-size: 28px; margin-bottom: 8px; }
    .signo-card .signo-val { font-size: 22px; font-weight: 700; line-height: 1; }
    .signo-card .signo-unit { font-size: 11px; color: #adb5bd; }
    .signo-card .signo-label { font-size: 12px; color: #6c757d; margin-top: 4px; }
    /* ── Section headers ── */
    .section-header {
        background: linear-gradient(90deg, #556ee6 0%, #6f7fe9 100%);
        color: #fff;
        border-radius: 8px 8px 0 0;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        display: flex; align-items: center; gap: 8px;
    }
    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 32px 16px;
        color: #adb5bd;
    }
    .empty-state i { font-size: 40px; display: block; margin-bottom: 8px; }
    /* ── Print ── */
    @media print {
        .d-print-none { display: none !important; }
        .consult-hero { background: #556ee6 !important; -webkit-print-color-adjust: exact; }
        .view-tabs { display: none !important; }
        .tab-pane { display: block !important; opacity: 1 !important; }
    }
</style>
@endsection

@section('content')
@php
    if (!function_exists('rango')) {
        function rango($valor, $min, $max) {
            if ($valor === null) return '';
            if ($valor < $min || $valor > $max) return 'text-danger fw-bold';
            return 'text-success';
        }
    }
    $patient   = $user_details->patient;
    $doctor    = optional(optional($user_details->appointment)->doctor)->user;
    $appt      = $user_details->appointment;
    $doctorName = $doctor ? trim($doctor->first_name . ' ' . $doctor->last_name) : '—';
    $patientName = $patient ? trim($patient->first_name . ' ' . $patient->last_name) : '—';
    $initials  = strtoupper(mb_substr($patientName, 0, 1) . (strpos($patientName, ' ') !== false ? mb_substr(strstr($patientName, ' '), 1, 1) : ''));
@endphp

{{-- Breadcrumb --}}
@component('components.breadcrumb')
    @slot('title') Detalle de Consulta @endslot
    @slot('li_1') Dashboard @endslot
    @slot('li_2') Listado de Consultas @endslot
    @slot('li_3') Consulta #{{ $prescription->id }} @endslot
@endcomponent

{{-- Action buttons --}}
<div class="d-flex gap-2 mb-4 d-print-none flex-wrap">
    <a href="{{ url('prescription') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bx bx-arrow-back me-1"></i> Regresar
    </a>
    @if($role != 'receptionist')
    <a href="{{ url('prescription/'.$prescription->id.'/edit') }}" class="btn btn-warning btn-sm">
        <i class="bx bx-edit me-1"></i> Editar Consulta
    </a>
    @endif
    <a href="javascript:window.print()" class="btn btn-success btn-sm ms-auto">
        <i class="bx bx-printer me-1"></i> Imprimir
    </a>
</div>

{{-- ─── HERO HEADER ─── --}}
<div class="consult-hero d-print-block">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-hero">{{ $initials }}</div>
            <div>
                <span class="badge-consult d-inline-block mb-2">Consulta #{{ $prescription->id }}</span>
                <h3 class="mb-1 fw-bold text-white" style="font-size:22px;">{{ $patientName }}</h3>
                <p class="mb-0 opacity-75" style="font-size:13px;">
                    <i class="bx bx-calendar me-1"></i>
                    {{ $prescription->created_at ? \Carbon\Carbon::parse($prescription->created_at)->format('d/m/Y H:i') : '—' }}
                    @if($appt && $appt->appointment_date)
                        &nbsp;·&nbsp; Cita: {{ $appt->appointment_date }}
                    @endif
                </p>
            </div>
        </div>
        <div class="text-end text-white-75">
            <p class="mb-1 opacity-75" style="font-size:12px;">Atendido por</p>
            <p class="mb-0 fw-semibold" style="font-size:15px;"><i class="bx bx-plus-medical me-1"></i> Dr. {{ $doctorName }}</p>
            @if($doctor)
            <p class="mb-0 opacity-75" style="font-size:12px;">{{ $doctor->email }}</p>
            @endif
        </div>
    </div>
</div>

{{-- ─── MAIN LAYOUT ─── --}}
<div class="row g-4">

    {{-- ─── LEFT: TABS ─── --}}
    <div class="col-lg-8">

        {{-- Tab Nav --}}
        <ul class="view-tabs nav d-print-none" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#vt-consulta" role="tab">
                    <i class="bx bx-message-rounded-dots"></i> Consulta
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#vt-signos" role="tab">
                    <i class="bx bx-heart-circle"></i> Signos Vitales
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#vt-evaluacion" role="tab">
                    <i class="bx bx-file-medical"></i> Evaluación y Receta
                </a>
            </li>
            @if($medicines->count())
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#vt-medicamentos" role="tab">
                    <i class="bx bx-capsule"></i> Medicamentos
                </a>
            </li>
            @endif
            @if($vacunas->count())
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#vt-vacunas" role="tab">
                    <i class="fas fa-syringe"></i> Vacunas
                </a>
            </li>
            @endif
            @if($user_details->archivos->count())
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#vt-archivos" role="tab">
                    <i class="bx bx-images"></i> Archivos
                </a>
            </li>
            @endif
        </ul>

        <div class="tab-content">

            {{-- ── TAB: CONSULTA POR ── --}}
            <div class="tab-pane fade show active" id="vt-consulta" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="section-header">
                        <i class="bx bx-message-rounded-detail"></i> Motivo de Consulta
                    </div>
                    <div class="card-body p-4">
                        @if($prescription->consulta_por)
                            <p class="mb-0" style="font-size:15px; line-height:1.7; color:#343a40; white-space:pre-wrap;">{{ $prescription->consulta_por }}</p>
                        @else
                            <div class="empty-state"><i class="bx bx-message-rounded-x opacity-50"></i><p class="mb-0 small">Sin motivo registrado</p></div>
                        @endif
                    </div>
                </div>

                {{-- Estudios de laboratorio --}}
                @if($evaluacion && $evaluacion->estudios_laboratorios)
                <div class="card border-0 shadow-sm mt-3" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #17a2b8, #0d7c94);">
                        <i class="bx bx-test-tube"></i> Estudios de Laboratorio
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0" style="font-size:15px; line-height:1.7; color:#343a40; white-space:pre-wrap;">{{ $evaluacion->estudios_laboratorios }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── TAB: SIGNOS VITALES ── --}}
            <div class="tab-pane fade" id="vt-signos" role="tabpanel">
                @if($signos)
                @php
                    $pesoKg = $signos->peso ? round($signos->peso * 0.453592, 1) : null;
                    $imc = null; $imcClass = ''; $imcLabel = '';
                    if ($signos->peso && $signos->talla > 0) {
                        $imc = round($pesoKg / ($signos->talla * $signos->talla), 2);
                        if ($imc < 18.5)       { $imcClass = 'text-warning'; $imcLabel = 'Bajo peso'; }
                        elseif ($imc < 25)     { $imcClass = 'text-success'; $imcLabel = 'Normal'; }
                        elseif ($imc < 30)     { $imcClass = 'text-warning fw-bold'; $imcLabel = 'Sobrepeso'; }
                        else                   { $imcClass = 'text-danger fw-bold'; $imcLabel = 'Obesidad'; }
                    }
                    $paClass = 'text-success';
                    if ($signos->presion_arterial_sistolica >= 140 || $signos->presion_arterial_diastolica >= 90)
                        $paClass = 'text-danger fw-bold';
                    elseif ($signos->presion_arterial_sistolica >= 120 || $signos->presion_arterial_diastolica >= 80)
                        $paClass = 'text-warning fw-bold';
                    $spoClass = 'text-success';
                    if ($signos->spo < 90) $spoClass = 'text-danger fw-bold';
                    elseif ($signos->spo < 95) $spoClass = 'text-warning fw-bold';
                @endphp
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #28a745, #1a7a31);">
                        <i class="bx bx-heart-circle"></i> Signos Vitales
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon text-primary">⚖️</div>
                                    <div class="signo-val text-primary">{{ $signos->peso ?? '—' }}</div>
                                    <div class="signo-unit">lb{{ $pesoKg ? ' · '.$pesoKg.' kg' : '' }}</div>
                                    <div class="signo-label">Peso</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon text-info">📏</div>
                                    <div class="signo-val text-info">{{ $signos->talla ?? '—' }}</div>
                                    <div class="signo-unit">m</div>
                                    <div class="signo-label">Talla</div>
                                </div>
                            </div>
                            @if($imc)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon">🏃</div>
                                    <div class="signo-val {{ $imcClass }}">{{ $imc }}</div>
                                    <div class="signo-unit">IMC</div>
                                    <div class="signo-label {{ $imcClass }}">{{ $imcLabel }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon text-danger">🌡️</div>
                                    <div class="signo-val {{ rango($signos->temperatura, 36.1, 37.2) }}">{{ $signos->temperatura ?? '—' }}</div>
                                    <div class="signo-unit">°C</div>
                                    <div class="signo-label">Temperatura</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon text-danger">❤️</div>
                                    <div class="signo-val {{ rango($signos->frec_cardiaca, 60, 100) }}">{{ $signos->frec_cardiaca ?? '—' }}</div>
                                    <div class="signo-unit">bpm</div>
                                    <div class="signo-label">Frec. Cardíaca</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon text-info">🫁</div>
                                    <div class="signo-val {{ rango($signos->frec_respiratoria, 12, 20) }}">{{ $signos->frec_respiratoria ?? '—' }}</div>
                                    <div class="signo-unit">rpm</div>
                                    <div class="signo-label">Frec. Respiratoria</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon">🩸</div>
                                    <div class="signo-val {{ $paClass }}">{{ $signos->presion_arterial_sistolica ?? '—' }}/{{ $signos->presion_arterial_diastolica ?? '—' }}</div>
                                    <div class="signo-unit">mmHg</div>
                                    <div class="signo-label">Presión Arterial</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="signo-card">
                                    <div class="signo-icon text-primary">💧</div>
                                    <div class="signo-val {{ $spoClass }}">{{ $signos->spo ?? '—' }}</div>
                                    <div class="signo-unit">%</div>
                                    <div class="signo-label">SpO₂</div>
                                </div>
                            </div>
                        </div>

                        @if($signos->examen)
                        <hr class="my-4">
                        <h6 class="fw-semibold mb-2"><i class="bx bx-search-alt text-primary me-1"></i> Examen Físico</h6>
                        <p class="mb-0" style="white-space:pre-wrap; color:#495057;">{{ $signos->examen }}</p>
                        @endif

                        @if($signos->observaciones_adicionales)
                        <hr class="my-4">
                        <h6 class="fw-semibold mb-2"><i class="bx bx-note text-warning me-1"></i> Observaciones Adicionales</h6>
                        <p class="mb-0" style="white-space:pre-wrap; color:#495057;">{{ $signos->observaciones_adicionales }}</p>
                        @endif
                    </div>
                </div>
                @else
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="card-body">
                        <div class="empty-state"><i class="bx bx-heart opacity-50"></i><p class="mb-0 small">Sin signos vitales registrados</p></div>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── TAB: EVALUACIÓN Y RECETA ── --}}
            <div class="tab-pane fade" id="vt-evaluacion" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #6f42c1, #5a30a8);">
                        <i class="bx bx-file-medical"></i> Diagnóstico y Evaluación
                    </div>
                    <div class="card-body p-4">
                        @if($evaluacion && $evaluacion->diagnostico)
                            <p class="mb-0" style="font-size:15px; line-height:1.7; color:#343a40; white-space:pre-wrap;">{{ $evaluacion->diagnostico }}</p>
                        @elseif($prescription->diagnosis)
                            <p class="mb-0" style="font-size:15px; line-height:1.7; color:#343a40; white-space:pre-wrap;">{{ $prescription->diagnosis }}</p>
                        @else
                            <div class="empty-state"><i class="bx bx-file-blank opacity-50"></i><p class="mb-0 small">Sin diagnóstico registrado</p></div>
                        @endif
                    </div>
                </div>

                @if($evaluacion && $evaluacion->medicamentos)
                <div class="card border-0 shadow-sm mt-3" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #20c997, #0fa07b);">
                        <i class="bx bx-clipboard"></i> Tratamiento / Receta Médica
                    </div>
                    <div class="card-body p-4">
                        <div style="background:#f8f9fa; border-radius:8px; padding:16px; border-left:4px solid #20c997;">
                            <p class="mb-0" style="font-size:14px; line-height:1.8; color:#343a40; white-space:pre-wrap;">{{ $evaluacion->medicamentos }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($test_reports->count())
                <div class="card border-0 shadow-sm mt-3" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #fd7e14, #d6630d);">
                        <i class="bx bx-test-tube"></i> Exámenes / Laboratorio
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th>Nombre</th>
                                    <th>Notas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($test_reports as $item)
                                <tr>
                                    <td class="fw-semibold text-muted">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $item->name }}</td>
                                    <td class="text-muted">{{ $item->notes ?: '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── TAB: MEDICAMENTOS ── --}}
            @if($medicines->count())
            <div class="tab-pane fade" id="vt-medicamentos" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #e83e8c, #ba2979);">
                        <i class="bx bx-capsule"></i> Medicamentos Recetados
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th>Medicamento</th>
                                    <th>Indicaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicines as $item)
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary fw-semibold">{{ $loop->iteration }}</span></td>
                                    <td class="fw-semibold">{{ $item->name }}</td>
                                    <td class="text-muted" style="white-space:pre-wrap;">{{ $item->notes ?: '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── TAB: VACUNAS ── --}}
            @if($vacunas->count())
            <div class="tab-pane fade" id="vt-vacunas" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #fd7e14, #d6630d);">
                        <i class="fas fa-syringe"></i> Vacunas Aplicadas
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr><th style="width:50px;">#</th><th>Tipo / Vacuna</th><th>Dosis</th></tr>
                            </thead>
                            <tbody>
                                @foreach($vacunas as $v)
                                <tr>
                                    <td><span class="badge bg-warning-subtle text-warning fw-semibold">{{ $loop->iteration }}</span></td>
                                    <td class="fw-semibold">{{ $v->tipo }}</td>
                                    <td class="text-muted">{{ $v->dosis ?: '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── TAB: ARCHIVOS ── --}}
            @if($user_details->archivos->count())
            <div class="tab-pane fade" id="vt-archivos" role="tabpanel">
                <div class="card border-0 shadow-sm" style="border-radius:12px;">
                    <div class="section-header" style="background: linear-gradient(90deg, #6c757d, #495057);">
                        <i class="bx bx-folder-open"></i> Archivos Clínicos
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($user_details->archivos as $file)
                            @php
                                $ext = strtolower(pathinfo($file->url_file, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                $isPdf = $ext === 'pdf';
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card border shadow-sm h-100" style="border-radius:10px; overflow:hidden;">
                                    @if($isImage)
                                    <img src="{{ asset('storage/'.$file->url_file) }}" alt="Archivo" style="width:100%;height:160px;object-fit:cover;">
                                    @else
                                    <div class="d-flex align-items-center justify-content-center" style="height:100px; background:#f0f2f5;">
                                        <i class="{{ $isPdf ? 'bx bxs-file-pdf text-danger' : 'bx bx-file text-secondary' }}" style="font-size:48px;"></i>
                                    </div>
                                    @endif
                                    <div class="p-3">
                                        @if($file->observaciones)
                                        <p class="mb-2 small text-muted">{{ $file->observaciones }}</p>
                                        @endif
                                        <a href="{{ asset('storage/'.$file->url_file) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bx bx-download me-1"></i> Ver / Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>{{-- end tab-content --}}
    </div>{{-- end col-lg-8 --}}

    {{-- ─── RIGHT SIDEBAR ─── --}}
    <div class="col-lg-4">

        {{-- Datos del doctor --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header bg-primary text-white" style="border-radius:12px 12px 0 0; padding:14px 20px;">
                <h6 class="mb-0 text-white fw-bold"><i class="bx bx-plus-medical me-2"></i>Médico Tratante</h6>
            </div>
            <div class="card-body p-4">
                <div class="info-block">
                    <div class="icon-box bg-primary-subtle"><i class="bx bx-user-pin text-primary"></i></div>
                    <div>
                        <div class="label">Dr. / Nombre</div>
                        <div class="value">{{ $doctorName }}</div>
                    </div>
                </div>
                @if($doctor && $doctor->mobile)
                <div class="info-block">
                    <div class="icon-box bg-success-subtle"><i class="bx bx-phone text-success"></i></div>
                    <div>
                        <div class="label">Teléfono</div>
                        <div class="value">{{ $doctor->mobile }}</div>
                    </div>
                </div>
                @endif
                @if($doctor && $doctor->email)
                <div class="info-block mb-0">
                    <div class="icon-box bg-info-subtle"><i class="bx bx-envelope text-info"></i></div>
                    <div>
                        <div class="label">Email</div>
                        <div class="value" style="word-break:break-all; font-size:13px;">{{ $doctor->email }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Datos del paciente --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
            <div class="card-header" style="background:linear-gradient(90deg,#4caf50,#388e3c); border-radius:12px 12px 0 0; padding:14px 20px;">
                <h6 class="mb-0 text-white fw-bold"><i class="bx bx-user me-2"></i>Datos del Paciente</h6>
            </div>
            <div class="card-body p-4">
                @if($patient)
                <div class="info-block">
                    <div class="icon-box bg-success-subtle"><i class="bx bx-id-card text-success"></i></div>
                    <div>
                        <div class="label">Nombre Completo</div>
                        <div class="value">{{ $patientName }}</div>
                    </div>
                </div>
                @if($patient->mobile)
                <div class="info-block">
                    <div class="icon-box bg-primary-subtle"><i class="bx bx-phone text-primary"></i></div>
                    <div>
                        <div class="label">Teléfono</div>
                        <div class="value">{{ $patient->mobile }}</div>
                    </div>
                </div>
                @endif
                @if($patient->email)
                <div class="info-block">
                    <div class="icon-box bg-info-subtle"><i class="bx bx-envelope text-info"></i></div>
                    <div>
                        <div class="label">Email</div>
                        <div class="value" style="word-break:break-all; font-size:13px;">{{ $patient->email }}</div>
                    </div>
                </div>
                @endif
                @if($patient->dob)
                <div class="info-block">
                    <div class="icon-box bg-warning-subtle"><i class="bx bx-calendar text-warning"></i></div>
                    <div>
                        <div class="label">Fecha Nacimiento</div>
                        <div class="value">{{ \Carbon\Carbon::parse($patient->dob)->format('d/m/Y') }}
                        <small class="text-muted">({{ \Carbon\Carbon::parse($patient->dob)->age }} años)</small></div>
                    </div>
                </div>
                @endif
                @if($patient->blood_group)
                <div class="info-block mb-0">
                    <div class="icon-box bg-danger-subtle"><i class="bx bx-donate-blood text-danger"></i></div>
                    <div>
                        <div class="label">Grupo Sanguíneo</div>
                        <div class="value">{{ $patient->blood_group }}</div>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>

        {{-- Antecedentes y alergias --}}
        @if($patient && ($patient->pathological_history || $patient->non_pathological_history || $patient->medications_allergies))
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header" style="background:linear-gradient(90deg,#dc3545,#a71d2a); border-radius:12px 12px 0 0; padding:14px 20px;">
                <h6 class="mb-0 text-white fw-bold"><i class="bx bx-history me-2"></i>Antecedentes y Alergias</h6>
            </div>
            <div class="card-body p-4">
                @if($patient->pathological_history)
                <div class="mb-3">
                    <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size:11px; letter-spacing:.6px;">Patológicos</p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;">{{ $patient->pathological_history }}</p>
                </div>
                @endif
                @if($patient->non_pathological_history)
                <div class="mb-3">
                    <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size:11px; letter-spacing:.6px;">Familiares</p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;">{{ $patient->non_pathological_history }}</p>
                </div>
                @endif
                @if($patient->medications_allergies)
                <div>
                    <p class="text-danger text-uppercase fw-semibold mb-1" style="font-size:11px; letter-spacing:.6px;"><i class="bx bx-error-circle me-1"></i>Alergias</p>
                    <div style="background:#fff5f5; border:1px solid #f5c6cb; border-radius:8px; padding:10px;">
                        <p class="mb-0 text-danger" style="font-size:13px; white-space:pre-wrap;">{{ $patient->medications_allergies }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>{{-- end col-lg-4 --}}

</div>{{-- end row --}}
@endsection
