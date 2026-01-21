@extends('layouts.master-layouts')
@section('title')
    @if ($department)
        {{ __('Update Department Details') }}
    @else
        {{ __('Add New Department') }}
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
                    @if ($department)
                        {{ __('Update Department Details') }}
                    @else
                        {{ __('Add New Department') }}
                    @endif
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('department') }}">{{ __('Departments') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            @if ($department)
                                {{ __('Update Department Details') }}
                            @else
                                {{ __('Add New Department') }}
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
            @if ($department)
                @if ($role == 'department')
                    <a href="{{ url('/dashboard') }}">
                        <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                            <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Back to Dashboard') }}
                        </button>
                    </a>
                @else
                    <a href="{{ url('department/' . $department->id) }}">
                        <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                            <i
                                class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Back to Department Profile') }}
                        </button>
                    </a>
                @endif
            @else
                <a href="{{ url('department') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Back to Department List') }}
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
                    <form
                        action="@if ($department) {{ url('department/' . $department->id) }} @else {{ route('department.store') }} @endif"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @if ($department)
                            <input type="hidden" name="_method" value="PATCH" />
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="department-name" class="form-label">{{ __('Department Name ') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            tabindex="1" name="name" id="department-name"
                                            value="@if ($department) {{ $department->name }}@elseif(old('name')){{ old('name') }} @endif"
                                            placeholder="{{ __('Enter Department Name') }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="department-description"
                                            class="form-label">{{ __('Description ') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('description') is-invalid @enderror" tabindex="1"
                                            name="description" id="department-description"
                                            value="@if ($department) {{ $department->description }}@elseif(old('description')){{ old('description') }} @endif"
                                            placeholder="{{ __('Max Description 250 caracters') }}">
                                        @error('description')
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
                                    @if ($department)
                                        {{ __('Update Details') }}
                                    @else
                                        {{ __('Add New Department') }}
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
