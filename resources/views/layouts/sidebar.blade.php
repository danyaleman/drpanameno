<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu"></li>
                <li class="menu-title" key="t-menu"></li>
                <li>
                    <a href="{{ url('/dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>{{ __('translation.dashboards') }}</span>
                    </a>
                </li>

                <li class="menu-title" key="t-menu">{{ __('Hospital') }}</li>
                @if ($role == 'admin')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class='bx bx-plus-medical'></i>
                            <span>{{ __('translation.doctors') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('doctor') }}">{{ __('translation.list-of-doctors') }}</a></li>
                            <li><a href="{{ route('doctor.create') }}">{{ __('translation.add-new-doctor') }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class='bx bxs-user-detail'></i>
                            <span>{{ __('translation.patients') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('patient') }}">{{ __('translation.list-of-patients') }}</a></li>
                            <li><a href="{{ route('patient.create') }}">{{ __('translation.add-new-patient') }}</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class='bx bx-user-voice'></i>
                            <span>{{ __('translation.receptionist') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('receptionist') }}">{{ __('translation.list-of-receptionist') }}</a>
                            </li>
                            <li><a
                                    href="{{ route('receptionist.create') }}">{{ __('translation.add-new-receptionist') }}</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-user-circle"></i>
                            <span>{{ __('translation.accountant') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('accountant') }}">{{ __('translation.list-of-accountant') }}</a></li>
                            <li><a
                                    href="{{ route('accountant.create') }}">{{ __('translation.add-new-accountant') }}</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-list-plus"></i>
                            <span>{{ __('translation.department') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('department') }}">{{ __('translation.list-of-department') }}</a></li>
                            <li><a
                                    href="{{ route('department.create') }}">{{ __('translation.add-new-department') }}</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ url('pending-appointment') }}" class="waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span>{{ __('translation.appointment-list') }}</span>
                        </a>
                    </li>
                    <li class="menu-title" key="t-menu">{{ __('Transactions') }}</li>
                    <li>
                        <a href="{{ url('transaction') }}" class="waves-effect">
                            <i class='bx bx-bookmark-minus'></i>
                            <span>{{ __('translation.transaction') }}</span>
                        </a>
                    </li>

                    <li class="menu-title" key="t-menu">{{ __('Setting') }}</li>
                    <li>
                        <a href="{{ url('app-setting') }}" class="waves-effect">
                            <i class='bx bx-cog'></i>
                            <span>{{ __('translation.app-setting') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('front-setting') }}" class="waves-effect">
                            <i class='bx bx-book-content' ></i>
                            <span>{{ __('translation.front-side') }}</span>
                        </a>
                    </li>
                @elseif ($role == 'doctor')
                    <li>
                        <a href="{{ route('appointment.create') }}" class="waves-effect">
                            <i class="bx bx-calendar-plus"></i>
                            <span>{{ __('translation.appointments') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('pending-appointment') }}" class="waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span>{{ __('translation.appointment-list') }}</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class='bx bxs-user-detail'></i>
                            <span>{{ __('translation.patients') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('patient') }}">{{ __('translation.list-of-patients') }}</a></li>
                            <li><a href="{{ route('patient.create') }}">{{ __('translation.add-new-patient') }}</a>
                            </li>
                        </ul>
                    </li>

                  
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-notepad"></i>
                            <span>{{ __('translation.prescription') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('prescription') }}">{{ __('translation.list-of-prescription') }}</a>
                            </li>
                            <li><a
                                    href="{{ route('prescription.create') }}">{{ __('translation.create-prescription') }}</a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-title" key="t-menu">{{ __('Control de Pagos') }}</li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span>{{ __('translation.invoice') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('invoice') }}">{{ __('translation.list-of-invoice') }}</a></li>
                            <li><a href="{{ route('invoice.create') }}">{{ __('translation.create-invoice') }}</a>
                            </li>
                        </ul>
                    </li>
                @elseif ($role == 'receptionist')
                    <li>
                        <a href="{{ route('appointment.create') }}" class="waves-effect">
                            <i class="bx bx-calendar-plus"></i>
                            <span>{{ __('translation.appointments') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('pending-appointment') }}" class="waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span>{{ __('translation.appointment-list') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('doctor') }}" class="waves-effect">
                            <i class='bx bx-plus-medical'></i>
                            <span>{{ __('translation.doctors') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class='bx bxs-user-detail'></i>
                            <span>{{ __('translation.patients') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('patient') }}">{{ __('translation.list-of-patients') }}</a></li>
                            <li><a href="{{ route('patient.create') }}">{{ __('translation.add-new-patient') }}</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ url('prescription') }}" class="waves-effect">
                            <i class="bx bx-notepad"></i>
                            <span>{{ __('translation.prescription') }}</span>
                        </a>
                    </li>
                    <li class="menu-title" key="t-menu">{{ __('Billing') }}</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span>{{ __('translation.invoice') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('invoice') }}">{{ __('translation.list-of-invoice') }}</a></li>
                            <li><a href="{{ route('invoice.create') }}">{{ __('translation.create-invoice') }}</a>
                            </li>
                        </ul>
                    </li>
                    
                @elseif ($role == 'patient')
                    <li>
                        <a href="{{ route('appointment.create') }}" class="waves-effect">
                            <i class="bx bx-calendar-plus"></i>
                            <span>{{ __('translation.appointments') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('patient-appointment') }}" class="waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span>{{ __('translation.appointment-list') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('doctor') }}" class="waves-effect">
                            <i class='bx bx-plus-medical'></i>
                            <span>{{ __('translation.doctors') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('prescription-list') }}" class="waves-effect">
                            <i class="bx bx-notepad"></i>
                            <span>{{ __('translation.prescription') }}</span>
                        </a>
                    </li>
                    <li class="menu-title" key="t-menu">{{ __('Billing') }}</li>
                    <li>
                        <a href="{{ url('invoice-list') }}" class="waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span>{{ __('translation.invoice') }}</span>
                        </a>
                    </li>
                @elseif ($role == 'accountant')
                    <li>
                        <a href="{{ url('doctor') }}" class="waves-effect">
                            <i class='bx bx-plus-medical'></i>
                            <span>{{ __('translation.doctors') }}</span>
                        </a>
                    </li>
                    <li class="menu-title" key="t-menu">{{ __('Billing') }}</li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span>{{ __('translation.invoice') }}</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ url('invoice') }}">{{ __('translation.list-of-invoice') }}</a></li>
                            <li><a href="{{ route('invoice.create') }}">{{ __('translation.create-invoice') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
