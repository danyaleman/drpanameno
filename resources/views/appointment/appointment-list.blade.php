@extends('layouts.master-layouts')
@section('title') {{ __('Citas') }} @endsection
@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/datatables/datatables.min.css') }}">
@endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title') Listado de Citas @endslot
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
                                <a class="nav-link active" data-bs-toggle="tab" href="#PendingAppointmentList" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Pendientes') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#UpcomingAppointmentList" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Futuras') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ComplateAppointmentList" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Completadas') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#CancelAppointmentList" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">{{ __('Citas Canceladas') }}</span>
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="PendingAppointmentList" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap datatable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No.') }}</th>
                                            <th>{{ __('Nombre de Paciente') }}</th>
                                            <th>{{ __('Teléfono') }}</th>
                                            <th>{{ __('Correo Electrónico') }}</th>
                                            <th>{{ __('Fecha') }}</th>
                                            <th>{{ __('Hora') }}</th>
                                            <th>{{ __('Acción') }}</th>
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
                                            $currentpage = $pending_appointment->currentPage();
                                        @endphp
                                        @foreach ($pending_appointment as $item)
                                            <tr>
                                                <td> {{ $loop->index + 1 + $per_page * ($currentpage - 1) }} </td>
                                                <td> {{ optional($item->patient)->first_name ?? 'N/A' }} {{ optional($item->patient)->last_name ?? '' }}
                                                </td>
                                                <td> {{ optional($item->patient)->mobile ?? 'N/A' }} </td>
                                                <td> {{ optional($item->patient)->email ?? 'N/A' }} </td>
                                                <td>{{ \Carbon\Carbon::parse($item->appointment_date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</td>
                                                <td>{{ optional($item->timeSlot)->from ?? 'N/A' }} {{ optional($item->timeSlot)->to ? '- ' . optional($item->timeSlot)->to : '' }}</td>
                                                <td>
                                                    @if ($role != 'patient')
                                                        <button type="button" class="btn btn-success complete"
                                                            data-id="{{ $item->id }}">Completar</button>
                                                    @endif
                                                    <button type="button" class="btn btn-danger cancel"
                                                        data-id="{{ $item->id }}">Cancelar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="UpcomingAppointmentList" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap datatable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No.') }}</th>
                                            <th>{{ __('Nombre de Paciente') }}</th>
                                            <th>{{ __('Teléfono') }}</th>
                                            <th>{{ __('Correo Electrónico') }}</th>
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
                                                <td> {{ $loop->index + 1 + $per_page * ($currentpage - 1) }} </td>
                                                <td> {{ optional($item->patient)->first_name ?? 'N/A' }} {{ optional($item->patient)->last_name ?? '' }}
                                                </td>
                                                <td> {{ optional($item->patient)->mobile ?? 'N/A' }} </td>
                                                <td>{{ optional($item->patient)->email ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->appointment_date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</td>
                                                <td>{{ optional($item->timeSlot)->from ?? 'N/A' }} {{ optional($item->timeSlot)->to ? '- ' . optional($item->timeSlot)->to : '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="ComplateAppointmentList" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap datatable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                           <th>{{ __('No.') }}</th>
                                            <th>{{ __('Nombre de Paciente') }}</th>
                                            <th>{{ __('Teléfono') }}</th>
                                            <th>{{ __('Correo Electrónico') }}</th>
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
                                            $currentpage = $Complete_appointment->currentPage();
                                        @endphp
                                        @foreach ($Complete_appointment as $item)
                                            <tr>
                                                <td> {{ $loop->index + 1 + $per_page * ($currentpage - 1) }} </td>
                                                <td> {{ optional($item->patient)->first_name ?? 'N/A' }} {{ optional($item->patient)->last_name ?? '' }}
                                                </td>
                                                <td> {{ optional($item->patient)->mobile ?? 'N/A' }} </td>
                                                <td>{{ optional($item->patient)->email ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->appointment_date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</td>
                                                <td>{{ optional($item->timeSlot)->from ?? 'N/A' }} {{ optional($item->timeSlot)->to ? '- ' . optional($item->timeSlot)->to : '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="CancelAppointmentList" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap datatable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No.') }}</th>
                                            <th>{{ __('Nombre de Paciente') }}</th>
                                            <th>{{ __('Teléfono') }}</th>
                                            <th>{{ __('Correo Electrónico') }}</th>
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
                                            $currentpage = $Cancel_appointment->currentPage();
                                        @endphp
                                        @foreach ($Cancel_appointment as $item)
                                            <tr>
                                                <td> {{ $loop->index + 1 + $per_page * ($currentpage - 1) }} </td>
                                                <td> {{ optional($item->patient)->first_name ?? 'N/A' }} {{ optional($item->patient)->last_name ?? '' }}
                                                </td>
                                                <td> {{ optional($item->patient)->mobile ?? 'N/A' }} </td>
                                                <td>{{ optional($item->patient)->email ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->appointment_date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</td>
                                                <td>{{ optional($item->timeSlot)->from ?? 'N/A' }} {{ optional($item->timeSlot)->to ? '- ' . optional($item->timeSlot)->to : '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <!-- Plugins js -->
        <script src="{{ URL::asset('build/libs/datatables/datatables.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <!-- Init js-->
        <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/notification.init.js') }}"></script>
    @endsection
    @section('script-bottom')
        <script>
            // complete appointment
            $('.complete').click(function(e) {
                var id = $(this).data('id');
                var token = $("input[name='_token']").val();
                var status = 1;
                if (confirm('¿Estás seguro de que quieres confirmar la cita?')) {
                    $.ajax({
                        type: "post",
                        url: "appointment-status/" + id,
                        data: {
                            'appointment_id': id,
                            '_token': token,
                            'status': status
                        },
                        beforeSend: function() {
                            $('#preloader').show()
                        },
                        success: function(response) {
                            toastr.success(reponse.Message);
                            location.reload();
                        },
                        error: function(response) {
                            toastr.error(response.responseJSON.message);
                        },
                        complete: function() {
                            $('#preloader').hide();
                        }
                    });
                }
            });
            // cancel appointment
            $('.cancel').click(function(e) {
                var id = $(this).data('id');
                var token = $("input[name='_token']").val();
                var status = 2;
                if (confirm('¿Estás seguro de que quieres cancelar la cita?')) {
                    $.ajax({
                        type: "post",
                        url: "appointment-status/" + id,
                        data: {
                            'appointment_id': id,
                            '_token': token,
                            'status': status
                        },
                        beforeSend: function() {
                            $('#preloader').show()
                        },
                        success: function(response) {
                            toastr.success(reponse.Message);
                            location.reload();
                        },
                        error: function(response) {
                            toastr.error(response.responseJSON.message);
                        },
                        complete: function() {
                            $('#preloader').hide();
                        }
                    });
                }
            });
            // active tab
            if (window.location.href) {
                var url = window.location.href;
                var activeTab = url.substring(url.indexOf("#") + 1);
                var URL = document.location.origin;
                if (url.substring(url.indexOf("#") + 1) == URL + '/appointment-list') {
                    $("#PendingAppointmentList").addClass("active in");
                } else {
                    $(".tab-pane").removeClass("active in");
                    $("#" + activeTab).addClass("active in");
                    $('a[href="#' + activeTab + '"]').tab('show')
                }
            }
        </script>
    @endsection
