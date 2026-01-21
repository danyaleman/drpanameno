@extends('layouts.master-layouts')
@section('title')
    {{ __('Dashboard') }}
@endsection
    @section('content')
        @if ($role == 'admin')
            @include('layouts.admin-dashboard')
        @elseif ($role == 'doctor')
            @include('layouts.doctor-dashboard')
        @elseif ($role == 'receptionist')
            @include('layouts.receptionist-dashboard')
        @elseif ($role == 'patient')
            @include('layouts.patient-dashboard')
        @elseif ($role == 'accountant')
            @include('layouts.accountant-dashboard')
        @endif
    @endsection
    @section('script')
        <!-- Plugin Js-->
        <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/dashboard.init.js') }}"></script>
    @endsection
