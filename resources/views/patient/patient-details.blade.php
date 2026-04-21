@extends('layouts.master-layouts')
@section('title')
    @if ($patient)
        {{ __('Actualizar Información de Paciente') }}
    @else
        {{ __('Agregar Nuevo Paciente') }}
    @endif
@endsection

@section('css')
<style>
    .form-section {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s;
    }
    .form-section:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,.1);
    }
    .section-header {
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-header i {
        font-size: 1.3rem;
    }
    .section-header h6 {
        margin: 0;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.02em;
    }
    .section-body {
        padding: 24px;
        background: #fff;
    }
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #344054;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26,115,232,.12);
    }
    .form-control::placeholder {
        color: #adb5bd;
        font-size: 0.85rem;
    }
    .required-star {
        color: #dc3545;
        font-weight: 700;
    }
    .photo-upload-zone {
        border: 2px dashed #d0d5dd;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #fafbfc;
    }
    .photo-upload-zone:hover {
        border-color: #1a73e8;
        background: #f0f4ff;
    }
    .photo-upload-zone img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
    }
    .btn-submit-patient {
        background: linear-gradient(135deg, #1a73e8, #0d47a1);
        border: none;
        padding: 12px 32px;
        font-weight: 700;
        font-size: 0.95rem;
        border-radius: 8px;
        letter-spacing: 0.02em;
        transition: all 0.3s;
    }
    .btn-submit-patient:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(26,115,232,.35);
    }
    .input-icon-wrapper {
        position: relative;
    }
    .input-icon-wrapper .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 1.1rem;
    }
    .input-icon-wrapper .form-control {
        padding-left: 38px;
    }
</style>
@endsection

@section('content')
{{-- Encabezado de página --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">
                @if ($patient)
                    ✏️ Actualizar Información de Paciente
                @else
                    👤 Agregar Nuevo Paciente
                @endif
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('patient') }}">Pacientes</a></li>
                    <li class="breadcrumb-item active">
                        @if ($patient) Editar @else Nuevo @endif
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Botón Atrás --}}
<div class="row mb-2">
    <div class="col-12">
        @if ($patient && $patient_info && $medical_info)
            @if ($role == 'patient')
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary btn-sm waves-effect">
                    <i class="bx bx-arrow-back me-1"></i>Atrás
                </a>
            @else
                <a href="{{ url('patient/' . $patient->id) }}" class="btn btn-outline-secondary btn-sm waves-effect">
                    <i class="bx bx-arrow-back me-1"></i>Atrás al Perfil
                </a>
            @endif
        @else
            <a href="{{ url('patient') }}" class="btn btn-outline-secondary btn-sm waves-effect">
                <i class="bx bx-arrow-back me-1"></i>Volver a Pacientes
            </a>
        @endif
    </div>
</div>

{{-- Alertas de errores --}}
@if ($errors->any())
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <strong>Hay errores en el formulario:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

