@extends('layouts.master-layouts')
@section('title') {{ __('Citas Futuras') }} @endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title') Citas Futuras @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Citas @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('today-appointment') }}">
                                    <span class="d-block d-sm-none"><i class="fas fa-calendar-day"></i></span>
                                    <span class="d-none d-sm-block">{{ __("Citas de Hoy") }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{{ url('pending-appointment') }}">
                                    <span class="d-block d-sm-none"><i class="far fa-calendar"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Pendientes') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url('upcoming-appointment') }}">
                                    <span class="d-block d-sm-none"><i class="fas fa-calendar-week"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Futuras') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('complete-appointment') }}">
                                    <span class="d-block d-sm-none"><i class="fas fa-check-square"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Completadas') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('cancel-appointment') }}">
                                    <span class="d-block d-sm-none"><i class="fas fa-window-close"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Canceladas') }}</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="PendingAppointmentList" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered dt-responsive nowrap "
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Nombre de Doctor') }}</th>
                                                <th>{{ __('Nombre de Paciente') }}</th>
                                                <th>{{ __('Telefono de Paciente') }}</th>
                                                <th>{{ __('Correo de Paciente') }}</th>
                                                <th>{{ __('Fecha') }}</th>
                                                <th>{{ __('Hora') }}</th>
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
                                                $currentpage = $Upcoming_appointment->currentPage();
                                            @endphp
                                            @foreach ($Upcoming_appointment as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 + $per_page * ($currentpage - 1) }}</td>
                                                    <td> {{ @$item->doctor->user->first_name . ' ' . @$item->doctor->user->last_name }}
                                                    </td>
                                                    <td> {{ $item->patient->first_name . ' ' . $item->patient->last_name }}
                                                    </td>
                                                    <td> {{ $item->patient->mobile }} </td>
                                                    <td>{{ $item->patient->email }}</td>
                                                    <td>{{ $item->appointment_date }}</td>
                                                    <td>{{ $item->timeSlot->from . ' to ' . $item->timeSlot->to }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12 text-center mt-3">
                                    <div class="d-flex justify-content-start">
                                        Showing {{ $Upcoming_appointment->firstItem() }} to
                                        {{ $Upcoming_appointment->lastItem() }} of
                                        {{ $Upcoming_appointment->total() }}
                                        entries
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {{ $Upcoming_appointment->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <!-- Plugins js -->
        <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <!-- Init js-->
        <script src="{{ URL::asset('build/js/pages/notification.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/appointment.js') }}"></script>
    @endsection
