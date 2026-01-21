<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ AppSetting('title') }} - Hospital & Clinic Management Laravel System </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Hospital Management System" name="description" />
    <meta content="Doctorly" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/') . '/' . AppSetting('favicon') }}">
    @include('layouts.head')
</head>

<body>

    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">{{ __('Reset Password') }}</h5>
                                        <p>Reset your password with {{ AppSetting('title') }}.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ URL::asset('build/images/profile-img.png') }}" alt="Doctorly"
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
                                            <img src="{{ URL::asset('build/images/logo.svg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <h4>{{ __('Hello,') }} {{ $user->first_name }} {{ $user->last_name }} </h4>
                                <p>
                                    <a
                                        href="{{ url('reset-password/' . $user->id . '/' . $token) }}">{{ __('Click here') }}</a>
                                    to reset your {{ AppSetting('title') }} account password.
                                </p>
                                <p> {{ __('If password reset request is not raised by you then immediately change your password to secure your account.') }}
                                </p>
                                <p>{{ __('Thank you,') }}</p>
                                <p>{{ AppSetting('title') }}.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>© {{ date('Y') }} {{ AppSetting('title') }}. Crafted with <i
                                class="mdi mdi-heart text-danger"></i> {{ __('by Themesbrand') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer-script')

</body>

</html>