<form action="@if ($patient) {{ url('patient/' . $patient->id) }} @else {{ route('patient.store') }} @endif" method="post" enctype="multipart/form-data">
    @csrf
    @if ($patient)
        <input type="hidden" name="_method" value="PATCH" />
    @endif

    <div class="row">
        {{-- ═══ COLUMNA IZQUIERDA ═══ --}}
        <div class="col-lg-8">

            {{-- ── Sección 1: Información Personal ── --}}
            <div class="card form-section">
                <div class="section-header" style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <i class="bx bx-user text-white"></i>
                    <h6 class="text-white">Información Personal</h6>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres <span class="required-star">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-user input-icon"></i>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    name="first_name" id="FirstName"
                                    value="{{ old('first_name', $patient->first_name ?? '') }}"
                                    placeholder="Ingresar nombres">
                            </div>
                            @error('first_name')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos <span class="required-star">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-user input-icon"></i>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    name="last_name" id="LastName"
                                    value="{{ old('last_name', $patient->last_name ?? '') }}"
                                    placeholder="Ingresar apellidos">
                            </div>
                            @error('last_name')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Género <span class="required-star">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                <option value="" selected disabled>Seleccionar...</option>
                                <option value="Male" @if(old('gender', $patient->gender ?? '') == 'Male') selected @endif>Masculino</option>
                                <option value="Female" @if(old('gender', $patient->gender ?? '') == 'Female') selected @endif>Femenino</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">DUI o Identificación</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-id-card input-icon"></i>
                                <input type="text" class="form-control @error('dui') is-invalid @enderror"
                                    name="dui"
                                    value="{{ old('dui', $patient->dui ?? '') }}"
                                    placeholder="00000000-0">
                            </div>
                            @error('dui')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha de nacimiento <span class="required-star">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-calendar input-icon"></i>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                    name="birth_date"
                                    value="{{ old('birth_date', isset($patient) && $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('Y-m-d') : '') }}">
                            </div>
                            @error('birth_date')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado civil</label>
                            <select class="form-select @error('marital_status') is-invalid @enderror" name="marital_status">
                                <option value="">Seleccione...</option>
                                @foreach (['soltero','casado','divorciado','viudo'] as $status)
                                    <option value="{{ $status }}"
                                        {{ old('marital_status', $patient->marital_status ?? '') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sección 2: Contacto ── --}}
            <div class="card form-section">
                <div class="section-header" style="background: linear-gradient(135deg,#28a745,#1e7e34);">
                    <i class="bx bx-phone text-white"></i>
                    <h6 class="text-white">Información de Contacto</h6>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Teléfono principal <span class="required-star">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-phone input-icon"></i>
                                <input type="tel" class="form-control @error('phone_primary') is-invalid @enderror"
                                    name="phone_primary" id="patientMobile"
                                    value="{{ old('phone_primary', $patient->phone_primary ?? '') }}"
                                    placeholder="Ej: 7890-1234">
                            </div>
                            @error('phone_primary')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono secundario</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-phone input-icon"></i>
                                <input type="text" class="form-control" name="phone_secondary"
                                    value="{{ old('phone_secondary', $patient->phone_secondary ?? '') }}"
                                    placeholder="Opcional">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico <span class="required-star">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-envelope input-icon"></i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" id="patientEmail"
                                    value="{{ old('email', $patient->email ?? '') }}"
                                    placeholder="ejemplo@correo.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Domicilio <span class="required-star">*</span></label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-map input-icon"></i>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    name="address"
                                    value="{{ old('address', $patient->address ?? '') }}"
                                    placeholder="Dirección completa">
                            </div>
                            @error('address')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ocupación</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-briefcase input-icon"></i>
                                <input type="text" class="form-control" name="occupation"
                                    value="{{ old('occupation', $patient->occupation ?? '') }}"
                                    placeholder="Profesión u oficio">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lugar de trabajo</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-building input-icon"></i>
                                <input type="text" class="form-control" name="workplace"
                                    value="{{ old('workplace', $patient->workplace ?? '') }}"
                                    placeholder="Empresa o institución">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Referido por</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-user-voice input-icon"></i>
                                <input type="text" class="form-control" name="referred_by"
                                    value="{{ old('referred_by', $patient->referred_by ?? '') }}"
                                    placeholder="¿Quién lo refirió?">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sección 3: Antecedentes ── --}}
            <div class="card form-section">
                <div class="section-header" style="background: linear-gradient(135deg,#fd7e14,#e55a00);">
                    <i class="bx bx-history text-white"></i>
                    <h6 class="text-white">Antecedentes del Paciente</h6>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Antecedentes patológicos</label>
                            <textarea class="form-control @error('pathological_history') is-invalid @enderror"
                                name="pathological_history" rows="3"
                                placeholder="Enfermedades previas, cirugías, hospitalizaciones...">{{ old('pathological_history', $patient->pathological_history ?? '') }}</textarea>
                            @error('pathological_history')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Antecedentes no patológicos</label>
                            <textarea class="form-control @error('non_pathological_history') is-invalid @enderror"
                                name="non_pathological_history" rows="3"
                                placeholder="Hábitos, estilo de vida, actividad física...">{{ old('non_pathological_history', $patient->non_pathological_history ?? '') }}</textarea>
                            @error('non_pathological_history')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Medicamentos y alergias</label>
                            <textarea class="form-control @error('medications_allergies') is-invalid @enderror"
                                name="medications_allergies" rows="3"
                                placeholder="Medicamentos actuales, alergias conocidas...">{{ old('medications_allergies', $patient->medications_allergies ?? '') }}</textarea>
                            @error('medications_allergies')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sección 4: Información Médica ── --}}
            <div class="card form-section">
                <div class="section-header" style="background: linear-gradient(135deg,#dc3545,#a71d2a);">
                    <i class="bx bx-heart text-white"></i>
                    <h6 class="text-white">Información Médica</h6>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Altura (cm)</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-ruler input-icon"></i>
                                <input type="text" class="form-control @error('height') is-invalid @enderror"
                                    name="height"
                                    value="{{ old('height', $medical_info->height ?? '') }}"
                                    placeholder="Ej: 170">
                            </div>
                            @error('height')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Peso (lb)</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-dumbbell input-icon"></i>
                                <input type="text" class="form-control @error('weight') is-invalid @enderror"
                                    name="weight"
                                    value="{{ old('weight', $medical_info->weight ?? '') }}"
                                    placeholder="Ej: 150">
                            </div>
                            @error('weight')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo de sangre</label>
                            <select class="form-select @error('b_group') is-invalid @enderror" name="b_group">
                                <option value="" selected disabled>Seleccionar...</option>
                                @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                    <option value="{{ $bg }}" @if(old('b_group', $medical_info->b_group ?? '') == $bg) selected @endif>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('b_group')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Presión arterial</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-pulse input-icon"></i>
                                <input type="text" class="form-control @error('b_pressure') is-invalid @enderror"
                                    name="b_pressure"
                                    value="{{ old('b_pressure', $medical_info->b_pressure ?? '') }}"
                                    placeholder="Ej: 120/80">
                            </div>
                            @error('b_pressure')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pulso (BPM)</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-heart input-icon"></i>
                                <input type="text" class="form-control @error('pulse') is-invalid @enderror"
                                    name="pulse"
                                    value="{{ old('pulse', $medical_info->pulse ?? '') }}"
                                    placeholder="Ej: 72">
                            </div>
                            @error('pulse')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dieta especial</label>
                            <select class="form-select @error('diet') is-invalid @enderror" name="diet">
                                <option value="" selected disabled>Seleccionar...</option>
                                <option value="Vegetarian" @if(old('diet', $medical_info->diet ?? '') == 'Vegetarian') selected @endif>Vegetariano</option>
                                <option value="Non-vegetarian" @if(old('diet', $medical_info->diet ?? '') == 'Non-vegetarian') selected @endif>No Vegetariano</option>
                                <option value="Vegan" @if(old('diet', $medical_info->diet ?? '') == 'Vegan') selected @endif>Vegano</option>
                            </select>
                            @error('diet')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alergias</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-error-alt input-icon"></i>
                                <input type="text" class="form-control @error('allergy') is-invalid @enderror"
                                    name="allergy"
                                    value="{{ old('allergy', $medical_info->allergy ?? '') }}"
                                    placeholder="Medicamentos, alimentos, etc.">
                            </div>
                            @error('allergy')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Notas adicionales</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-note input-icon"></i>
                                <input type="text" class="form-control @error('respiration') is-invalid @enderror"
                                    name="respiration"
                                    value="{{ old('respiration', $medical_info->respiration ?? '') }}"
                                    placeholder="Observaciones médicas">
                            </div>
                            @error('respiration')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ COLUMNA DERECHA (Sidebar) ═══ --}}
        <div class="col-lg-4">

            {{-- ── Fotografía del paciente ── --}}
            <div class="card form-section">
                <div class="section-header" style="background: linear-gradient(135deg,#6f42c1,#563d7c);">
                    <i class="bx bx-camera text-white"></i>
                    <h6 class="text-white">Fotografía</h6>
                </div>
                <div class="section-body">
                    <div class="photo-upload-zone" onclick="triggerClick()">
                        <img id="profile_display"
                             src="@if ($patient && $patient->photo){{ URL::asset('storage/images/patients/' . $patient->photo) }}@else{{ URL::asset('build/images/users/noImage.png') }}@endif"
                             alt="Foto del paciente">
                        <p class="mb-1 text-muted small fw-semibold">Clic para subir foto</p>
                        <p class="mb-0 text-muted" style="font-size:0.75rem;">JPG, PNG, GIF • máx. 500KB</p>
                    </div>
                    <input type="file" class="form-control d-none" name="photo" id="profile_photo"
                           accept="image/*" onchange="displayProfile(this)">
                    <input type="hidden" name="webcam_photo" id="webcam_photo">
                    
                    <div class="mt-3 text-center">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="startWebcamBtn" onclick="initWebcam()">
                            <i class="bx bx-video me-1"></i> Tomar con Webcam
                        </button>
                    </div>

                    <!-- Webcam UI -->
                    <div id="webcam-container" class="mt-3 d-none text-center">
                        <div class="position-relative d-inline-block">
                            <video id="webcam-video" width="100%" autoplay playsinline style="border-radius: 8px; border: 2px solid #ddd; max-height: 200px;"></video>
                            <canvas id="webcam-canvas" class="d-none"></canvas>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-success btn-sm me-2" onclick="takeSnapshot()">
                                <i class="bx bx-camera"></i> Capturar
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="stopWebcam()">
                                <i class="bx bx-x"></i> Cancelar
                            </button>
                        </div>
                    </div>

                    @error('photo')
                        <span class="invalid-feedback d-block mt-2"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            {{-- ── Contacto de emergencia ── --}}
            <div class="card form-section">
                <div class="section-header" style="background: linear-gradient(135deg,#dc3545,#a71d2a);">
                    <i class="bx bx-shield-plus text-white"></i>
                    <h6 class="text-white">Contacto de Emergencia</h6>
                </div>
                <div class="section-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre del contacto</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-user input-icon"></i>
                                <input type="text" class="form-control" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}"
                                    placeholder="Nombre completo">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Teléfono de emergencia</label>
                            <div class="input-icon-wrapper">
                                <i class="bx bx-phone-call input-icon"></i>
                                <input type="text" class="form-control" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}"
                                    placeholder="Número de teléfono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Botón de Guardar ── --}}
            <div class="card form-section">
                <div class="section-body text-center py-4">
                    <button type="submit" class="btn btn-primary btn-submit-patient w-100 waves-effect">
                        @if ($patient)
                            <i class="bx bx-save me-2"></i>Actualizar Datos del Paciente
                        @else
                            <i class="bx bx-user-plus me-2"></i>Registrar Nuevo Paciente
                        @endif
                    </button>
                    <a href="{{ url('patient') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bx bx-x me-1"></i>Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
    <script>
        // Profile Photo upload
        function triggerClick() {
            document.querySelector('#profile_photo').click();
        }

        function displayProfile(e) {
            if (e.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('#profile_display').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(e.files[0]);
                document.getElementById('webcam_photo').value = ''; // Limpiar la foto de la webcam
            }
        }

        // Webcam functions
        let video = document.getElementById('webcam-video');
        let canvas = document.getElementById('webcam-canvas');
        let stream = null;

        function initWebcam() {
            document.getElementById('webcam-container').classList.remove('d-none');
            document.getElementById('startWebcamBtn').classList.add('d-none');
            
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(s) {
                    stream = s;
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function(err) {
                    console.log("An error occurred: " + err);
                    alert("No se pudo acceder a la cámara web. Asegúrese de otorgar los permisos necesarios o conectar una cámara.");
                    stopWebcam();
                });
        }

        function stopWebcam() {
            if (stream) {
                let tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                video.srcObject = null;
            }
            document.getElementById('webcam-container').classList.add('d-none');
            document.getElementById('startWebcamBtn').classList.remove('d-none');
        }

        function takeSnapshot() {
            let context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            let dataUrl = canvas.toDataURL('image/jpeg');
            document.getElementById('profile_display').setAttribute('src', dataUrl);
            document.getElementById('webcam_photo').value = dataUrl;
            
            // Limpiar el input file si había algo
            document.getElementById('profile_photo').value = '';
            
            stopWebcam();
        }
    </script>
@endsection
