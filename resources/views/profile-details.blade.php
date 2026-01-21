@extends('layouts.master-without-nav')
@section('title') {{ __('Completa tu Perfil') }} @endsection
@section('body')

    <body>
    @endsection
    @section('content')
        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="card overflow-hidden">
                            <div class="bg-primary-subtle">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">{{ __('Registro de Paciente') }}</h5>
                                            <p>Ahora, completa tu perfil en {{ AppSetting('title') }} .</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ URL::asset('build/images/profile-img.png') }}" alt=""
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <form method="POST" class="form-horizontal mt-4" action="{{ url('user') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <blockquote>{{ __('Información de Paciente') }}</blockquote>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">{{ __('Edad ') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('age') is-invalid @enderror" name="age"
                                                        id="patientAge" value="{{ old('age') }}"
                                                        placeholder="{{ __('Ingresa edad') }}">
                                                    @error('age')
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
                                                    <textarea id="formmessage" name="address"
                                                        class="form-control @error('address') is-invalid @enderror"
                                                        value="{{ old('address') }}" rows="3"
                                                        placeholder="{{ __('Ingresa tu domicilio') }}"></textarea>
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
                                                <div class="mb-3 col-md-12">
                                                    <label for="formmessage">{{ __('Genero ') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('gender') is-invalid @enderror"
                                                        name="gender">
                                                        <option hidden selected disabled>{{ __('-- Selecciona genero --') }}</option>
                                                        <option value="Male">{{ __('Masculino') }}</option>
                                                        <option value="Female">{{ __('Femenino') }}</option>
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
                                                    <label class="form-label">{{ __('Foto de perfil ') }}<span
                                                            class="text-danger">*</span></label>
                                                    <img class="@error('profile_photo') is-invalid @enderror"
                                                        src="{{ URL::asset('build/images/users/noImage.png') }}"
                                                        id="profile_display" onclick="triggerClick()" data-bs-toggle="tooltip"
                                                        data-placement="top" title="Click to Upload Profile Photo" />
                                                    <input type="file"
                                                        class="form-control @error('profile_photo') is-invalid @enderror"
                                                        name="profile_photo" id="profile_photo" style="display:none;"
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
                                    <blockquote>{{ __('Información Clinica') }}</blockquote>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">{{ __('Altura (m)') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('height') is-invalid @enderror"
                                                        name="height" value="{{ old('height') }}" id="patientHeight"
                                                        placeholder="{{ __('Ingesa la altura en metros') }}">
                                                    @error('height')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-12">
                                                    <label for="formmessage">{{ __('Grupo Sanguíneo ') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control @error('b_group') is-invalid @enderror"
                                                        name="b_group">
                                                        <option hidden selected disabled>{{ __('-- Selecciona el grupo sanguineo --') }}</option>
                                                        <option value="A+">{{ __('A+') }}</option>
                                                        <option value="A-">{{ __('A-') }}</option>
                                                        <option value="B+">{{ __('B+') }}</option>
                                                        <option value="B-">{{ __('B-') }}</option>
                                                        <option value="O+">{{ __('O+') }}</option>
                                                        <option value="O-">{{ __('O-') }}</option>
                                                        <option value="AB+">{{ __('AB+') }}</option>
                                                        <option value="AB-">{{ __('AB-') }}</option>
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
                                                    <label class="form-label">{{ __('Ritmo Cardiaco ') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('pulse') is-invalid @enderror"
                                                        name="pulse" value="{{ old('pulse') }}" id="patientPulse"
                                                        placeholder="{{ __('Ingresar Ritmo Cardiaco') }}">
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
                                                    <input type="text"
                                                        class="form-control @error('allergy') is-invalid @enderror"
                                                        name="allergy" id="patientAllergy" value="{{ old('allergy') }}"
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
                                                    <input type="text"
                                                        class="form-control @error('weight') is-invalid @enderror"
                                                        name="weight" id="patientWeight" value="{{ old('weight') }}"
                                                        placeholder="{{ __('Ingresar Peso') }}">
                                                    @error('weight')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">{{ __('Presión Arterial ') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('b_pressure') is-invalid @enderror"
                                                        name="b_pressure" id="blood_pressure"
                                                        value="{{ old('b_pressure') }}"
                                                        placeholder="{{ __('Ingresar Presión Arterial') }}">
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
                                                    <input type="text"
                                                        class="form-control @error('respiration') is-invalid @enderror"
                                                        name="respiration" id="patientRespiration"
                                                        value="{{ old('respiration') }}"
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
                                                    <select class="form-control @error('diet') is-invalid @enderror"
                                                        name="diet">
                                                        <option hidden selected disabled>{{ __('-- Selecciona Dieta --') }}</option>
                                                        <option value="Vegetarian">{{ __('Vegetariano') }}</option>
                                                        <option value="Non-vegetarian">{{ __('No-Vegetariano') }}
                                                        </option>
                                                        <option value="Vegan">{{ __('Vegano') }}</option>
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
                                        <div class="col-md-12 text-center">
                                            <button type="submit"
                                                class="btn btn-primary form-control">{{ __('Save Profile') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
