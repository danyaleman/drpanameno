@extends('layouts.master-layouts')
@section('title')
    {{ __('Accountant Profile') }}
@endsection
@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/datatables/datatables.min.css') }}">
@endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title')
                Profile
            @endslot
            @slot('li_1')
                Dashboard
            @endslot
            @slot('li_2')
                Profile
            @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-4">
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-3">
                                    <h5 class="text-primary">{{ __('Accountant Information') }}</h5>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ URL::asset('build/images/profile-img.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <img src="@if ($accountant->profile_photo != '') {{ URL::asset('storage/images/users/' . $accountant->profile_photo) }}@else{{ URL::asset('build/images/users/noImage.png') }} @endif"
                                        alt="{{ $accountant->fisrt_name }}" class="img-thumbnail rounded-circle">
                                </div>
                                <h5 class="font-size-15 text-truncate"> {{ $accountant->first_name }}
                                    {{ $accountant->last_name }} </h5>
                                <p class="text-muted mb-0 text-truncate"> {{ $accountant->title }} </p>
                            </div>
                            <div class="col-sm-8">
                                <div class="pt-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="font-size-12">{{ __('Last Login:') }}</h5>
                                            <p class="text-muted mb-0"> {{ $accountant->last_login }} </p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ url('profile-edit') }}"
                                            class="btn btn-primary waves-effect waves-light btn-sm">
                                            {{ __('Edit Profile') }} <i class="mdi mdi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ __('Personal Information') }}</h4>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">{{ __('Full Name:') }}</th>
                                        <td>{{ $accountant->first_name }} {{ $accountant->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Contact:') }}</th>
                                        <td> {{ $accountant->mobile }} </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">{{ __('Email:') }}</th>
                                        <td> {{ $accountant->email }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end card -->
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">{{ __('Total Invoices') }}</p>
                                        <h4 class="mb-0">{{ $invoices->total() }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                        <span class="avatar-title">
                                            <i class="bx bxs-calendar-check font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">{{ __('Total Doctors') }}</p>
                                        <h4 class="mb-0">{{ $doctors->total() }}</h4>
                                    </div>
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                        <span class="avatar-title">
                                            <i class="bx bxs-calendar-check font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#DoctorList" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Doctors List') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#Invoices" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Invoices') }}</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="DoctorList" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap "
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sr. No') }}</th>
                                            <th>{{ __('Doctor Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Mobile') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (session()->has('page_limit'))
                                            @php
                                                $per_page = session()->get('page_limit');
                                            @endphp
                                        @else
                                            @php
                                                $per_page = Config::get('app.page_limit');
                                            @endphp
                                        @endif
                                        @php
                                            $currentpage = $doctors->currentPage();
                                        @endphp
                                        @foreach ($doctors as $doctor)
                                            <tr>
                                                <td>{{ $loop->index + 1 + $per_page * ($currentpage - 1) }}</td>
                                                <td> {{ $doctor->first_name . ' ' . $doctor->last_name }}
                                                </td>
                                                <td>{{ $doctor->email }}</td>
                                                <td>{{ $doctor->mobile }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12 text-center mt-3">
                                    <div class="d-flex justify-content-start">
                                        Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of
                                        {{ $doctors->total() }} entries
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {{ $doctors->links() }}
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="Invoices" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap "
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sr. No') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Patient name') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Option') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (session()->has('page_limit'))
                                            @php
                                                $per_page = session()->get('page_limit');
                                            @endphp
                                        @else
                                            @php
                                                $per_page = Config::get('app.page_limit');
                                            @endphp
                                        @endif
                                        @php
                                            $currentpage = $invoices->currentPage();
                                        @endphp
                                        @foreach ($invoices as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 + $per_page * ($currentpage - 1) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                                <td>{{ $item->user->first_name . ' ' . $item->user->last_name }}</td>
                                                <td>{{ $item->payment_status }}</td>
                                                <td>
                                                    <a href="{{ url('invoice/' . $item->id) }}">
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                            {{ __('View') }}
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-12 text-center mt-3">
                                    <div class="d-flex justify-content-start">
                                        Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of
                                        {{ $invoices->total() }} entries
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {{ $invoices->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
    @section('script')
        <!-- Chart plugins -->
        <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
        <!-- Plugins js -->
        <script src="{{ URL::asset('build/libs/datatables/datatables.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <!-- Init js-->
        <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>
    @endsection
