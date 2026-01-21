@extends('layouts.master-layouts')
@section('title')
    @if ($accountant)
        {{ __('Update Accountant Details') }}
    @else
        {{ __('Add New Accountant') }}
    @endif
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
@endsection
    @section('content')
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        @if ($accountant)
                            {{ __('Update Accountant Details') }}
                        @else
                            {{ __('Add New Accountant') }}
                        @endif
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('accountant') }}">{{ __('Accountants') }}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                @if ($accountant)
                                    {{ __('Update Accountant Details') }}
                                @else
                                    {{ __('Add New Accountant') }}
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
                @if ($accountant)
                    @if ($role == 'accountant')
                        <a href="{{ url('/dashboard') }}">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i
                                    class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Back to Dashboard') }}
                            </button>
                        </a>
                    @else
                        <a href="{{ url('accountant/' . $accountant->id) }}">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i
                                    class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Back to Accountant Profile') }}
                            </button>
                        </a>
                    @endif
                @else
                    <a href="{{ url('accountant') }}">
                        <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                            <i
                                class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Back to Accountant List') }}
                        </button>
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote>{{ __('Basic Information') }}</blockquote>
                        <form action="@if ($accountant) {{ url('accountant/' . $accountant->id) }} @else {{ route('accountant.store') }} @endif" method="post" enctype="multipart/form-data">
                            @csrf
                            @if ($accountant)
                                <input type="hidden" name="_method" value="PATCH" />
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('First Name ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('first_name') is-invalid @enderror" tabindex="1"
                                                name="first_name" id="firstname" value="@if ($accountant){{ $accountant->first_name }}@elseif(old('first_name')){{ old('first_name') }}@endif"
                                                placeholder="{{ __('Enter First Name') }}">
                                            @error('first_name')
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
                                                name="email" tabindex="3" id="email" value="@if ($accountant){{ $accountant->email }}@elseif(old('email')){{ old('email') }}@endif"
                                                placeholder="{{ __('Enter Email') }}">
                                            @error('email')
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
                                            <label class="form-label">{{ __('Last Name ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                tabindex="2" name="last_name" id="lastname" value="@if ($accountant){{ $accountant->last_name }}@elseif(old('last_name')){{ old('last_name') }}@endif"
                                                placeholder="{{ __('Enter Last Name') }}">
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Contact Number ') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror"
                                                tabindex="4" name="mobile" id="patientMobile"
                                                value="@if ($accountant){{ $accountant->mobile }}@elseif(old('mobile')){{ old('mobile') }}@endif"
                                                placeholder="{{ __('Enter Contact Number') }}">
                                            @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">{{ __('Profile Photo ') }}</label>
                                            <img class="@error('profile_photo') is-invalid @enderror"
                                                src="@if ($accountant && $accountant->profile_photo != null){{ URL::asset('storage/images/users/' . $accountant->profile_photo) }}@else{{ URL::asset('build/images/users/noImage.png') }}@endif" id="profile_display" onclick="triggerClick()"
                                                data-bs-toggle="tooltip" data-placement="top"
                                                title="Click to Upload Profile Photo" />
                                            <input type="file"
                                                class="form-control @error('profile_photo') is-invalid @enderror"
                                                tabindex="5" name="profile_photo" id="profile_photo" style="display:none;"
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
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        @if ($accountant)
                                            {{ __('Update Details') }}
                                        @else
                                            {{ __('Add New Accountant') }}
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
        <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
        {{-- Profile Photo --}}
        <script>
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
            // Multipale Select
            $(".select2").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
        </script>
    @endsection
