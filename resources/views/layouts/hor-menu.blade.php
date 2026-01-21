<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/dashboard') }}">
                            <i class="bx bx-home-circle me-2"></i>{{ __('translation.dashboards') }}
                        </a>
                    </li>
                    @if ($role == 'admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-plus-medical me-2'></i></i>{{ __('translation.doctors') }}
                                <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('doctor') }}" class="dropdown-item">{{ __('translation.list-of-doctors') }}</a>
                                <a href="{{ route('doctor.create') }}" class="dropdown-item">{{ __('translation.add-new-doctor') }}</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bxs-user-detail me-2"></i>{{ __('translation.patients') }} 
                                <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('patient') }}" class="dropdown-item">{{ __('translation.list-of-patients') }}</a>
                                <a href="{{ route('patient.create') }}" class="dropdown-item">{{ __('translation.add-new-patient') }}</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-user-voice me-2"></i>{{ __('translation.receptionist') }} 
                                <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('receptionist') }}" class="dropdown-item">{{ __('translation.list-of-receptionist') }}</a>
                                <a href="{{ route('receptionist.create') }}" class="dropdown-item">{{ __('translation.add-new-receptionist') }}</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-user-circle me-2"></i>{{ __('translation.accountant') }} <div
                                    class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('accountant') }}"
                                    class="dropdown-item">{{ __('translation.list-of-accountant') }}</a>
                                <a href="{{ route('accountant.create') }}"
                                    class="dropdown-item">{{ __('translation.add-new-accountant') }}</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-layout me-2"></i><span>{{ __('translation.other') }}</span>
                                <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown">
                                    <a class="dropdown-item dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span>{{ __('translation.department') }}</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="{{ url('department') }}"
                                            class="dropdown-item">{{ __('translation.list-of-department') }}</a>
                                        <a href="{{ route('department.create') }}"
                                            class="dropdown-item">{{ __('translation.add-new-department') }}</a>
                                    </div>
                                </div>
                                <a class="dropdown-item" href="{{ url('pending-appointment') }}"
                                ><span>{{ __('translation.appointment-list') }}</span></a>
                                <a class="dropdown-item" href="{{ url('transaction') }}"
                                ><span>{{ __('translation.transaction') }}</span></a>
                                <a class="dropdown-item" href="{{ url('app-setting') }}"
                                ><span>{{ __('translation.app-setting') }}</span></a>
                                <a class="dropdown-item" href="{{ url('front-setting') }}"
                                ><span>{{ __('translation.front-side') }}</span></a>
                            </div>
                        </li>
                    @elseif ($role == 'doctor')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointment.create') }}">
                                <i class="bx bx-calendar-plus me-2"></i>{{ __('translation.appointments') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bxs-user-detail me-2"></i>{{ __('translation.patients') }} <div
                                    class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('patient') }}"
                                    class="dropdown-item">{{ __('translation.list-of-patients') }}</a>
                                <a href="{{ route('patient.create') }}"
                                    class="dropdown-item">{{ __('translation.add-new-patient') }}</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-notepad me-2"></i>{{ __('translation.prescription') }}<div
                                    class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('prescription') }}"
                                    class="dropdown-item">{{ __('translation.list-of-prescription') }}</a>
                                <a href="{{ route('prescription.create') }}"
                                    class="dropdown-item">{{ __('translation.create-prescription') }}</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-receipt me-2"></i>{{ __('translation.invoice') }} <div
                                    class="arrow-down">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('invoice') }}"
                                    class="dropdown-item">{{ __('translation.list-of-invoice') }}</a>
                                <a href="{{ route('invoice.create') }}"
                                    class="dropdown-item">{{ __('translation.create-invoice') }}</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('pending-appointment') }}">
                                <i class='bx bx-list-ul me-2'></i>{{ __('translation.appointment-list') }}
                            </a>
                        </li>
                    @elseif ($role == 'receptionist')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointment.create') }}">
                                <i class="bx bx-calendar-plus me-2"></i>{{ __('translation.appointments') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('doctor') }}">
                                <i class="bx-plus-medical me-2"></i>{{ __('translation.doctors') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bxs-user-detail me-2"></i>{{ __('translation.patients') }} <div
                                    class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('patient') }}"
                                    class="dropdown-item">{{ __('translation.list-of-patients') }}</a>
                                <a href="{{ route('patient.create') }}"
                                    class="dropdown-item">{{ __('translation.add-new-patient') }}</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('prescription') }}">
                                <i class="bx bx-notepad me-2"></i>{{ __('translation.prescription') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-receipt me-2"></i>{{ __('translation.invoice') }}<div
                                    class="arrow-down">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('invoice') }}"
                                    class="dropdown-item">{{ __('translation.list-of-invoice') }}</a>
                                <a href="{{ route('invoice.create') }}"
                                    class="dropdown-item">{{ __('translation.create-invoice') }}</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('pending-appointment') }}">
                                <i class='bx bx-list-plus me-2'></i>{{ __('translation.appointment-list') }}
                            </a>
                        </li>
                    @elseif ($role == 'patient')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('appointment.create') }}">
                                <i class="bx bx-calendar-plus me-2"></i>{{ __('translation.appointments') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('doctor') }}">
                                <i class="bx-plus-medical me-2"></i>{{ __('translation.doctors') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('prescription-list') }}">
                                <i class="bx bx-notepad me-2"></i>{{ __('translation.prescription') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('invoice-list') }}">
                                <i class="bx bx-receipt me-2"></i>{{ __('translation.invoice') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('patient-appointment') }}">
                                <i class='bx bx-list-ul me-2'></i>{{ __('translation.appointment-list') }}
                            </a>
                        </li>
                    @elseif ($role == 'accountant')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('doctor') }}">
                                <i class="bx-plus-medical me-2"></i>{{ __('translation.doctors') }}
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#!" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-receipt me-2"></i>{{ __('translation.invoice') }} <div
                                    class="arrow-down">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ url('invoice') }}"
                                    class="dropdown-item">{{ __('translation.list-of-invoice') }}</a>
                                <a href="{{ route('invoice.create') }}"
                                    class="dropdown-item">{{ __('translation.create-invoice') }}</a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
