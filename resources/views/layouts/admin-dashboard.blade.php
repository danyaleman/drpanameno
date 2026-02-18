<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">{{ __('translation.dashboards') }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ __('translation.welcome-to-dashboard') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">{{ __('translation.welcome-back') }} !</h5>
                            <p>{{ __('translation.dashboards') }}</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="{{ URL::asset('build/images/profile-img.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="@if ($user->profile_photo != ''){{ URL::asset('storage/images/users/' . $user->profile_photo) }}@else{{ URL::asset('build/images/users/noImage.png') }}@endif" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text-truncate"> {{ $user->first_name }} {{ $user->last_name }} </h5>
                        <p class="text-muted mb-0 text-truncate">{{ __('Super Admin') }}</p>
                    </div>
                    <div class="col-sm-8">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ url('/doctor') }}" class="mb-0 fw-medium font-size-15">
                                        <h5 class="mb-0">{{ number_format($data['total_doctors']) }}</h5>
                                    </a>
                                    <p class="text-muted mb-0">{{ __('translation.doctors') }}</p>
                                </div>
                                <div class="col-6">
                                    <a href="{{ url('/patient') }}" class="mb-0 fw-medium font-size-15">
                                        <h5 class="mb-0">{{ number_format($data['total_patients']) }}</h5>
                                    </a>
                                    <p class="text-muted mb-0">{{ __('translation.patients') }}</p>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <a href="{{ url('/receptionist') }}"
                                        class="mb-0 fw-medium font-size-15">
                                        <h5 class="mb-0">{{ number_format($data['total_receptionists']) }}
                                        </h5>
                                    </a>
                                    <p class="text-muted mb-0">{{ __('translation.receptionist') }}</p>
                                </div>
                                <div class="col-6">
                                    <a href="{{ url('/accountant') }}"
                                        class="mb-0 fw-medium font-size-15">
                                        <h5 class="mb-0">{{ number_format($data['total_accountants']) }}
                                        </h5>
                                    </a>
                                    <p class="text-muted mb-0">{{ __('translation.accountant') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ __('translation.monthly-earning') }}</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="text-muted">{{ __('This month') }}</p>
                        <h3>${{ number_format($data['monthly_earning']) }}</h3>
                        <p class="text-muted">
                            <span class="@if ($data['monthly_diff'] > 0) text-success @else text-danger @endif me-2">
                                {{ $data['monthly_diff'] }}% <i class="mdi @if ($data['monthly_diff'] > 0) mdi-arrow-up @else mdi-arrow-down @endif"></i>
                            </span>{{ __('From previous month') }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <div id="radialBar-chart" class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        @if (session()->has('page_limit'))
                            @php
                                $per_page = session()->get('page_limit');
                            @endphp
                        @else
                            @php
                                $per_page = Config::get('app.page_limit');
                            @endphp
                        @endif
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">{{ __('translation.items-per-page') }}</p>
                            <button
                                class="btn  {{ $per_page == 10 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items  mb-md-1"
                                data-page="10">10</button>
                            <button
                                class="btn  {{ $per_page == 25 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items  mb-md-1"
                                data-page="25">25</button>
                            <button
                                class="btn  {{ $per_page == 50 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items  mb-md-1"
                                data-page="50">50</button>
                            <button
                                class="btn  {{ $per_page == 100 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items  mb-md-1"
                                data-page="100">100</button>
                        </div>
                        <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="bx bx-book-open font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="row">
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">{{ __('translation.appointments') }}</p>
                                <h4 class="mb-0">{{ number_format($data['total_appointment']) }}</h4>
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
                                <p class="text-muted fw-medium">{{ __('translation.revenue') }}</p>
                                <h4 class="mb-0">${{ number_format($data['revenue'], 2) }}</h4>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-dollar font-size-24"></i>
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
                                <p class="text-muted fw-medium">{{ __("translation.today's-earning") }}</p>
                                <h4 class="mb-0">${{ number_format($data['daily_earning'], 2) }}</h4>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bxs-dollar-circle  font-size-24"></i>
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
                                <p class="text-muted fw-medium">{{ __("translation.today's-appointments") }}</p>
                                <a href="{{ url('/today-appointment') }}"
                                    class="mb-0 fw-medium font-size-24">
                                    <h4 class="mb-0">{{ number_format($data['today_appointment']) }}</h4>
                                </a>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-calendar font-size-24"></i>
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
                                <p class="text-muted fw-medium">{{ __('translation.tomorrow-appointments') }}</p>
                                <h4 class="mb-0">{{ number_format($data['tomorrow_appointment']) }}</h4>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-calendar-event font-size-24"></i>
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
                                <p class="text-muted fw-medium">{{ __('translation.upcoming-appointments') }}</p>
                                <a href="{{ url('/upcoming-appointment') }}"
                                    class="mb-0 fw-medium font-size-24">
                                    <h4 class="mb-0">{{ number_format($data['Upcoming_appointment']) }}
                                    </h4>
                                </a>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class='bx bxs-calendar-minus font-size-24'></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- end row -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ __('translation.monthly-registered-users') }}</h4>
                <div id="monthly_users" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ __('translation.latest-users') }}</h4>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#Doctors" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-user-md"></i></span>
                            <span class="d-none d-sm-block">{{ __('translation.doctors') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#Receptionist" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-user-tie"></i></span>
                            <span class="d-none d-sm-block">{{ __('translation.receptionist') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#Patients" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-user-injured"></i></span>
                            <span class="d-none d-sm-block">{{ __('translation.patients') }}</span>
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="Doctors" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Sr.No.') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Degree') }}</th>
                                        <th>{{ __('Contact No') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('View Details') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($doctors as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                            <td>{{ @$item->doctor->degree }}</td>
                                            <td>{{ $item->mobile }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <a href="{{ url('doctor/' . $item->id) }}">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        {{ __('View Details') }}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                    <div class="tab-pane" id="Receptionist" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Sr.No.') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Contact No') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('View Details') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receptionists as $receptionist)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $receptionist->first_name }} {{ $receptionist->last_name }}
                                            </td>
                                            <td>{{ $receptionist->mobile }}</td>
                                            <td>{{ $receptionist->email }}</td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <a href="{{ url('receptionist/' . $receptionist->id) }}">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        {{ __('View Details') }}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                    <div class="tab-pane" id="Patients" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('Sr.No.') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Contact No') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('View Details') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patients as $patient)
                                        <tr>
                                            <td> {{ $loop->index + 1 }} </td>
                                            <td> {{ $patient->first_name }} {{ $patient->last_name }} </td>
                                            <td> {{ $patient->mobile }} </td>
                                            <td> {{ $patient->email }} </td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <a href="{{ url('patient/' . $patient->id) }}">
                                                    <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                                        {{ __('View Details') }}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

{{-- ============================================================== --}}
{{-- SECCIÓN: VACUNACIONES PENDIENTES                               --}}
{{-- ============================================================== --}}
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%); border-radius: 8px 8px 0 0;">
                <div class="d-flex align-items-center">
                    <div class="me-3" style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bx bx-injection font-size-22 text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 text-white fw-bold">💉 Vacunaciones Pendientes</h5>
                        <small class="text-white-50">Pacientes con dosis pendientes según esquema de vacunación</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(isset($upcoming_vaccines) && $upcoming_vaccines->count() > 0)
                    <span class="badge rounded-pill text-white fw-bold px-3 py-2"
                          style="background:rgba(255,255,255,0.25);font-size:0.9rem;">
                        {{ $upcoming_vaccines->count() }} pendiente(s)
                    </span>
                    @endif
                    <a href="{{ route('vaccines.records.index') }}" class="btn btn-light btn-sm waves-effect">
                        <i class="bx bx-list-ul me-1"></i>Ver todos
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($upcoming_vaccines) && $upcoming_vaccines->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="upcoming-vaccines-table">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">PACIENTE</th>
                                <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">VACUNA</th>
                                <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">DOSIS</th>
                                <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">FECHA PROGRAMADA</th>
                                <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">TELÉFONO</th>
                                <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcoming_vaccines as $vRecord)
                                @php
                                    $daysUntil = \Carbon\Carbon::today()->diffInDays($vRecord->scheduled_date, false);
                                    if ($daysUntil < 0) {
                                        $urgencyClass = 'danger';
                                        $urgencyText  = 'Vencida (' . abs((int)$daysUntil) . 'd)';
                                    } elseif ($daysUntil == 0) {
                                        $urgencyClass = 'danger';
                                        $urgencyText  = 'HOY';
                                    } elseif ($daysUntil <= 3) {
                                        $urgencyClass = 'warning';
                                        $urgencyText  = $daysUntil == 1 ? 'MAÑANA' : "En {$daysUntil} días";
                                    } else {
                                        $urgencyClass = 'info';
                                        $urgencyText  = "En {$daysUntil} días";
                                    }
                                @endphp
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3 flex-shrink-0">
                                                <div class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">
                                                    {{ strtoupper(substr($vRecord->patient->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr($vRecord->patient->last_name ?? '', 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold text-dark">
                                                    {{ $vRecord->patient->first_name ?? 'N/A' }} {{ $vRecord->patient->last_name ?? '' }}
                                                </p>
                                                <small class="text-muted">
                                                    <i class="bx bx-id-card me-1"></i>
                                                    {{ $vRecord->patient->dui ?? 'Sin DUI' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-injection text-primary me-2 font-size-18"></i>
                                            <span class="fw-medium">{{ $vRecord->vaccine->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-medium">
                                            {{ $vRecord->dose_label }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div>
                                            <span class="fw-medium text-dark">
                                                {{ \Carbon\Carbon::parse($vRecord->scheduled_date)->format('d/m/Y') }}
                                            </span>
                                            <br>
                                            <span class="badge bg-{{ $urgencyClass }}-subtle text-{{ $urgencyClass }} px-2 py-1 rounded-pill mt-1" style="font-size:0.7rem;">
                                                {{ $urgencyText }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($vRecord->patient->phone_primary)
                                            <a href="tel:{{ $vRecord->patient->phone_primary }}" class="text-dark text-decoration-none">
                                                <i class="bx bx-phone text-success me-1"></i>
                                                {{ $vRecord->patient->phone_primary }}
                                            </a>
                                        @elseif($vRecord->patient->phone_secondary)
                                            <a href="tel:{{ $vRecord->patient->phone_secondary }}" class="text-dark text-decoration-none">
                                                <i class="bx bx-phone text-success me-1"></i>
                                                {{ $vRecord->patient->phone_secondary }}
                                            </a>
                                        @else
                                            <span class="text-muted"><i class="bx bx-phone-off me-1"></i>Sin teléfono</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        @php
                                            $phone = $vRecord->patient->phone_primary ?? $vRecord->patient->phone_secondary ?? '';
                                            $patientName = ($vRecord->patient->first_name ?? '') . ' ' . ($vRecord->patient->last_name ?? '');
                                            $vaccineName = $vRecord->vaccine->name ?? '';
                                            $doseLabel   = $vRecord->dose_label ?? '';
                                            $schedDate   = \Carbon\Carbon::parse($vRecord->scheduled_date)->format('d/m/Y');
                                            $waMsg = urlencode("Estimado/a {$patientName}, le recordamos que tiene programada la {$doseLabel} de la vacuna {$vaccineName} para el {$schedDate}. Por favor confírmenos su asistencia. Gracias.");
                                        @endphp
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            @if($phone)
                                                <a href="tel:{{ $phone }}"
                                                   class="btn btn-sm btn-success waves-effect"
                                                   title="Llamar al paciente">
                                                    <i class="bx bx-phone-call me-1"></i>Llamar
                                                </a>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}?text={{ $waMsg }}"
                                                   target="_blank"
                                                   class="btn btn-sm waves-effect"
                                                   style="background:#25D366;color:#fff;"
                                                   title="Enviar WhatsApp">
                                                    <i class="bx bxl-whatsapp me-1"></i>WhatsApp
                                                </a>
                                            @else
                                                <span class="text-muted small">Sin contacto</span>
                                            @endif
                                            <a href="{{ route('vaccines.patient-history', $vRecord->patient_id) }}"
                                               class="btn btn-sm btn-outline-primary waves-effect">
                                                <i class="bx bx-history me-1"></i>Historial
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                {{-- Estado vacío: no hay vacunas pendientes --}}
                <div class="text-center py-5">
                    <div class="mb-3" style="font-size:3.5rem; opacity:0.3;">💉</div>
                    <p class="text-muted fw-medium mb-1">No hay vacunas pendientes en el esquema de vacunación</p>
                    <p class="text-muted small mb-3">Todos los pacientes tienen su esquema al día o no hay dosis registradas.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('vaccines.records.create') }}" class="btn btn-primary btn-sm waves-effect">
                            <i class="bx bx-plus me-1"></i>Registrar Vacuna
                        </a>
                        <a href="{{ route('vaccines.records.index') }}" class="btn btn-outline-primary btn-sm waves-effect">
                            <i class="bx bx-list-ul me-1"></i>Ver todos los registros
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer bg-white border-top-0 py-3 text-center">
                <small class="text-muted">
                    <i class="bx bx-info-circle me-1"></i>
                    Solo se muestran pacientes con dosis <strong>pendientes</strong> según su esquema de vacunación.
                    &nbsp;|&nbsp;
                    <a href="{{ route('vaccines.catalog.index') }}" class="text-primary">Gestionar catálogo</a>
                    &nbsp;|&nbsp;
                    <a href="{{ route('vaccines.records.index') }}" class="text-primary">Ver todos los registros</a>
                </small>
            </div>
        </div>
    </div>
</div>
{{-- ============================================================== --}}
{{-- FIN SECCIÓN VACUNACIONES PRÓXIMAS                              --}}
{{-- ============================================================== --}}

