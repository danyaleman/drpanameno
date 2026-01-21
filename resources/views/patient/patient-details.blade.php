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
                                            <label class="form-label">{{ __('Edad ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('age') is-invalid @enderror"
                                                tabindex="4" name="age" id="patientAge" value="@if ($patient && $patient_info ){{ old('age', $patient_info->age) }}@elseif(old('age')){{ old('age') }}@endif"
                                                placeholder="{{ __('Ingresar Edad') }}">
                                            @error('age')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
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
                                    </div>
                                </div>
                            </div>
                            <blockquote>{{ __('Información Médica') }}</blockquote>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Altura ') }}<span
                                                    class="text-danger">*</span></label>
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
                                            <label for="formmessage">{{ __('Tipo de sangre ') }}<span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label">{{ __('Pulso (BPM) ') }}<span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label">{{ __('Alergias ') }}<span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label">{{ __('Peso (lb) ') }}<span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label">{{ __('Presión arterial ') }}<span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label">{{ __('Notas ') }}<span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label">{{ __('Dieta especial ') }}<span
                                                    class="text-danger">*</span></label>
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
