@extends('layouts.master-without-nav')

@section('title') {{ __("Registro") }} @endsection

@section('body')
<body>
@endsection

@section('content')
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">{{ __("Registro de Paciente") }}</h5>
                                        <p>Ingresa a {{ AppSetting('title'); }} y descubre beneficios e historial médico.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ URL::asset('build/images/profile-img.png') }}" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="{{ url('/dashboard') }}" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ URL::asset('build/images/logo-light.svg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                                <a href="{{ url('/dashboard') }}" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ URL::asset('build/images/logo.svg') }}" alt="" class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <form method="POST" class="form-horizontal" action="{{ url('register') }}">
                                    @csrf
                                    @if ($msg = Session::get('error'))
                                        <div class="alert alert-danger">
                                            <span> {{ $msg }} </span>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label for="first_name">{{ __("Nombres ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            value="{{ old('first_name') }}" name="first_name" id="userfirstname"
                                            placeholder="{{ __("Ingresa tus nombres") }}">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="last_name">{{ __("Apellido ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            value="{{ old('last_name') }}" name="last_name" id="userlastname"
                                            placeholder="{{ __("Ingresa tus apellidos") }}">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="usermobile">{{ __("Número de contacto ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                            value="{{ old('mobile') }}" name="mobile" id="usermobile"
                                            placeholder="{{ __("Ingresa tu número de contacto") }}">
                                        @error('mobile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="useremail">{{ __("Email ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" id="useremail" name="email"
                                            placeholder="{{ __("Enter email") }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="userpassword">{{ __("Contraseña ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            value="{{ old('password') }}" name="password" id="userpassword"
                                            placeholder="{{ __("Ingresa la contraseña") }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="userpassword">{{ __("Confirmar Contraseña ") }} <span
                                            class="text-danger">*</span></label>
                                        <input id="password-confirm" type="password" name="password_confirmation"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="{{ __("Ingresa la contraseña") }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn btn-primary w-100 waves-effect waves-light"
                                            type="submit">{{ __("Registrarse") }}</button>
                                    </div>
                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Al registrarse, estas de acuerdo con los términos de uso de {{ AppSetting('title'); }} <a href="#" class="text-primary">{{ __("Terminos de uso") }}</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>{{ __("Ya tienes cuenta ?") }} <a href="{{ url('login') }}"
                                class="fw-medium text-primary">{{ __("Login") }} </a> </p>
                        <p>© {{ date('Y') }} {{ AppSetting('title'); }}. Crafted with <i class="mdi mdi-heart text-danger"></i> {{ __("by Ing.Alemán") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
