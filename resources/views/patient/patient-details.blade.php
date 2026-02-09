@extends('layouts.master-layouts')
@section('title')
    @if ($patient )
        {{ __('Actualizar Información de Paciente') }}
    @else
        {{ __('Agregar Nuevo Paciente') }}
    @endif
@endsection
    @section('content')
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        @if ($patient && $patient_info && $medical_info)
                            {{ __('Actualizar Información de Paciente') }}
                        @else
                            {{ __('Agregar Nuevo Paciente') }}
                        @endif
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('patient') }}">{{ __('Pacientes') }}</a></li>
                            <li class="breadcrumb-item active">
                                @if ($patient)
                                    {{ __('Actualizar Información de Paciente') }}
                                @else
                                    {{ __('Agregar Nuevo Paciente') }}
                                @endif
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                @if ($patient && $patient_info && $medical_info)
                    @if ($role == 'patient')
                        <a href="{{ url('/dashboard') }}">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i
                                    class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Atrás') }}
                            </button>
                        </a>
                    @else
                        <a href="{{ url('patient/' . $patient->id) }}">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i
                                    class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Atrás') }}
                            </button>
                        </a>
                    @endif
                @else
                    <a href="{{ url('patient') }}">
                        <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                            <i
                                class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Atrás') }}
                        </button>
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote class="blockquote">{{ __('Información Principal') }}</blockquote>
                        <form action="@if ($patient ) {{ url('patient/' . $patient->id) }} @else {{ route('patient.store') }} @endif" method="post" enctype="multipart/form-data">
                            @csrf
                            @if ($patient )
                                <input type="hidden" name="_method" value="PATCH" />
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Nombres ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                name="first_name" id="FirstName" tabindex="1"
                                                value="@if ($patient){{ old('first_name', $patient->first_name) }}@elseif(old('first_name')){{ old('first_name') }}@endif"
                                                placeholder="{{ __('Ingresar los nombres') }}">
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label for="formmessage">{{ __('Género ') }}<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('gender') is-invalid @enderror" tabindex="3"
                                                name="gender">
                                                <option selected disabled>{{ __('-- Select Gender --') }}</option>
                                                <option value="Male" @if (($patient_info && $patient_info->gender == 'Male') || old('gender') == 'Male') selected @endif>{{ __('Masculino') }}</option>
                                                <option value="Female" @if (($patient_info && $patient_info->gender == 'Female') || old('gender') == 'Female') selected @endif>{{ __('Femenino') }}
                                                </option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                         <div class="mb-3 col-md-12">
                                            <label class="form-label">{{ __('Estado civil') }}</label>
                                            <select class="form-control @error('marital_status') is-invalid @enderror"
                                                name="marital_status">
                                                <option value="">{{ __('Seleccione') }}</option>
                                                @foreach (['soltero','casado','divorciado','viudo'] as $status)
                                                    <option value="{{ $status }}"
                                                        {{ old('marital_status', $patient->marital_status ?? '') == $status ? 'selected' : '' }}>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>   
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Email ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                tabindex="5" name="email" id="patientEmail" value="@if ($patient){{ old('email', $patient->email) }}@elseif(old('email')){{ old('email') }}@endif"
                                                placeholder="{{ __('Enter Email') }}">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Domicilio ') }}<span
                                                    class="text-danger">*</span></label>
                                            <textarea id="formmessage" name="address" tabindex="7"
                                                class="form-control @error('address') is-invalid @enderror" rows="3"
                                                placeholder="{{ __('Enter Current Address') }}">@if ($patient && $patient_info ){{ $patient_info->address }}@elseif(old('address')){{ old('address') }}@endif</textarea>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                            <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Ocupación') }}</label>
                                            <input type="text"
                                                class="form-control @error('occupation') is-invalid @enderror"
                                                name="occupation"
                                                value="{{ old('occupation', $patient->occupation ?? '') }}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Lugar de trabajo') }}</label>
                                            <input type="text"
                                                class="form-control @error('workplace') is-invalid @enderror"
                                                name="workplace"
                                                value="{{ old('workplace', $patient->workplace ?? '') }}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Referido por') }}</label>
                                            <input type="text"
                                                class="form-control"
                                                name="referred_by"
                                                value="{{ old('referred_by', $patient->referred_by ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Apellidos ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                tabindex="2" name="last_name" id="LastName" value="@if ($patient){{ old('last_name', $patient->last_name) }}@elseif(old('last_name')){{ old('last_name') }}@endif"
                                                placeholder="{{ __('Ingresar los apellidos') }}">
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                        <label class="form-label">{{ __('Fecha de nacimiento') }}</label>
                                        <input type="date"
                                            class="form-control @error('birth_date') is-invalid @enderror"
                                            name="birth_date"
                                            value="{{ old('birth_date', optional($patient->birth_date ?? null)->format('d-m-Y')) }}">
                                        @error('birth_date')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Número de Contacto ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                                tabindex="6" name="mobile" id="patientMobile"
                                                value="@if ($patient ){{ old('mobile', $patient->mobile) }}@elseif(old('mobile')){{ old('mobile') }}@endif"
                                                placeholder="{{ __('Ingresar Número de Contacto') }}">
                                            @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Teléfono secundario') }}</label>
                                            <input type="text"
                                                class="form-control @error('phone_secondary') is-invalid @enderror"
                                                name="phone_secondary"
                                                value="{{ old('phone_secondary', $patient->phone_secondary ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Fotografía ') }}</label>
                                            <img class="@error('profile_photo') is-invalid @enderror "
                                                src="@if ($patient && $patient->profile_photo != null){{ URL::asset('storage/images/users/' . $patient->profile_photo) }}@else{{ URL::asset('build/images/users/noImage.png') }}@endif" onclick="triggerClick()"
                                                data-bs-toggle="tooltip" data-placement="top"
                                                title="Click to Upload Profile Photo" id="profile_display" />
                                            <input type="file"
                                                class="form-control @error('profile_photo') is-invalid @enderror"
                                                tabindex="8" name="profile_photo" id="profile_photo" style="display:none;"
                                                onchange="displayProfile(this)">
                                            @error('profile_photo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-3">
                                                <label class="form-label">{{ __('Contacto de emergencia') }}</label>
                                                <input type="text"
                                                    class="form-control"
                                                    name="emergency_contact_name"
                                                    value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}">
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">{{ __('Teléfono de emergencia') }}</label>
                                                <input type="text"
                                                    class="form-control"
                                                    name="emergency_contact_phone"
                                                    value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}">
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <blockquote class="mt-4">{{ __('Antecedentes del paciente') }}</blockquote>

                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('Antecedentes patológicos') }}</label>
                                <textarea
                                    class="form-control @error('pathological_history') is-invalid @enderror"
                                    name="pathological_history"
                                    rows="3"
                                >{{ old('pathological_history', $patient->pathological_history ?? '') }}</textarea>
                                @error('pathological_history')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('Antecedentes no patológicos') }}</label>
                                <textarea
                                    class="form-control @error('non_pathological_history') is-invalid @enderror"
                                    name="non_pathological_history"
                                    rows="3"
                                >{{ old('non_pathological_history', $patient->non_pathological_history ?? '') }}</textarea>
                                @error('non_pathological_history')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">{{ __('Medicamentos y alergias') }}</label>
                                <textarea
                                    class="form-control @error('medications_allergies') is-invalid @enderror"
                                    name="medications_allergies"
                                    rows="3"
                                >{{ old('medications_allergies', $patient->medications_allergies ?? '') }}</textarea>
                                @error('medications_allergies')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                        </div>  
                            <blockquote>{{ __('Información Médica') }}</blockquote>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Altura ') }}</label>
                                            <input type="text" class="form-control @error('height') is-invalid @enderror"
                                                name="height" tabindex="9" value="@if ($patient && $patient_info && $medical_info){{ old('height', $medical_info->height) }}@elseif(old('height')){{ old('height') }}@endif"
                                                id="patientHeight" placeholder="{{ __('Ingresar Altura en Centímetros') }}">
                                            @error('height')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label for="formmessage">{{ __('Tipo de sangre ') }}</label>
                                            <select class="form-control @error('b_group') is-invalid @enderror"
                                                tabindex="11" name="b_group">
                                                <option selected disabled>{{ __('-- Select Blood Group --') }}</option>
                                                <option value="A+" @if (($medical_info && $medical_info->b_group == 'A+') || old('b_group') == 'A+') selected @endif>{{ __('A+') }}</option>
                                                <option value="A-" @if (($medical_info && $medical_info->b_group == 'A-') || old('b_group') == 'A-') selected @endif>{{ __('A-') }}</option>
                                                <option value="B+" @if (($medical_info && $medical_info->b_group == 'B+') || old('b_group') == 'B+') selected @endif>{{ __('B+') }}</option>
                                                <option value="B-" @if (($medical_info && $medical_info->b_group == 'B-') || old('b_group') == 'B-') selected @endif>{{ __('B-') }}</option>
                                                <option value="O+" @if (($medical_info && $medical_info->b_group == 'O+') || old('b_group') == 'O+') selected @endif>{{ __('O+') }}</option>
                                                <option value="O-" @if (($medical_info && $medical_info->b_group == 'O-') || old('b_group') == 'O-') selected @endif>{{ __('O-') }}</option>
                                                <option value="AB+" @if (($medical_info && $medical_info->b_group == 'AB+') || old('b_group') == 'AB+') selected @endif>{{ __('AB+') }}</option>
                                                <option value="AB-" @if (($medical_info && $medical_info->b_group == 'AB-') || old('b_group') == 'AB-') selected @endif>{{ __('AB-') }}</option>
                                            </select>
                                            @error('b_group')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Pulso (BPM) ') }}</label>
                                            <input type="text" class="form-control @error('pulse') is-invalid @enderror"
                                                tabindex="13" name="pulse" value="@if ($patient && $patient_info && $medical_info){{ old('pulse', $medical_info->pulse) }}@elseif(old('pulse')){{ old('pulse') }}@endif"
                                                id="patientPulse" placeholder="{{ __('Ingresar Pulso') }}">
                                            @error('pulse')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Alergias ') }}</label>
                                            <input type="text" class="form-control @error('allergy') is-invalid @enderror"
                                                tabindex="15" name="allergy" id="patientAllergy"
                                                value="@if ($patient && $patient_info && $medical_info){{ old('allergy', $medical_info->allergy) }}@elseif(old('allergy')){{ old('allergy') }}@endif"
                                                placeholder="{{ __('Ingresar Alergias') }}">
                                            @error('allergy')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Peso (lb) ') }}</label>
                                            <input type="text" class="form-control @error('weight') is-invalid @enderror"
                                                tabindex="10" name="weight" id="patientWeight"
                                                value="@if ($patient && $patient_info && $medical_info){{ old('weight', $medical_info->weight) }}@elseif(old('weight')){{ old('weight') }}@endif" placeholder="{{ __('Enter Weight') }}">
                                            @error('weight')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Presión arterial ') }}</label>
                                            <input type="tel"
                                                class="form-control @error('b_pressure') is-invalid @enderror"
                                                tabindex="12" name="b_pressure" id="blood_pressure"
                                                value="@if ($patient && $patient_info && $medical_info){{ old('b_pressure', $medical_info->b_pressure) }}@elseif(old('b_pressure')){{ old('b_pressure') }}@endif"
                                                placeholder="{{ __('Ingresar Presión arterial') }}">
                                            @error('b_pressure')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Notas ') }}</label>
                                            <input type="tel"
                                                class="form-control @error('respiration') is-invalid @enderror"
                                                tabindex="14" name="respiration" id="patientRespiration"
                                                value="@if ($patient && $patient_info && $medical_info){{ old('respiration', $medical_info->respiration) }}@elseif(old('respiration')){{ old('respiration') }}@endif"
                                                placeholder="{{ __('Ingresar Notas') }}">
                                            @error('respiration')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Dieta especial ') }}</label>
                                            <select class="form-control @error('diet') is-invalid @enderror" tabindex="16"
                                                name="diet">
                                                <option selected disabled>{{ __('-- Select Diet --') }}</option>
                                                <option value="Vegetarian" @if (($medical_info && $medical_info->diet == 'Vegetarian') || old('diet') == 'Vegetarian') selected @endif>
                                                    {{ __('Vegetariano') }}</option>
                                                <option value="Non-vegetarian" @if (($medical_info && $medical_info->diet == 'Non-vegetarian') || old('diet') == 'Non-vegetarian') selected @endif>
                                                    {{ __('No Vegetariano') }}</option>
                                                <option value="Vegan" @if (($medical_info && $medical_info->diet == 'Vegan') || old('diet') == 'Vegan') selected @endif>{{ __('Vegano') }}
                                                </option>
                                            </select>
                                            @error('diet')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        @if ($patient && $patient_info && $medical_info)
                                            {{ __('Actualizar Datos del Paciente') }}
                                        @else
                                            {{ __('Agregar Nuevo Paciente') }}
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
    @section('script')
        <script>
            // Profile Photo
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
                }
            }
        </script>
    @endsection
