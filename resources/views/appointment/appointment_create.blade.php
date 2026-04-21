@extends('layouts.master-layouts')
@section('title') {{ __('Nueva Cita') }} @endsection

@section('css')
    <!-- Calender -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/bootstrap-timepicker/bootstrap-timepicker.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/datatables/datatables.min.css') }}">

    <style>
        /* ── Premium Form Card ── */
        .premium-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            transition: box-shadow 0.3s;
        }
        .premium-card:hover {
            box-shadow: 0 4px 24px rgba(0,0,0,.1);
        }
        .premium-header {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .premium-header i { font-size: 1.3rem; }
        .premium-header h6 { margin: 0; font-weight: 700; font-size: 0.95rem; letter-spacing: 0.02em; }

        /* ── Form Controls ── */
        .form-label-premium {
            font-weight: 600;
            font-size: 0.85rem;
            color: #344054;
            margin-bottom: 6px;
        }
        .required-star { color: #dc3545; font-weight: 700; }

        .select2-container--default .select2-selection--single {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            height: 42px !important;
            padding: 6px 14px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 7px !important;
        }

        /* ── Datepicker Premium ── */
        .datepicker-premium .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 9px 14px 9px 40px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .datepicker-premium .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26,115,232,.12);
        }
        .datepicker-premium .datepicker-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.1rem;
            z-index: 5;
        }

        /* ── Time / Slot Buttons ── */
        .time-slot-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .time-slot-grid label {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
            background: #fff;
            color: #344054;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .time-slot-grid label:hover {
            border-color: #1a73e8;
            background: #f0f4ff;
            color: #1a73e8;
        }
        .time-slot-grid label.active {
            border-color: #1a73e8;
            background: linear-gradient(135deg,#1a73e8,#0d47a1);
            color: #fff;
            box-shadow: 0 2px 8px rgba(26,115,232,.3);
        }
        .time-slot-grid label.disabled-slot {
            border-color: #f0f0f0;
            background: #f8f9fa;
            color: #c0c0c0;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        /* ── Calendar Premium ── */
        .fc-toolbar { margin-bottom: 1rem !important; }
        .fc-toolbar h2 {
            font-size: 1rem !important;
            font-weight: 700 !important;
            text-transform: capitalize !important;
        }
        .fc-day-header {
            padding: 8px 0 !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            color: #6c757d !important;
            text-transform: uppercase;
        }
        .fc-day-number {
            font-size: 0.85rem !important;
            font-weight: 500 !important;
        }
        .fc-today {
            background: rgba(26,115,232,.06) !important;
        }
        .fc-event {
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 0.75rem !important;
            border: none !important;
        }
        .fc td, .fc th {
            border-color: #f0f2f5 !important;
        }
        .fc-button {
            border-radius: 6px !important;
            font-size: 0.8rem !important;
            padding: 4px 12px !important;
        }
        .fc-state-default {
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            color: #344054 !important;
        }
        .fc-state-active {
            background: linear-gradient(135deg,#1a73e8,#0d47a1) !important;
            color: #fff !important;
            border-color: #1a73e8 !important;
        }

        /* ── Appointment List in Calendar ── */
        .appointment-list-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f2f5;
            transition: background 0.2s;
        }
        .appointment-list-item:hover {
            background: #f8f9ff;
        }
        .appointment-list-item:last-child {
            border-bottom: none;
        }

        /* ── Submit Button ── */
        .btn-create-appointment {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            border: none;
            padding: 12px 32px;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 8px;
            letter-spacing: 0.02em;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-create-appointment:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(26,115,232,.35);
        }

        /* ── Steps Indicator ── */
        .steps-indicator {
            display: flex;
            gap: 0;
            margin-bottom: 24px;
        }
        .step-item {
            flex: 1;
            text-align: center;
            padding: 12px 8px;
            position: relative;
            font-size: 0.8rem;
            font-weight: 600;
            color: #adb5bd;
            transition: all 0.3s;
        }
        .step-item.active {
            color: #1a73e8;
        }
        .step-item.completed {
            color: #28a745;
        }
        .step-item .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e9ecef;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 6px;
            transition: all 0.3s;
        }
        .step-item.active .step-number {
            background: linear-gradient(135deg,#1a73e8,#0d47a1);
            color: #fff;
        }
        .step-item.completed .step-number {
            background: #28a745;
            color: #fff;
        }
        .step-item::after {
            content: '';
            position: absolute;
            top: 28px;
            right: -2px;
            width: calc(100% - 40px);
            height: 2px;
            background: #e9ecef;
        }
        .step-item:last-child::after { display: none; }
        .step-item.completed::after { background: #28a745; }

        /* ── Quick Stats ── */
        .quick-stat {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 10px;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .quick-stat:hover { background: #f0f4ff; }
        .quick-stat .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* ── Datepicker override ── */
        .datepicker { z-index: 1060 !important; }
    </style>
@endsection

@section('content')
{{-- Encabezado --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📅 Programar Nueva Cita</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/pending-appointment') }}">Citas</a></li>
                    <li class="breadcrumb-item active">Nueva Cita</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- ═══ COLUMNA IZQUIERDA: Formulario ═══ --}}
    <div class="col-lg-7">

        {{-- Indicador de pasos --}}
        <div class="card premium-card mb-4">
            <div class="card-body py-3">
                <div class="steps-indicator">
                    <div class="step-item active" id="step1-indicator">
                        <div class="step-number">1</div>
                        <div>Paciente y Doctor</div>
                    </div>
                    <div class="step-item" id="step2-indicator">
                        <div class="step-number">2</div>
                        <div>Fecha</div>
                    </div>
                    <div class="step-item" id="step3-indicator">
                        <div class="step-number">3</div>
                        <div>Horario</div>
                    </div>
                    <div class="step-item" id="step4-indicator">
                        <div class="step-number">4</div>
                        <div>Confirmar</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulario principal --}}
        <div class="card premium-card">
            <div class="premium-header" style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
                <i class="bx bx-calendar-plus text-white"></i>
                <h6 class="text-white">Datos de la Cita</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ url('appointment-store') }}" method="POST">
                    @csrf

                    {{-- Tipo de Cita --}}
                    <div class="mb-4">
                        <label class="form-label-premium">
                            <i class="bx bx-laptop text-primary me-1"></i>Tipo de Cita
                            <span class="required-star">*</span>
                        </label>
                        <select class="form-select" name="is_telemedicine" id="is_telemedicine">
                            <option value="0" {{ old('is_telemedicine') == '0' ? 'selected' : '' }}>Presencial</option>
                            <option value="1" {{ old('is_telemedicine') == '1' ? 'selected' : '' }}>Telemedicina (Videollamada)</option>
                        </select>
                        @error('is_telemedicine')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Paciente --}}
                    @if ($role != 'Nombre de Paciente')
                        <div class="mb-4">
                            <label class="form-label-premium">
                                <i class="bx bx-user text-primary me-1"></i>Paciente
                                <span class="required-star">*</span>
                            </label>
                            <select class="form-control select2 @error('patient_id') is-invalid @enderror"
                                name="patient_id" id="patient">
                                <option hidden selected disabled>Buscar paciente...</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="patient_id" value="{{ $user->id }}">
                    @endif

                    {{-- Doctor --}}
                    @if ($role != 'doctor')
                        <div class="mb-4">
                            <label class="form-label-premium">
                                <i class="bx bx-user-circle text-success me-1"></i>Doctor
                                <span class="required-star">*</span>
                            </label>
                            <select class="form-control select2 sel-doctor @error('appointment_with') is-invalid @enderror"
                                name="appointment_with" id="doctor">
                                <option value="">Seleccionar doctor...</option>
                                @foreach ($doctors as $doctor)
                                    @if($doctor->user)
                                        <option value="{{ $doctor->id }}" {{ old('appointment_with') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->user->first_name }} {{ $doctor->user->last_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('appointment_with')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="appointment_with" value="{{ @$user->doctor->id }}" id="doctor">
                    @endif

                    {{-- Fecha --}}
                    <div class="mb-4">
                        <label class="form-label-premium">
                            <i class="bx bx-calendar text-info me-1"></i>Fecha de la cita
                            <span class="required-star">*</span>
                        </label>
                        <div class="datepicker-premium position-relative">
                            <i class="bx bx-calendar datepicker-icon"></i>
                            <input type="text"
                                class="form-control appointment-date @error('appointment_date') is-invalid @enderror"
                                name="appointment_date" id="datepicker" autocomplete="off"
                                placeholder="Seleccionar fecha..."
                                {{ old('appointment_date', date('Y-m-d')) }}>
                            @error('appointment_date')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    {{-- Horarios --}}
                    <div class="mb-4">
                        <label class="form-label-premium">
                            <i class="bx bx-time text-warning me-1"></i>Horarios disponibles
                            <span class="required-star">*</span>
                        </label>
                        @if ($role !== 'doctor')
                            <div class="time-slot-grid availble_time" role="group">
                                {{-- Se carga dinámicamente al elegir doctor --}}
                            </div>
                        @else
                            <div class="time-slot-grid availble_time" role="group">
                                @foreach ($doctor_available_time as $item)
                                    <label class="time-btn">
                                        <input type="radio" name="available_time"
                                            class="btn-check available-time @error('available_time') is-invalid @enderror"
                                            value="{{ $item->id }}">
                                        <i class="bx bx-time-five"></i>
                                        {{ $item->from }} - {{ $item->to }}
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        @error('available_time')
                            <span class="text-danger small d-block mt-1"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Slots (selección múltiple) --}}
                    <div class="mb-4">
                        <label class="form-label-premium">
                            <i class="bx bx-grid-alt text-danger me-1"></i>Franjas horarias
                            <span class="required-star">*</span>
                        </label>
                        <p class="text-muted small mb-2" style="margin-top:-4px;">
                            <i class="bx bx-info-circle me-1"></i>Puedes seleccionar <strong>múltiples franjas</strong> consecutivas para citas más largas (cada franja = 30 min)
                        </p>
                        <div class="time-slot-grid availble_slot" role="group">
                            {{-- Se carga dinámicamente --}}
                        </div>
                        <div id="slot-placeholder" class="text-center py-3">
                            <i class="bx bx-info-circle text-muted font-size-20"></i>
                            <p class="text-muted small mb-0 mt-1">Selecciona un doctor, fecha y horario para ver las franjas disponibles</p>
                        </div>
                        @error('available_slot')
                            <span class="text-danger small d-block mt-1"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    {{-- Resumen de franjas seleccionadas --}}
                    <div class="mb-4" id="slots-summary" style="display:none;">
                        <div class="card border-0" style="background:linear-gradient(135deg,rgba(26,115,232,0.06),rgba(13,71,161,0.06));border-radius:12px;">
                            <div class="card-body py-3 px-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bx bx-calendar-check text-primary font-size-18 me-2"></i>
                                    <span class="fw-bold text-dark" style="font-size:0.9rem;">Resumen de reserva</span>
                                </div>
                                <div id="slots-summary-list" class="d-flex flex-wrap gap-2 mb-2"></div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted small">Franjas seleccionadas: <strong id="slots-count" class="text-primary">0</strong></span>
                                    <span class="badge bg-primary rounded-pill px-3" style="font-size:0.8rem;" id="slots-duration">0 min</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botón Crear --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-create-appointment waves-effect">
                            <i class="bx bx-calendar-check me-2"></i>Confirmar y Crear Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ COLUMNA DERECHA: Calendario + Info ═══ --}}
    <div class="col-lg-5">

        {{-- Mini Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="card premium-card mb-0">
                    <div class="card-body p-3">
                        <div class="quick-stat">
                            <div class="stat-icon" style="background:rgba(26,115,232,.1);color:#1a73e8;">
                                <i class="bx bx-calendar-event"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size:0.75rem;font-weight:600;">HOY</p>
                                <h5 class="mb-0 fw-bold" id="stat-today">—</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card premium-card mb-0">
                    <div class="card-body p-3">
                        <div class="quick-stat">
                            <div class="stat-icon" style="background:rgba(253,126,20,.1);color:#fd7e14;">
                                <i class="bx bx-time-five"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size:0.75rem;font-weight:600;">PENDIENTES</p>
                                <h5 class="mb-0 fw-bold" id="stat-pending">—</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Calendario --}}
        <div class="card premium-card mb-4">
            <div class="premium-header" style="background: linear-gradient(135deg,#28a745,#1e7e34);">
                <i class="bx bx-calendar text-white"></i>
                <h6 class="text-white">Calendario de Citas</h6>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

        {{-- Lista de citas del día seleccionado --}}
        <div class="card premium-card">
            <div class="premium-header" style="background: linear-gradient(135deg,#fd7e14,#e55a00);">
                <i class="bx bx-list-check text-white"></i>
                <h6 class="text-white">Citas del día: <span id="selected-date-label" class="fw-normal opacity-75">Hoy</span></h6>
            </div>
            <div class="card-body p-0" id="day-appointments-list">
                <div class="text-center py-4">
                    <i class="bx bx-calendar-alt font-size-40 text-muted opacity-50 d-block mb-2"></i>
                    <p class="text-muted small mb-0">Haz clic en un día del calendario para ver las citas</p>
                </div>
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
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/fullcalendar/fullcalendar.min.js') }}"></script>
    <!-- Get App url in Javascript file -->
    <script type="text/javascript">
        var aplist_url = "{{ url('appointmentList') }}";
    </script>
    <!-- Init js-->
    <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/appointment.js') }}"></script>
    <script>
        // ── Español ──
        moment.locale('es');

        (function( factory ) {
            if ( typeof define === "function" && define.amd ) {
                define( [ "../widgets/datepicker" ], factory );
            } else {
                if(jQuery.datepicker){
                    factory( jQuery.datepicker );
                }
            }
        }(function( datepicker ) {
            datepicker.regional.es = {
                closeText: "Cerrar",
                prevText: "&#x3C;Ant",
                nextText: "Sig&#x3E;",
                currentText: "Hoy",
                monthNames: [ "Enero","Febrero","Marzo","Abril","Mayo","Junio",
                "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre" ],
                monthNamesShort: [ "ene","feb","mar","abr","may","jun",
                "jul","ago","sep","oct","nov","dic" ],
                dayNames: [ "Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado" ],
                dayNamesShort: [ "dom","lun","mar","mié","jue","vie","sáb" ],
                dayNamesMin: [ "D","L","M","X","J","V","S" ],
                weekHeader: "Sm",
                dateFormat: "dd/mm/yy",
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: "" };
            datepicker.setDefaults( datepicker.regional.es );
            return datepicker.regional.es;
        }));

        (function($){
            $.fn.datepicker.dates['es'] = {
                days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                today: "Hoy",
                monthsTitle: "Meses",
                clear: "Borrar",
                weekStart: 1,
                format: "dd/mm/yyyy"
            };
        }(jQuery));

        var roles = '{{ $role }}';
        let datep = $('#datepicker');

        datep.datepicker({
            startDate: new Date(),
            autoclose: true,
            todayHighlight: true,
            language: 'es',
        });

        function days(disabledDays) {
           $('#datepicker').datepicker('destroy');
            $('#datepicker').datepicker({
                startDate: new Date(),
                daysOfWeekDisabled: [0, 6],
                autoclose: true,
                todayHighlight: true,
                language: 'es',
            });
        }

        // ── Doctor change → load times ──
        $('.sel-doctor').change(function(e) {
            e.preventDefault();
            $('.availble_time').empty();
            updateSteps('doctor');
            var doctorId = $(this).val();
            var token = $("input[name='_token']").val();
            $.ajax({
                type: "post",
                url: "{{ route('doctor_by_day_time') }}",
                data: { doctor_id: doctorId, _token: token },
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
                            '<label><input type="radio" name="available_time" class="btn-check available-time" value="' +
                            value.id + '"><i class="bx bx-time-five"></i> ' + value.from + ' - ' + value.to + '</label>');
                    });
                    activeAvailableTime();
                },
                error: function(response) {}
            });
        });

        // ── Datepicker change ──
        $(document).on('change', '#datepicker', function() {
            $('.availble_slot').empty();
            $('#slot-placeholder').show();
            updateSteps('date');
        });

        // ── Available time click → load slots ──
        $(document).on('click', '.available-time', function() {
            $('.availble_slot').empty();
            $('#slot-placeholder').hide();
            $('#slots-summary').hide();
            updateSteps('time');
            var token = $("input[name='_token']").val();
            var timeId = $(this).val();
            var dates = $('#datepicker').val();
            var doctorId = $("#doctor").val();
            $.ajax({
                type: "post",
                url: "{{ route('timeBySlot') }}",
                data: { timeId: timeId, _token: token, dates: dates, doctorId: doctorId },
                success: function(response) {
                    var available_slot = response.data[0];
                    $.each(available_slot, function(key, value) {
                        if (value.appointment.length == 0) {
                            $('.availble_slot').append(
                                '<label><input type="checkbox" name="available_slot[]" class="btn-check available-slot" value="' +
                                value.id + '" data-from="' + value.from + '" data-to="' + value.to + '"><i class="bx bx-check-circle"></i> ' + value.from + ' - ' + value.to + '</label>');
                        } else {
                            $('.availble_slot').append(
                                '<label class="disabled-slot"><input type="checkbox" name="available_slot[]" class="btn-check available-slot" value="' +
                                value.id + '" disabled data-from="' + value.from + '" data-to="' + value.to + '"><i class="bx bx-x-circle"></i> ' + value.from + ' - ' + value.to + '</label>');
                        }
                    });

                    // Multi-slot activation
                    if ($(".availble_slot").length) {
                        $(".availble_slot label").off('click').on('click', function(e) {
                            // Don't intercept if they clicked directly on the actual input, prevents double trigger
                            if(e.target.tagName.toLowerCase() === 'input') {
                                return true; 
                            }
                            // Otherwise, it was a click on the label wrapper, so prevent default and handle manually
                            e.preventDefault();
                            
                            if ($(this).hasClass('disabled-slot')) return;
                            $(this).toggleClass('active');
                            var checkbox = $(this).find('input[type=checkbox]');
                            checkbox.prop('checked', $(this).hasClass('active'));
                            
                            updateSlotsSummary();
                            var checked = $('.availble_slot input[type=checkbox]:checked').length;
                            if (checked > 0) {
                                updateSteps('slot');
                            }
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                    toastr.error('¡Algo salió mal!', { timeOut: 10000 });
                }
            });
        });

        // ── Available time activation ──
        function activeAvailableTime() {
            if ($(".availble_time").length) {
                $(".availble_time label").click(function() {
                    $(".availble_time label.active").removeClass("active");
                    $(this).addClass("active");
                });
            }
        }
        activeAvailableTime();

        // ── Update slots summary panel ──
        function updateSlotsSummary() {
            var checked = $('.availble_slot input[type=checkbox]:checked');
            var count = checked.length;
            if (count === 0) {
                $('#slots-summary').slideUp(200);
                return;
            }
            var summaryHtml = '';
            checked.each(function() {
                var from = $(this).data('from');
                var to = $(this).data('to');
                summaryHtml += '<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2" style="font-size:0.82rem;">' +
                    '<i class="bx bx-time me-1"></i>' + from + ' - ' + to + '</span>';
            });
            $('#slots-summary-list').html(summaryHtml);
            $('#slots-count').text(count);
            $('#slots-duration').text((count * 30) + ' min');
            $('#slots-summary').slideDown(200);
        }

        // ── Steps indicator ──
        function updateSteps(step) {
            var steps = {
                'doctor': ['step1-indicator'],
                'date': ['step1-indicator', 'step2-indicator'],
                'time': ['step1-indicator', 'step2-indicator', 'step3-indicator'],
                'slot': ['step1-indicator', 'step2-indicator', 'step3-indicator', 'step4-indicator']
            };
            // Reset
            $('.step-item').removeClass('active completed');
            // Set completed and active
            var completedSteps = steps[step] || [];
            completedSteps.forEach(function(id, index) {
                if (index < completedSteps.length - 1) {
                    $('#' + id).addClass('completed');
                } else {
                    $('#' + id).addClass('active');
                }
            });
        }

        // ── Load today stats ──
        $.ajax({
            type: 'GET',
            url: aplist_url,
            data: { date: moment().format('YYYY/MM/DD') },
            success: function(response) {
                if (response.appointments) {
                    $('#stat-today').text(response.appointments.length);
                } else {
                    $('#stat-today').text('0');
                }
            },
            error: function() { $('#stat-today').text('0'); }
        });

        // Load pending count
        $.ajax({
            type: 'GET',
            url: '{{ url("pending-appointment") }}',
            success: function(data) {
                // Try counting from page
                var $html = $(data);
                var rows = $html.find('tbody tr').length;
                $('#stat-pending').text(rows > 0 ? rows : '0');
            },
            error: function() { $('#stat-pending').text('—'); }
        });

        // ── Calendar click → show day appointments ──
        function loadDayAppointments(dateStr, displayDate) {
            $('#selected-date-label').text(displayDate);
            $.ajax({
                type: 'GET',
                url: aplist_url,
                data: { date: dateStr },
                success: function(response) {
                    var $container = $('#day-appointments-list');
                    $container.empty();

                    if (response.appointments && response.appointments.length > 0) {
                        response.appointments.forEach(function(apt) {
                            var patientName = '—';
                            if (apt.patient) {
                                patientName = (apt.patient.first_name || '') + ' ' + (apt.patient.last_name || '');
                            }
                            var timeSlot = '—';
                            if (apt.time_slot) {
                                timeSlot = apt.time_slot.from + ' - ' + apt.time_slot.to;
                            }
                            var initials = patientName.split(' ').map(function(n) { return n.charAt(0); }).join('').toUpperCase().substring(0, 2);
                            var statusBadge = '';
                            if (apt.status == 0) {
                                statusBadge = '<span class="badge bg-warning-subtle text-warning rounded-pill px-2">Pendiente</span>';
                            } else if (apt.status == 1) {
                                statusBadge = '<span class="badge bg-success-subtle text-success rounded-pill px-2">Completada</span>';
                            } else {
                                statusBadge = '<span class="badge bg-danger-subtle text-danger rounded-pill px-2">Cancelada</span>';
                            }

                            $container.append(
                                '<div class="appointment-list-item">' +
                                    '<div class="d-flex align-items-center justify-content-between">' +
                                        '<div class="d-flex align-items-center">' +
                                            '<div class="avatar-sm me-3 flex-shrink-0">' +
                                                '<div class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold" style="width:36px;height:36px;font-size:0.8rem;">' +
                                                    initials +
                                                '</div>' +
                                            '</div>' +
                                            '<div>' +
                                                '<p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">' + patientName + '</p>' +
                                                '<span class="text-muted" style="font-size:0.8rem;"><i class="bx bx-time me-1"></i>' + timeSlot + '</span>' +
                                            '</div>' +
                                        '</div>' +
                                        '<div>' + statusBadge + '</div>' +
                                    '</div>' +
                                '</div>'
                            );
                        });
                    } else {
                        $container.html(
                            '<div class="text-center py-4">' +
                                '<i class="bx bx-calendar-x font-size-40 text-muted opacity-50 d-block mb-2"></i>' +
                                '<p class="text-muted small mb-0">Sin citas para este día</p>' +
                            '</div>'
                        );
                    }
                },
                error: function() {
                    $('#day-appointments-list').html(
                        '<div class="text-center py-3">' +
                            '<p class="text-muted small mb-0">Error al cargar citas</p>' +
                        '</div>'
                    );
                }
            });
        }

        // Load today on init
        loadDayAppointments(moment().format('YYYY/MM/DD'), 'Hoy');

        // ── FullCalendar ──
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                locale: 'es',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes'
                },
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                monthNamesShort: ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'],
                dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
                editable: false,
                droppable: false,
                dayClick: function(date, jsEvent, view) {
                    var dateStr = date.format('YYYY/MM/DD');
                    var displayDate = date.format('dddd D [de] MMMM');
                    displayDate = displayDate.charAt(0).toUpperCase() + displayDate.slice(1);
                    loadDayAppointments(dateStr, displayDate);
                },
                events: function(start, end, timezone, callback) {
                    // Una sola petición eficiente con el rango completo del mes visible
                    $.ajax({
                        type: 'GET',
                        url: '/cal-appointment-show',
                        data: {
                            start: start.format('YYYY-MM-DD'),
                            end: end.format('YYYY-MM-DD'),
                            title: 'appointment'
                        },
                        success: function(response) {
                            var appEvents = [];
                            if (response.appointments) {
                                $(response.appointments).each(function(key, value) {
                                    var badge = value.total_appointment == 1
                                        ? value.total_appointment + ' Cita'
                                        : value.total_appointment + ' Citas';
                                    appEvents.push({
                                        title: badge,
                                        start: value.appointment_date,
                                        end: value.appointment_date,
                                        color: value.total_appointment > 2 ? '#dc3545' : '#1a73e8',
                                        textColor: '#fff'
                                    });
                                });
                            }
                            callback(appEvents);
                        },
                        error: function(response) {
                            console.log('Error cargando citas del calendario:', response);
                            callback([]);
                        }
                    });
                }
            });
        });
    </script>
@endsection
