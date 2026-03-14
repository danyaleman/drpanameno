@extends('layouts.master-layouts')

@section('title') {{ __('Citas de Hoy') }} @endsection

@section('content')
{{-- Encabezado de página --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📅 Citas de Hoy</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Citas de Hoy</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Pestañas de navegación --}}
<div class="row mb-3">
    <div class="col-12">
        <ul class="nav nav-pills nav-justified gap-2 px-0" role="tablist">
            <li class="nav-item">
                <a class="nav-link rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 active"
                   href="{{ url('today-appointment') }}"
                   style="background: linear-gradient(135deg,#1a73e8,#0d47a1); color: #fff; font-weight: 600; font-size: 0.85rem; border: none;">
                    <i class="fas fa-calendar-day"></i>
                    <span class="d-none d-sm-inline">Citas de Hoy</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill d-flex align-items-center justify-content-center gap-2 py-2"
                   href="{{ url('pending-appointment') }}"
                   style="background: #f0f4f8; color: #5a6a85; font-weight: 600; font-size: 0.85rem;">
                    <i class="far fa-calendar"></i>
                    <span class="d-none d-sm-inline">Pendientes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill d-flex align-items-center justify-content-center gap-2 py-2"
                   href="{{ url('upcoming-appointment') }}"
                   style="background: #f0f4f8; color: #5a6a85; font-weight: 600; font-size: 0.85rem;">
                    <i class="fas fa-calendar-week"></i>
                    <span class="d-none d-sm-inline">Futuras</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill d-flex align-items-center justify-content-center gap-2 py-2"
                   href="{{ url('complete-appointment') }}"
                   style="background: #f0f4f8; color: #5a6a85; font-weight: 600; font-size: 0.85rem;">
                    <i class="fas fa-check-square"></i>
                    <span class="d-none d-sm-inline">Completadas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill d-flex align-items-center justify-content-center gap-2 py-2"
                   href="{{ url('cancel-appointment') }}"
                   style="background: #f0f4f8; color: #5a6a85; font-weight: 600; font-size: 0.85rem;">
                    <i class="fas fa-window-close"></i>
                    <span class="d-none d-sm-inline">Canceladas</span>
                </a>
            </li>
        </ul>
    </div>
</div>

{{-- Tabla de citas --}}
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between py-3"
         style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
        <div class="d-flex align-items-center">
            <i class="bx bx-calendar text-white font-size-20 me-2"></i>
            <h5 class="mb-0 text-white fw-bold">Citas de Hoy — {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</h5>
        </div>
        <a href="{{ url('/appointment-create') }}" class="btn btn-light btn-sm waves-effect">
            <i class="bx bx-plus me-1"></i>Nueva Cita
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">PACIENTE</th>
                        <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">TELÉFONO</th>
                        <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">CORREO</th>
                        <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">HORA</th>
                        <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">ESTADO</th>
                        <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($Today_appointment as $item)
                    <tr class="border-bottom">
                        {{-- Paciente con avatar --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 flex-shrink-0">
                                    <div class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">
                                        {{ strtoupper(substr(optional($item->patient)->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr(optional($item->patient)->last_name ?? '', 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold text-dark small">
                                        {{ optional($item->patient)->first_name ?? 'N/A' }} {{ optional($item->patient)->last_name ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        {{-- Teléfono --}}
                        <td class="py-3">
                            <span class="fw-medium">
                                <i class="bx bx-phone text-muted me-1"></i>{{ optional($item->patient)->mobile ?? 'N/A' }}
                            </span>
                        </td>
                        {{-- Correo --}}
                        <td class="py-3">
                            <span class="small text-muted">
                                <i class="bx bx-envelope me-1"></i>{{ optional($item->patient)->email ?? 'N/A' }}
                            </span>
                        </td>
                        {{-- Hora --}}
                        <td class="py-3">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3" style="font-size:0.95rem;">
                                <i class="bx bx-time me-1"></i>{{ optional($item->timeSlot)->from ?? 'N/A' }} {{ optional($item->timeSlot)->to ? '- ' . optional($item->timeSlot)->to : '' }}
                            </span>
                        </td>
                        {{-- Estado --}}
                        <td class="py-3 text-center">
                            @if ($item->status == 1)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                    <i class="bx bx-check me-1"></i>Completada
                                </span>
                            @elseif($item->status == 2)
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                                    <i class="bx bx-x me-1"></i>Cancelada
                                </span>
                            @elseif($item->status == 0)
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3">
                                    <i class="bx bx-time me-1"></i>Pendiente
                                </span>
                            @endif
                        </td>
                        {{-- Acciones --}}
                        <td class="py-3 text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                @if ($item->status == 0)
                                    @if ($role != 'patient')
                                        <button type="button" class="btn btn-sm btn-success waves-effect complete"
                                                data-id="{{ $item->id }}" title="Completar cita">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger waves-effect cancel"
                                            data-id="{{ $item->id }}" title="Cancelar cita">
                                        <i class="bx bx-x"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bx bx-calendar font-size-48 d-block mb-3 text-primary opacity-50"></i>
                                <p class="mb-2 fw-medium">No hay citas programadas para hoy</p>
                                <a href="{{ url('/appointment-create') }}" class="btn btn-primary btn-sm">
                                    <i class="bx bx-plus me-1"></i>Programar nueva cita
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($Today_appointment->hasPages())
    <div class="card-footer bg-white d-flex align-items-center justify-content-between">
        <small class="text-muted">
            Mostrando {{ $Today_appointment->firstItem() }} a {{ $Today_appointment->lastItem() }} de {{ $Today_appointment->total() }} registros
        </small>
        {{ $Today_appointment->links() }}
    </div>
    @endif
</div>
@endsection

@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <!-- Init js-->
    <script src="{{ URL::asset('build/js/pages/notification.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/appointment.js') }}"></script>
@endsection
