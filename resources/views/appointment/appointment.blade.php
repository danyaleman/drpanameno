@extends('layouts.master-layouts')
@section('title') {{ __('Programar Cita') }} @endsection
@section('css')
    <!-- Calender -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/fullcalendar/fullcalendar.min.css') }}">
@endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title') Citas Programadas @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Citas @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <a href="{{ url('/appointment-create') }}"
                    class="btn btn-primary text-white waves-effect waves-light mb-4">
                    <i class="bx bx-plus font-size-16 align-middle me-2"></i> {{ __('Nueva Cita') }}
                </a>
            </div> <!-- end col -->
        </div> <!-- end row -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div> <!-- end col -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ __('Listas de Citas') }} | <label
                                id="selected_date"><?php echo date('d M, Y'); ?></label>
                        </h4>
                        <div id="appointment_list">
                            <table class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('No.') }}</th>
                                        @if ($role == 'patient')
                                            <th>{{ __('Nombre del Doctor') }}</th>
                                            <th>{{ __('Numero de Doctor') }}</th>
                                        @elseif($role == 'doctor')
                                            <th>{{ __('Nombre del Paciente') }}</th>
                                            <th>{{ __('Numero de Telefono') }}</th>
                                        @else
                                            <th>{{ __('Nombre del Paciente') }}</th>
                                            <th>{{ __('Nombre del Doctor') }}</th>
                                            <th>{{ __('Numero de Telefono') }}</th>

                                        @endif
                                        <th>{{ __('Hora de visita') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @if ($role == 'receptionist')
                                        @foreach ($appointments as $appointment)
                                            <tr>
                                                <td> {{ $i }} </td>
                                                <td>{{ $appointment->patient->first_name . ' ' . $appointment->patient->last_name }}
                                                </td>
                                                <td>{{ @$appointment->doctor->user->first_name . ' ' . @$appointment->doctor->user->last_name }}
                                                </td>
                                                <td>{{ $appointment->patient->mobile }}</td>
                                                <td>
                                                    {{ $appointment->timeSlot->from . ' to ' . $appointment->timeSlot->to }}
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @elseif ($role == 'doctor')
                                        @foreach ($appointments as $appointment)
                                            <tr>
                                                <td> {{ $i }} </td>
                                                <td>{{ $appointment->patient->first_name . ' ' . $appointment->patient->last_name }}
                                                </td>
                                                <td>{{ $appointment->patient->mobile }}</td>
                                                <td>
                                                    {{ $appointment->timeSlot->from . ' to ' . $appointment->timeSlot->to }}
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @elseif ($role == 'patient')
                                        @foreach ($appointments as $appointment)
                                            <tr>
                                                <td> {{ $i }} </td>
                                                <td>{{ @$appointment->doctor->user->first_name . ' ' . @$appointment->doctor->user->last_name }}
                                                </td>
                                                <td>{{ @$appointment->doctor->user->mobile }}</td>
                                                <td>
                                                    {{ $appointment->timeSlot->from . ' to ' . $appointment->timeSlot->to }}
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div id="new_list" style="display : none"></div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    @endsection
    @section('script')
        <!-- Calender Js-->
        <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/moment/moment.js') }}"></script>
        <script src="{{ URL::asset('build/libs/fullcalendar/index.global.min.js') }}"></script>
        <!-- Get App url in Javascript file -->
        <script type="text/javascript">
            var aplist_url = "{{ url('appointmentList') }}";
        </script>
        <!-- Init js-->
        <script src="{{ URL::asset('build/js/pages/calendar-init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/appointment.js') }}"></script>
    @endsection
