@extends('layouts.master-layouts')
@section('title') {{ __('Programar Cita') }} @endsection

@section('css')
    <!-- Calender -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/fullcalendar/fullcalendar.min.css') }}">

    <style>
        /* ── Premium Cards ── */
        .premium-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            transition: box-shadow 0.3s;
        }
        .premium-card:hover { box-shadow: 0 4px 24px rgba(0,0,0,.1); }
        .premium-header {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .premium-header i { font-size: 1.3rem; }
        .premium-header h6 { margin: 0; font-weight: 700; font-size: 0.95rem; letter-spacing: 0.02em; }

        /* ── FullCalendar v6 Premium overrides ── */
        .fc {
            --fc-border-color: #f0f2f5;
            --fc-today-bg-color: rgba(26,115,232,.04);
            --fc-page-bg-color: #fff;
        }
        .fc .fc-toolbar-title {
            font-size: 1.05rem !important;
            font-weight: 700 !important;
            text-transform: capitalize;
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.78rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .fc .fc-daygrid-day-number {
            font-size: 0.85rem;
            font-weight: 500;
            color: #344054;
        }
        .fc .fc-button-primary {
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            color: #344054 !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            padding: 4px 14px !important;
            border-radius: 6px !important;
            transition: all 0.2s;
        }
        .fc .fc-button-primary:hover {
            background: #f0f4ff !important;
            border-color: #1a73e8 !important;
            color: #1a73e8 !important;
        }
        .fc .fc-button-primary.fc-button-active,
        .fc .fc-button-primary:active {
            background: linear-gradient(135deg,#1a73e8,#0d47a1) !important;
            color: #fff !important;
            border-color: #1a73e8 !important;
            box-shadow: none !important;
        }
        .fc .fc-daygrid-event {
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-size: 0.75rem !important;
            border: none !important;
            font-weight: 600;
        }
        .fc .fc-day-today {
            background: rgba(26,115,232,.06) !important;
        }
        .fc .fc-day-today .fc-daygrid-day-number {
            background: #1a73e8;
            color: #fff;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .fc .fc-daygrid-day:hover {
            background: rgba(26,115,232,.03);
            cursor: pointer;
        }
        .fc .fc-scrollgrid {
            border-radius: 8px;
            overflow: hidden;
        }
        .fc th { border: none !important; }
        .fc .fc-timegrid-slot { height: 3rem; }
        .fc .fc-daygrid-day-frame { min-height: 80px; }
        /* Número de semana */
        .fc .fc-daygrid-week-number {
            font-size: 0.7rem;
            font-weight: 600;
            color: #adb5bd;
            border-radius: 4px;
            padding: 2px 6px;
        }

        /* ── Appointment list items ── */
        .apt-list-item {
            padding: 14px 20px;
            border-bottom: 1px solid #f0f2f5;
            transition: background 0.2s;
        }
        .apt-list-item:hover { background: #f8f9ff; }
        .apt-list-item:last-child { border-bottom: none; }

        .apt-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* ── Stats cards ── */
        .stat-mini-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
        }
        .stat-mini-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* ── Empty state ── */
        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }
        .empty-state i { font-size: 48px; opacity: 0.4; }
    </style>
@endsection

@section('content')
    {{-- Encabezado --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">📅 Citas Programadas</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Citas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats rápidos + botón nueva cita --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card premium-card mb-0 h-100">
                <div class="card-body p-0">
                    <div class="stat-mini-card">
                        <div class="stat-mini-icon" style="background:rgba(26,115,232,.1);color:#1a73e8;">
                            <i class="bx bx-calendar-event"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.75rem;font-weight:600;">CITAS HOY</p>
                            <h4 class="mb-0 fw-bold">{{ $appointments->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card premium-card mb-0 h-100">
                <div class="card-body p-0">
                    <div class="stat-mini-card">
                        <div class="stat-mini-icon" style="background:rgba(40,167,69,.1);color:#28a745;">
                            <i class="bx bx-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.75rem;font-weight:600;">COMPLETADAS HOY</p>
                            <h4 class="mb-0 fw-bold text-success">{{ $appointments->where('status', 1)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card premium-card mb-0 h-100">
                <div class="card-body p-0">
                    <div class="stat-mini-card">
                        <div class="stat-mini-icon" style="background:rgba(253,126,20,.1);color:#fd7e14;">
                            <i class="bx bx-time-five"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0" style="font-size:0.75rem;font-weight:600;">PENDIENTES HOY</p>
                            <h4 class="mb-0 fw-bold text-warning">{{ $appointments->where('status', 0)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="d-flex align-items-center justify-content-center h-100">
                <a href="{{ url('/appointment-create') }}" class="btn btn-primary waves-effect w-100"
                   style="background:linear-gradient(135deg,#1a73e8,#0d47a1);border:none;border-radius:10px;padding:16px;font-weight:700;font-size:0.95rem;">
                    <i class="bx bx-plus-circle me-2 font-size-18"></i>Nueva Cita
                </a>
            </div>
        </div>
    </div>

    {{-- Content: Calendar + List --}}
    <div class="row g-4">
        {{-- Calendario --}}
        <div class="col-lg-7">
            <div class="card premium-card">
                <div class="premium-header" style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <i class="bx bx-calendar text-white"></i>
                    <h6 class="text-white">Calendario de Citas</h6>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>

        {{-- Lista de citas del día --}}
        <div class="col-lg-5">
            <div class="card premium-card">
                <div class="premium-header" style="background: linear-gradient(135deg,#28a745,#1e7e34);">
                    <i class="bx bx-list-check text-white"></i>
                    <h6 class="text-white">
                        Citas del día: <span id="selected_date" class="fw-normal opacity-75"><?php echo date('d M, Y'); ?></span>
                    </h6>
                </div>
                <div class="card-body p-0" id="appointment_list">
                    @php $i = 1; @endphp
                    @if ($appointments->count() > 0)
                        @if ($role == 'receptionist')
                            @foreach ($appointments as $appointment)
                                <div class="apt-list-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="apt-avatar me-3" style="background:rgba(26,115,232,.1);color:#1a73e8;">
                                                {{ strtoupper(substr($appointment->patient->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr($appointment->patient->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">
                                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                </p>
                                                <span class="text-muted" style="font-size:0.8rem;">
                                                    <i class="bx bx-user-circle me-1"></i>Dr. {{ @$appointment->doctor->user->first_name }} {{ @$appointment->doctor->user->last_name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3" style="font-size:0.85rem;">
                                                <i class="bx bx-time me-1"></i>{{ $appointment->timeSlot->from }} - {{ $appointment->timeSlot->to }}
                                            </span>
                                            <div class="mt-1">
                                                <a href="/prescription/create?patient_id={{ $appointment->patient_id }}&appointment_id={{ $appointment->id }}"
                                                   class="btn btn-sm btn-success waves-effect" title="Crear expediente">
                                                    <i class="bx bx-plus me-1"></i>Expediente
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif ($role == 'doctor')
                            @foreach ($appointments as $appointment)
                                <div class="apt-list-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="apt-avatar me-3" style="background:rgba(40,167,69,.1);color:#28a745;">
                                                {{ strtoupper(substr($appointment->patient->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr($appointment->patient->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">
                                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                </p>
                                                <span class="text-muted" style="font-size:0.8rem;">
                                                    <i class="bx bx-phone me-1"></i>{{ $appointment->patient->mobile ?? $appointment->patient->phone_primary ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3" style="font-size:0.85rem;">
                                                <i class="bx bx-time me-1"></i>{{ $appointment->timeSlot->from }} - {{ $appointment->timeSlot->to }}
                                            </span>
                                            <div class="mt-1">
                                                <a href="/prescription/create?patient_id={{ $appointment->patient_id }}&appointment_id={{ $appointment->id }}"
                                                   class="btn btn-sm btn-success waves-effect" title="Crear expediente">
                                                    <i class="bx bx-plus me-1"></i>Expediente
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif ($role == 'patient')
                            @foreach ($appointments as $appointment)
                                <div class="apt-list-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="apt-avatar me-3" style="background:rgba(111,66,193,.1);color:#6f42c1;">
                                                {{ strtoupper(substr(@$appointment->doctor->user->first_name ?? 'D', 0, 1)) }}{{ strtoupper(substr(@$appointment->doctor->user->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">
                                                    Dr. {{ @$appointment->doctor->user->first_name }} {{ @$appointment->doctor->user->last_name }}
                                                </p>
                                                <span class="text-muted" style="font-size:0.8rem;">
                                                    <i class="bx bx-phone me-1"></i>{{ @$appointment->doctor->user->mobile ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="badge bg-info-subtle text-info rounded-pill px-3" style="font-size:0.85rem;">
                                            <i class="bx bx-time me-1"></i>{{ $appointment->timeSlot->from }} - {{ $appointment->timeSlot->to }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="bx bx-calendar-check text-primary d-block mb-3"></i>
                            <p class="text-muted fw-medium mb-1">Sin citas para hoy</p>
                            <p class="text-muted small mb-3">Selecciona un día en el calendario o programa una nueva</p>
                            <a href="{{ url('/appointment-create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i>Nueva Cita
                            </a>
                        </div>
                    @endif
                </div>
                <div id="new_list" class="card-body p-0" style="display:none"></div>
            </div>

            {{-- Navegación rápida de citas --}}
            <div class="card premium-card mt-4">
                <div class="premium-header" style="background: linear-gradient(135deg,#fd7e14,#e55a00);">
                    <i class="bx bx-navigation text-white"></i>
                    <h6 class="text-white">Acceso Rápido</h6>
                </div>
                <div class="card-body p-0">
                    <a href="{{ url('today-appointment') }}" class="apt-list-item d-flex align-items-center text-decoration-none">
                        <div class="apt-avatar me-3" style="background:rgba(26,115,232,.1);color:#1a73e8;">
                            <i class="bx bx-calendar-event"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">Citas de Hoy</p>
                        </div>
                        <i class="bx bx-chevron-right text-muted font-size-18"></i>
                    </a>
                    <a href="{{ url('pending-appointment') }}" class="apt-list-item d-flex align-items-center text-decoration-none">
                        <div class="apt-avatar me-3" style="background:rgba(253,126,20,.1);color:#fd7e14;">
                            <i class="bx bx-time-five"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">Pendientes</p>
                        </div>
                        <i class="bx bx-chevron-right text-muted font-size-18"></i>
                    </a>
                    <a href="{{ url('upcoming-appointment') }}" class="apt-list-item d-flex align-items-center text-decoration-none">
                        <div class="apt-avatar me-3" style="background:rgba(23,162,184,.1);color:#17a2b8;">
                            <i class="bx bx-calendar-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">Próximas</p>
                        </div>
                        <i class="bx bx-chevron-right text-muted font-size-18"></i>
                    </a>
                    <a href="{{ url('complete-appointment') }}" class="apt-list-item d-flex align-items-center text-decoration-none">
                        <div class="apt-avatar me-3" style="background:rgba(40,167,69,.1);color:#28a745;">
                            <i class="bx bx-check-double"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">Completadas</p>
                        </div>
                        <i class="bx bx-chevron-right text-muted font-size-18"></i>
                    </a>
                    <a href="{{ url('cancel-appointment') }}" class="apt-list-item d-flex align-items-center text-decoration-none">
                        <div class="apt-avatar me-3" style="background:rgba(220,53,69,.1);color:#dc3545;">
                            <i class="bx bx-x-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">Canceladas</p>
                        </div>
                        <i class="bx bx-chevron-right text-muted font-size-18"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
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
    <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/appointment.js') }}"></script>
    <script>
        // Prevenir que calendar-init.js se ejecute (ya no lo cargamos)
    </script>
    <script>
        // Override calendar-init.js with premium version inline
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            var esLocale = {
                code: 'es',
                week: { dow: 1, doy: 4 },
                buttonText: {
                    prev: 'Ant', next: 'Sig', today: 'Hoy',
                    year: 'Año', month: 'Mes', week: 'Semana', day: 'Día', list: 'Lista'
                },
                weekText: 'Sm',
                allDayText: 'Todo el día',
                moreLinkText: 'más',
                noEventsText: 'No hay citas para mostrar'
            };

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: esLocale,
                editable: false,
                droppable: false,
                selectable: true,
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap',
                weekNumbers: true,
                firstDay: 1,
                headerToolbar: {
                    left: 'prev,next today',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
                    center: 'title',
                },
                buttonText: {
                    today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día', list: 'Lista'
                },
                longPressDelay: 1,
                dayMaxEventRows: true,
                views: { timeGrid: { dayMaxEventRows: 5 } },
                select: function(date) {
                    var start = date.start;
                    var dt = moment(start).format('YYYY-MM-DD');
                    var displayDate = moment(start).locale('es').format('dddd D [de] MMMM [de] YYYY');
                    displayDate = displayDate.charAt(0).toUpperCase() + displayDate.slice(1);
                    $('#selected_date').html(displayDate);
                    $('#appointment_list').hide();
                    $('#new_list').show();
                    loadPremiumList(dt);
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    var eventDate = moment(info.event.start).format('YYYY-MM-DD');
                    var displayDate = moment(info.event.start).locale('es').format('dddd D [de] MMMM [de] YYYY');
                    displayDate = displayDate.charAt(0).toUpperCase() + displayDate.slice(1);
                    $('#selected_date').html(displayDate);
                    $('#appointment_list').hide();
                    $('#new_list').show();
                    loadPremiumList(eventDate);
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    var start = moment(fetchInfo.start).format('YYYY-MM-DD');
                    var end = moment(fetchInfo.end).format('YYYY-MM-DD');
                    $.ajax({
                        type: "get",
                        url: "/cal-appointment-show",
                        data: { start: start, end: end, title: 'appointment' },
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(response) {
                            var appEvents = [];
                            if (response.appointments && response.appointments.length > 0) {
                                $.each(response.appointments, function(key, value) {
                                    var badge = value.total_appointment == 1 ? value.total_appointment + ' Cita' : value.total_appointment + ' Citas';
                                    var color = value.total_appointment > 2 ? '#dc3545' : '#1a73e8';
                                    appEvents.push({
                                        title: badge,
                                        start: value.appointment_date,
                                        end: value.appointment_date,
                                        className: 'text-white',
                                        backgroundColor: color,
                                        borderColor: color,
                                    });
                                });
                            }
                            successCallback(appEvents);
                        },
                        error: function(response) {
                            console.log(response);
                            failureCallback(response);
                        }
                    });
                },
            });
            calendar.render();

            // ── Premium list builder ──
            function loadPremiumList(dateStr) {
                $.ajax({
                    method: 'get',
                    url: aplist_url,
                    data: { date: dateStr },
                    dataType: 'json',
                    success: function(response) {
                        var $container = $('#new_list');
                        $container.empty();

                        if (response.status == 'error' || !response.appointments || response.appointments.length === 0) {
                            $container.html(
                                '<div class="empty-state">' +
                                    '<i class="bx bx-calendar-x text-muted d-block mb-2"></i>' +
                                    '<p class="text-muted small mb-0">Sin citas para este día</p>' +
                                '</div>'
                            );
                            return;
                        }

                        var data = response.appointments;
                        var role = response.role;
                        
                        // ── Agrupación visual de citas consecutivas ──
                        var groupedAppointments = [];
                        $.each(data, function(i, apt) {
                            if (!apt.time_slot) return;
                            
                            var lastApt = groupedAppointments.length > 0 ? groupedAppointments[groupedAppointments.length - 1] : null;
                            var canGroup = false;

                            if (lastApt && lastApt.time_slot) {
                                var samePatient = (apt.patient_id == lastApt.patient_id);
                                var sameDoctor = (apt.appointment_with == lastApt.appointment_with);
                                // Normalizar horas eliminando espacios extras
                                var lastTo = lastApt.time_slot.to.trim();
                                var currentFrom = apt.time_slot.from.trim();
                                
                                if (samePatient && sameDoctor && lastTo === currentFrom) {
                                    canGroup = true;
                                }
                            }

                            if (canGroup) {
                                // Extender la cita existente visualmente
                                lastApt.time_slot.to = apt.time_slot.to;
                            } else {
                                // Clonar objeto para seguridad (deep copy simple)
                                var newApt = JSON.parse(JSON.stringify(apt));
                                groupedAppointments.push(newApt);
                            }
                        });

                        $.each(groupedAppointments, function(i, apt) {
                            var name = '', phone = '', doctorName = '', initials = '', showDoctor = false;
                            var patientId = '', appointmentId = apt.id;

                            if (role == 'patient') {
                                name = (apt.doctor && apt.doctor.user ? 'Dr. ' + apt.doctor.user.first_name + ' ' + apt.doctor.user.last_name : '—');
                                phone = (apt.doctor && apt.doctor.user ? apt.doctor.user.mobile || 'N/A' : 'N/A');
                                initials = name.replace('Dr. ', '').split(' ').map(function(n){return n.charAt(0)}).join('').toUpperCase().substring(0,2);
                            } else if (role == 'doctor') {
                                name = (apt.patient ? apt.patient.first_name + ' ' + apt.patient.last_name : '—');
                                phone = (apt.patient ? apt.patient.mobile || apt.patient.phone_primary || 'N/A' : 'N/A');
                                initials = name.split(' ').map(function(n){return n.charAt(0)}).join('').toUpperCase().substring(0,2);
                                patientId = apt.patient_id;
                            } else {
                                name = (apt.patient ? apt.patient.first_name + ' ' + apt.patient.last_name : '—');
                                doctorName = (apt.doctor && apt.doctor.user ? 'Dr. ' + apt.doctor.user.first_name + ' ' + apt.doctor.user.last_name : '');
                                phone = (apt.patient ? apt.patient.mobile || apt.patient.phone_primary || 'N/A' : 'N/A');
                                initials = name.split(' ').map(function(n){return n.charAt(0)}).join('').toUpperCase().substring(0,2);
                                showDoctor = true;
                                patientId = apt.patient_id;
                            }

                            var timeText = (apt.time_slot ? apt.time_slot.from + ' - ' + apt.time_slot.to : '—');
                            var actionBtn = '';
                            if (role !== 'patient' && patientId) {
                                actionBtn = '<a href="/prescription/create?patient_id=' + patientId + '&appointment_id=' + appointmentId + '" class="btn btn-sm btn-success waves-effect mt-1" title="Expediente"><i class="bx bx-plus me-1"></i>Expediente</a>';
                            }

                            $container.append(
                                '<div class="apt-list-item">' +
                                    '<div class="d-flex align-items-center justify-content-between">' +
                                        '<div class="d-flex align-items-center">' +
                                            '<div class="apt-avatar me-3" style="background:rgba(26,115,232,.1);color:#1a73e8;">' + initials + '</div>' +
                                            '<div>' +
                                                '<p class="mb-0 fw-semibold text-dark" style="font-size:0.9rem;">' + name + '</p>' +
                                                (showDoctor ? '<span class="text-muted" style="font-size:0.78rem;"><i class="bx bx-user-circle me-1"></i>' + doctorName + '</span><br>' : '') +
                                                '<span class="text-muted" style="font-size:0.78rem;"><i class="bx bx-phone me-1"></i>' + phone + '</span>' +
                                            '</div>' +
                                        '</div>' +
                                        '<div class="text-end">' +
                                            '<span class="badge bg-primary-subtle text-primary rounded-pill px-3" style="font-size:0.85rem;"><i class="bx bx-time me-1"></i>' + timeText + '</span>' +
                                            actionBtn +
                                        '</div>' +
                                    '</div>' +
                                '</div>'
                            );
                        });
                    },
                    error: function() {
                        $('#new_list').html(
                            '<div class="empty-state"><p class="text-muted small">Error al cargar citas</p></div>'
                        );
                    }
                });
            }
        });
    </script>
@endsection
