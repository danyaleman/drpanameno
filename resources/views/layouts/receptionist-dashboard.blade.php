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
                            <img src="@if ($user->profile_photo != null) {{ URL::asset('storage/images/users/' . $user->profile_photo) }}@else{{ URL::asset('build/images/users/noImage.png') }} @endif"
                                class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text-truncate"> {{ $user->first_name }} {{ $user->last_name }} </h5>
                        <p class="text-muted mb-0 text-truncate">{{ __('Receptionist') }}</p>
                    </div>
                    <div class="col-sm-8">
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="font-size-12">{{ __('Last Login:') }}</h5>
                                    <p class="text-muted mb-0"> {{ $user->last_login }} </p>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <a href="{{ url('profile-edit') }}"
                                        class="btn btn-primary waves-effect waves-light btn-sm">{{ __('Edit Profile') }}
                                        <i class="mdi mdi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            class="btn  {{ $per_page == 10 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items mb-md-1"
                            data-page="10">10</button>
                        <button
                            class="btn  {{ $per_page == 25 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items mb-md-1"
                            data-page="25">25</button>
                        <button
                            class="btn  {{ $per_page == 50 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items mb-md-1"
                            data-page="50">50</button>
                        <button
                            class="btn  {{ $per_page == 100 ? 'btn-primary' : 'btn-info' }}  btn-sm me-2 per-page-items mb-md-1"
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
                                <p class="text-muted fw-medium">{{ __('translation.doctors') }}</p>
                                <a href="{{ url('/doctor') }}" class="mb-0 fw-medium font-size-24">
                                    <h4 class="mb-0">{{ number_format($data['total_doctor']) }}</h4>
                                </a>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bx-plus-medical font-size-24"></i>
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
                                <p class="text-muted fw-medium">{{ __('translation.patients') }}</p>
                                <a href="{{ url('/patient') }}" class="mb-0 fw-medium font-size-24">
                                    <h4 class="mb-0">{{ number_format($data['total_patient']) }}</h4>
                                </a>
                            </div>
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="bx bxs-user-rectangle font-size-24"></i>
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
                                <p class="text-muted fw-medium">{{ __("translation.today's-appointments") }}
                                </p>
                                <a href="{{ url('/today-appointment') }}" class="mb-0 fw-medium font-size-24">
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
                                <p class="text-muted fw-medium">{{ __('translation.tomorrow-appointments') }}
                                </p>
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
                                <p class="text-muted fw-medium">{{ __('translation.upcoming-appointments') }}
                                </p>
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
                <h4 class="card-title mb-4">{{ __("translation.today's-appointments") }}</h4>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Sr.No.') }}</th>
                                <th>{{ __('Patient Name') }}</th>
                                <th>{{ __('Doctor Name') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Contact No	') }}</th>
                                <th>{{ __('Time') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->patient->first_name . ' ' . $item->patient->last_name }}</td>
                                    <td>{{ @$item->doctor->user->first_name . ' ' . @$item->doctor->user->last_name }}</td>
                                    <td>{{ $item->appointment_date }}</td>
                                    <td>{{ $item->patient->mobile }}</td>
                                    <td>{{ $item->timeSlot->from . ' ' . $item->timeSlot->to }}</td>
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
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">{{ __('Doctors') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#Patients" role="tab">
                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                            <span class="d-none d-sm-block">{{ __('Patients') }}</span>
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
                                        <th>{{ __('Sr. No.') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Contact No') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('View Details') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($doctors as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ @$item->doctor->first_name }} {{ @$item->doctor->last_name }}
                                            </td>
                                            <td>{{ @$item->doctor->mobile }}</td>
                                            <td>{{ @$item->doctor->email }}</td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <a href="{{ url('doctor/' . $item->doctor->id) }}">
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
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                            <td>{{ $patient->mobile }}</td>
                                            <td>{{ $patient->email }}</td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <a href="{{ url('patient/' . $patient->id) }}">
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
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
