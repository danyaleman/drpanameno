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
                        <p class="text-muted mb-0 text-truncate">{{ __('Accountant') }}</p>
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
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ __('translation.monthly-earning') }}</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="text-muted">{{ __('This month') }}</p>
                        <h3>${{ number_format($data['monthly_earning']) }}</h3>
                        <p class="text-muted"><span class="@if ($data['monthly_diff'] > 0) text-success @else text-danger @endif me-2"> {{ $data['monthly_diff'] }}% <i class="mdi @if ($data['monthly_diff'] > 0) mdi-arrow-up @else mdi-arrow-down @endif"></i> </span>
                            {{ __('From previous month') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <div id="radialBar-chart" class="apex-charts"></div>
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
                                <p class="text-muted fw-medium">{{ __('Total Invoices') }}</p>
                                <h4 class="mb-0">{{ $invoices }}</h4>
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
                                <p class="text-muted fw-medium">{{ __('Unpaid Invoices') }}</p>
                                <h4 class="mb-0">{{ $unpaid_invoices }}</h4>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bxs-notepad font-size-24"></i>
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
                                <p class="text-muted fw-medium">{{ __('Total Income') }}</p>
                                <h4 class="mb-0">${{ $payment }}</h4>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
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
                                <p class="text-muted fw-medium">{{ __('Total Due') }}</p>
                                <h4 class="mb-0">${{ $payment_due }}</h4>
                            </div>
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="bx bx-dollar font-size-24"></i>
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
                <h4 class="card-title mb-4">{{ __('Monthly Invoice History') }}</h4>
                <div id="monthly_invoices" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
