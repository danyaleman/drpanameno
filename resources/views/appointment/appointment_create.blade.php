@extends('layouts.master-layouts')
@section('title')
    {{ __('Nueva Cita') }}
@endsection
@section('css')
    <!-- Calender -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ URL::asset('build/libs/bootstrap-timepicker/bootstrap-timepicker.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/datatables/datatables.min.css') }}">
@endsection
@section('content')
    <!-- start page title -->
    @component('components.breadcrumb')
        @slot('title')
            Nueva Cita
        @endslot
        @slot('li_1')
            Dashboard
        @endslot
        @slot('li_2')
            Lista de Citas
        @endslot
    @endcomponent
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <a href="{{ url('/appointment/create') }}" class="btn btn-primary text-white waves-effect waves-light mb-4">
                <i class="mdi mdi-arrow-left  font-size-16 align-middle me-2"></i> {{ __('Atras') }}
            </a>
        </div> <!-- end col -->
    </div> <!-- end row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>{{ __('Crear Cita') }}</blockquote>
                    <form action="{{ url('appointment-store') }}" id="" method="POST">
                        @csrf
                        @if ($role != 'Nombre de Paciente')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="control-label">{{ __('Paciente ') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('appointment_for') is-invalid @enderror"
                                        name="appointment_for" id="patient">
                                        <option hidden selected disabled>{{ __('Seleccionar') }}</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->first_name }}
                                                {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('appointment_for')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="appointment_for" value="{{ $user->id }}">
                        @endif
                        @if ($role != 'doctor')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="control-label">{{ __('Doctor ') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel-doctor @error('appointment_with') is-invalid @enderror"
                                        name="appointment_with" id="doctor">
                                        <option hidden selected disabled>{{ __('Seleccionar ') }}</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->doctor->id }}"
                                                {{ old('appointment_with') == $doctor->doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->first_name }}
                                                {{ $doctor->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('appointment_with')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="appointment_with" value="{{ @$user->doctor->id }}" id="doctor">
                        @endif
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="control-label">{{ __('Fecha ') }}<span class="text-danger">*</span></label>
                                <div class="input-group datepickerdiv">
                                    <input type="text"
                                        class="form-control appointment-date @error('appointment_date') is-invalid @enderror"
                                        name="appointment_date" id="datepicker" autocomplete="off"
                                        {{ old('appointment_date', date('Y-m-d')) }}>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                    @error('appointment_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @if ($role !== 'doctor')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="" class="d-block">{{ __('Horarios ') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="btn-group availble_time" role="group">


                                    </div>
                                    @error('available_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @if ($errors->has('available_time'))
                                        <div class="error " role="alert">
                                            {{ $errors->first('available_time') }}</div>
                                    @endif
                                </div>
                            </div>
                        @elseif ($role == 'doctor')
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="" class="d-block">{{ __('Horarios ') }} <span
                                            class="text-danger">*</span></label>
                                    <div class="btn-group availble_time" role="group">
                                        @foreach ($doctor_available_time as $item)
                                            <label class="btn btn-outline-secondary me-2">
                                                <input type="radio" name="available_time"
                                                    class="btn-check available-time @error('available_time') is-invalid @enderror"
                                                    value="{{ $item->id }}">
                                                {{ $item->from . ' to ' . $item->to }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('available_time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @if ($errors->has('available_time'))
                                        <div class="error " role="alert">
                                            {{ $errors->first('available_time') }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="" class="d-block">{{ __('Horarios Disponibles') }}<span
                                        class="text-danger">*</span></label>
                                <div class="btn-group availble_slot d-block" role="group">
                                    @error('available_slot')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @if ($errors->has('available_slot'))
                                        <div class="error " role="alert">
                                            {{ $errors->first('available_slot') }}</div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Crear Cita') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <!-- Calender Js-->
    <script src="{{ URL::asset('build/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/fullcalendar/fullcalendar.min.js') }}"></script>
    <!-- Get App url in Javascript file -->
    <script type="text/javascript">
        var aplist_url = "{{ url('appointmentList') }}";
    </script>
    <!-- Init js-->
    <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/appointment.js') }}"></script>
    <script>
       
        var roles = '{{ $role }}';
        let datep = $('#datepicker');
        
            datep.datepicker({
                startDate: new Date(),
                autoclose: true,
                todayHighlight: true,
             //   format: 'yyyy-mm-dd'
            });
        
        function days(disabledDays) {
           //$('#datepicker').datepicker('setDaysOfWeekDisabled', disabledDays);
           $('#datepicker').datepicker('destroy');

            $('#datepicker').datepicker({
                startDate: new Date(),
                daysOfWeekDisabled: [0, 6],
                autoclose: true,
                todayHighlight: true,

            });
        }
        $('.sel-doctor').change(function(e) {
            e.preventDefault(); 
            $('.availble_time').empty();
            var doctorId = $(this).val();
            var token = $("input[name='_token']").val();
            $.ajax({
                type: "post",
                url: "{{ route('doctor_by_day_time') }}",
                data: {
                    doctor_id: doctorId,
                    _token: token,
                },
                success: function(response) {
                    var res_data = response.data[0];
                    
                   
                    if (res_data !== null) {
                         let disabledDays = [];
                            if (res_data.sun == 0) disabledDays.push(0);
                            if (res_data.mon == 0) disabledDays.push(1);
                            if (res_data.tue == 0) disabledDays.push(2);
                            if (res_data.wen == 0) disabledDays.push(3);
                            if (res_data.thu == 0) disabledDays.push(4);
                            if (res_data.fri == 0) disabledDays.push(5);
                            if (res_data.sat == 0) disabledDays.push(6);
                        days(disabledDays);
                    }
                    var availble_time = response.data[1];
                    $.each(availble_time, function(key, value) {
                        $('.availble_time').append(
                            '<label class="btn btn-outline-secondary me-2 "><input type="radio" name="available_time" class="btn-check available-time @error('available_time') is-invalid @enderror" value="' +
                            value.id + '" >' + value.from + ' to ' + value.to + '</label>');
                    });
                    activeAvailableTime();
                },
                error: function(response) {}
            });
        });
        // datepicker change
        $(document).on('change', '#datepicker', function() {
            $('.availble_slot').empty();
        });
        // doctor available time show
        $(document).on('click', '.available-time', function() {
            $('.availble_slot').empty();
            var token = $("input[name='_token']").val();
            var timeId = $(this).val();
            var dates = $('#datepicker').val();
            var doctorId = $("#doctor").val();
            $.ajax({
                type: "post",
                url: "{{ route('timeBySlot') }}",
                data: {
                    timeId: timeId,
                    _token: token,
                    dates: dates,
                    doctorId: doctorId
                },
                success: function(response) {
                    var available_slot = response.data[0];
                    $.each(available_slot, function(key, value) {
                        if (value.appointment.length == 0) {
                            $('.availble_slot').append(
                                '<label class="btn btn-outline-secondary m-2"><input type="radio" name="available_slot" class="btn-check available-slot"  value="' +
                                value.id + '">' + value.from + ' to ' + value.to +
                                '</label>');
                        } else {
                            $('.availble_slot').append(
                                '<label class="btn alert-secondary m-2"><input type="radio" name="available_slot" class="btn-check available-slot"  value="' +
                                value.id + '" disabled>' + value.from + ' to ' + value.to +
                                '</label>');
                        }
                    });

                    // available slots activation
                    if ($(".availble_slot").length) {
                        $(".availble_slot label").click(function() {
                            var activeLabel = $(".availble_slot label.active");
                            if (activeLabel.length) {
                                activeLabel.removeClass("active");
                            }
                            $(this).addClass("active");
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                    toastr.error('Something went wrong!', {
                        timeOut: 10000
                    });
                }
            });
        });

        // available time activation
        function activeAvailableTime() {
            if ($(".availble_time").length) {
                $(".availble_time label").click(function() {
                    var activeLabel = $(".availble_time label.active");
                    if (activeLabel.length) {
                        activeLabel.removeClass("active");
                    }
                    $(this).addClass("active");
                });
            }
        }
        activeAvailableTime();
    </script>
@endsection
