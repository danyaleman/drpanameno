@extends('layouts.master-without-nav')

@section('title') {{ __("Change Password") }} @endsection

@section('body')
<body>
@endsection

@section('content')
    <div class="home-btn d-none d-sm-block">
        <a href="{{ url('login') }}" class="text-dark"><i class="fas fa-home h2"></i></a>
    </div>
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary"> {{ __("Change Password") }}</h5>
                                        <p>Re-Password with {{ AppSetting('title'); }}.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ URL::asset('build/images/profile-img.png') }}" alt="" class="img-fluid">
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
                                <form class="form-horizontal" method="POST" action="{{ url('change-password') }}">
                                    @csrf
                                    @if ($msg = Session::get('error'))
                                        <div class="alert alert-danger">
                                            <span> {{ $msg }} </span>
                                        </div>
                                    @endif

                                    @if ($msg = Session::get('success'))
                                        <div class="alert alert-success">
                                            <span> {{ $msg }} </span>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label for="oldpassword">{{ __("Current Password ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('oldpassword') is-invalid @enderror" name="oldpassword" id="oldpassword" placeholder="{{ __("Enter Current password") }}">
                                        @error('oldpassword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="newpassword">{{ __("New Password ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="newpassword" placeholder="{{ __("Enter New password") }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="userpassword">{{ __("Confirm Password ") }}<span
                                            class="text-danger">*</span></label>
                                        <input type="password" id="userpassword" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __("Enter confirm password") }}">
                                    </div>
                                    <div class="mb-3 row mb-0">
                                        <div class="col-12 text-end">
                                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">{{ __("Change Password") }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>© {{ date('Y') }} {{ AppSetting('title'); }}. Crafted with <i class="mdi mdi-heart text-danger"></i> {{ __("by Themesbrand") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
