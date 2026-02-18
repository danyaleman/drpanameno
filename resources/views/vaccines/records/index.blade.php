@extends('layouts.master-layouts')

@section('title') Registros de Vacunación @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">💉 Registros de Vacunación</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vacunaciones</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Tarjetas de estadísticas --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <i class="bx bx-injection text-white font-size-22"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-semibold">TOTAL REGISTROS</p>
                    <h4 class="mb-0 fw-bold">{{ $stats['total'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#28a745,#1e7e34);">
                    <i class="bx bx-check-circle text-white font-size-22"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-semibold">APLICADAS</p>
                    <h4 class="mb-0 fw-bold text-success">{{ $stats['applied'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#fd7e14,#e55a00);">
                    <i class="bx bx-time text-white font-size-22"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-semibold">PENDIENTES</p>
                    <h4 class="mb-0 fw-bold text-warning">{{ $stats['pending'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#dc3545,#a71d2a);">
                    <i class="bx bx-bell text-white font-size-22"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-semibold">PRÓXIMOS 7 DÍAS</p>
                    <h4 class="mb-0 fw-bold text-danger">{{ $stats['upcoming'] }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('vaccines.records.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Buscar paciente</label>
                <input type="text" name="patient_search" class="form-control form-control-sm"
                       value="{{ request('patient_search') }}" placeholder="Nombre o DUI...">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Vacuna</label>
                <select name="vaccine_id" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    @foreach($vaccines as $v)
                        <option value="{{ $v->id }}" {{ request('vaccine_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Estado</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pendiente</option>
                    <option value="applied"   {{ request('status') == 'applied'   ? 'selected' : '' }}>Aplicada</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Desde</label>
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Hasta</label>
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm waves-effect" title="Filtrar">
                    <i class="bx bx-search"></i>
                </button>
                <a href="{{ route('vaccines.records.index') }}" class="btn btn-outline-secondary btn-sm" title="Limpiar">
                    <i class="bx bx-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de registros --}}
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between py-3"
         style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
        <div class="d-flex align-items-center">
            <i class="bx bx-list-ul text-white font-size-20 me-2"></i>
            <h5 class="mb-0 text-white fw-bold">Historial de Vacunaciones</h5>
        </div>
        <a href="{{ route('vaccines.records.create') }}" class="btn btn-light btn-sm waves-effect">
            <i class="bx bx-plus me-1"></i>Registrar Vacuna
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th class="ps-4 py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">PACIENTE</th>
                        <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">VACUNA</th>
                        <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">DOSIS</th>
                        <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">FECHA PROGRAMADA</th>
                        <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">FECHA APLICADA</th>
                        <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">ESTADO</th>
                        <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    @php
                        $daysUntil = $record->scheduled_date
                            ? \Carbon\Carbon::today()->diffInDays($record->scheduled_date, false)
                            : null;
                        $isOverdue = $record->status === 'pending' && $daysUntil !== null && $daysUntil < 0;
                    @endphp
                    <tr class="border-bottom {{ $isOverdue ? 'table-danger' : '' }}">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3 flex-shrink-0">
                                    <div class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">
                                        {{ strtoupper(substr($record->patient->first_name ?? 'P', 0, 1)) }}{{ strtoupper(substr($record->patient->last_name ?? '', 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold text-dark small">
                                        {{ $record->patient->first_name ?? 'N/A' }} {{ $record->patient->last_name ?? '' }}
                                    </p>
                                    <a href="{{ route('vaccines.patient-history', $record->patient_id) }}"
                                       class="text-primary small">
                                        <i class="bx bx-history me-1"></i>Ver historial
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="fw-medium">{{ $record->vaccine->name ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3 text-center">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                                {{ $record->dose_label }}
                            </span>
                        </td>
                        <td class="py-3">
                            @if($record->scheduled_date)
                                {{ \Carbon\Carbon::parse($record->scheduled_date)->format('d/m/Y') }}
                                @if($record->status === 'pending')
                                    @if($daysUntil === 0)
                                        <span class="badge bg-danger-subtle text-danger ms-1 small">HOY</span>
                                    @elseif($daysUntil > 0 && $daysUntil <= 3)
                                        <span class="badge bg-warning-subtle text-warning ms-1 small">En {{ $daysUntil }}d</span>
                                    @elseif($daysUntil < 0)
                                        <span class="badge bg-danger ms-1 small text-white">Vencida</span>
                                    @endif
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($record->applied_date)
                                {{ \Carbon\Carbon::parse($record->applied_date)->format('d/m/Y') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            @if($record->status === 'applied')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                    <i class="bx bx-check me-1"></i>Aplicada
                                </span>
                            @elseif($record->status === 'pending')
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3">
                                    <i class="bx bx-time me-1"></i>Pendiente
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">
                                    <i class="bx bx-x me-1"></i>Cancelada
                                </span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                @if($record->status === 'pending')
                                    <button type="button"
                                            class="btn btn-sm btn-success waves-effect"
                                            data-bs-toggle="modal"
                                            data-bs-target="#applyModal{{ $record->id }}"
                                            title="Marcar como aplicada">
                                        <i class="bx bx-check"></i>
                                    </button>
                                    <form action="{{ route('vaccines.records.cancel', $record->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Cancelar esta dosis?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger waves-effect"
                                                title="Cancelar dosis">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('vaccines.patient-history', $record->patient_id) }}"
                                   class="btn btn-sm btn-outline-primary waves-effect" title="Ver historial">
                                    <i class="bx bx-user"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal para marcar como aplicada --}}
                    @if($record->status === 'pending')
                    <div class="modal fade" id="applyModal{{ $record->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background:linear-gradient(135deg,#28a745,#1e7e34);">
                                    <h5 class="modal-title text-white fw-bold">
                                        <i class="bx bx-injection me-2"></i>Confirmar Aplicación
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('vaccines.records.apply', $record->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="alert alert-info mb-3">
                                            <strong>{{ $record->patient->first_name ?? '' }} {{ $record->patient->last_name ?? '' }}</strong>
                                            — {{ $record->vaccine->name ?? '' }} ({{ $record->dose_label }})
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Fecha de aplicación <span class="text-danger">*</span></label>
                                                <input type="date" name="applied_date" class="form-control"
                                                       value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Número de lote</label>
                                                <input type="text" name="lot_number" class="form-control"
                                                       placeholder="Ej: LOT-2024-001">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Aplicada por</label>
                                                <input type="text" name="applied_by" class="form-control"
                                                       placeholder="Nombre del profesional">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Observaciones</label>
                                                <textarea name="notes" class="form-control" rows="2"
                                                          placeholder="Reacciones, observaciones..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success waves-effect">
                                            <i class="bx bx-check me-1"></i>Confirmar Aplicación
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bx bx-injection font-size-48 d-block mb-3 text-primary opacity-50"></i>
                                <p class="mb-2 fw-medium">No hay registros de vacunación</p>
                                <a href="{{ route('vaccines.records.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bx bx-plus me-1"></i>Registrar primera vacuna
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
    <div class="card-footer bg-white">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
