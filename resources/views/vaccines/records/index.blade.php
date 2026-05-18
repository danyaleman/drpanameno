@extends('layouts.master-layouts')

@section('title') Registros de Vacunación @endsection

@section('css')
<style>
    /* ── Stat Filter Cards ── */
    .stat-card {
        border: 2px solid transparent !important;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12) !important;
    }
    .stat-total:hover { border-color: #1a73e8 !important; }
    .stat-applied:hover { border-color: #28a745 !important; }
    .stat-pending:hover { border-color: #fd7e14 !important; }
    .stat-upcoming:hover { border-color: #dc3545 !important; }

    /* Active filter state */
    .active-filter .stat-total {
        border-color: #1a73e8 !important;
        box-shadow: 0 0 0 3px rgba(26,115,232,0.2), 0 4px 15px rgba(26,115,232,0.15) !important;
    }
    .active-filter .stat-applied {
        border-color: #28a745 !important;
        box-shadow: 0 0 0 3px rgba(40,167,69,0.2), 0 4px 15px rgba(40,167,69,0.15) !important;
    }
    .active-filter .stat-pending {
        border-color: #fd7e14 !important;
        box-shadow: 0 0 0 3px rgba(253,126,20,0.2), 0 4px 15px rgba(253,126,20,0.15) !important;
    }
    .active-filter .stat-upcoming {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220,53,69,0.2), 0 4px 15px rgba(220,53,69,0.15) !important;
    }

    /* Pulse animation for urgent cards */
    @keyframes pulse-border {
        0%, 100% { box-shadow: 0 0 0 0 rgba(220,53,69,0.4); }
        50% { box-shadow: 0 0 0 6px rgba(220,53,69,0); }
    }
</style>
@endsection

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

@php
    $activeFilter = request('status', '');
@endphp

{{-- Tarjetas de estadísticas — filtros interactivos --}}
<div class="row g-3 mb-4">
    {{-- Total Registros --}}
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('vaccines.records.index') }}" class="text-decoration-none stat-filter-card {{ $activeFilter === '' ? 'active-filter' : '' }}" data-filter="">
            <div class="card border-0 shadow-sm h-100 stat-card stat-total" style="border-radius: 14px; overflow: hidden; transition: all 0.3s ease; cursor: pointer;">
                <div class="card-body p-0">
                    <div class="d-flex align-items-stretch">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width: 90px; background: linear-gradient(180deg, #1a73e8, #0d47a1);">
                            <i class="bx bx-injection text-white" style="font-size: 36px;"></i>
                        </div>
                        <div class="flex-grow-1 p-3 d-flex flex-column justify-content-center">
                            <p class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px; color: #1a73e8;">Total Registros</p>
                            <h3 class="mb-0 fw-bold" style="color: #0d47a1; font-size: 1.8rem; line-height: 1;">{{ $stats['total'] }}</h3>
                            <small class="text-muted" style="font-size: 0.72rem;">Todos los registros</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Aplicadas --}}
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('vaccines.records.index', ['status' => 'applied']) }}" class="text-decoration-none stat-filter-card {{ $activeFilter === 'applied' ? 'active-filter' : '' }}" data-filter="applied">
            <div class="card border-0 shadow-sm h-100 stat-card stat-applied" style="border-radius: 14px; overflow: hidden; transition: all 0.3s ease; cursor: pointer;">
                <div class="card-body p-0">
                    <div class="d-flex align-items-stretch">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width: 90px; background: linear-gradient(180deg, #28a745, #1e7e34);">
                            <i class="bx bx-check-circle text-white" style="font-size: 36px;"></i>
                        </div>
                        <div class="flex-grow-1 p-3 d-flex flex-column justify-content-center">
                            <p class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px; color: #28a745;">Aplicadas</p>
                            <h3 class="mb-0 fw-bold" style="color: #1e7e34; font-size: 1.8rem; line-height: 1;">{{ $stats['applied'] }}</h3>
                            <small class="text-muted" style="font-size: 0.72rem;">Dosis completadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Pendientes --}}
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('vaccines.records.index', ['status' => 'pending']) }}" class="text-decoration-none stat-filter-card {{ $activeFilter === 'pending' ? 'active-filter' : '' }}" data-filter="pending">
            <div class="card border-0 shadow-sm h-100 stat-card stat-pending" style="border-radius: 14px; overflow: hidden; transition: all 0.3s ease; cursor: pointer;">
                <div class="card-body p-0">
                    <div class="d-flex align-items-stretch">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width: 90px; background: linear-gradient(180deg, #fd7e14, #e55a00);">
                            <i class="bx bx-time text-white" style="font-size: 36px;"></i>
                        </div>
                        <div class="flex-grow-1 p-3 d-flex flex-column justify-content-center">
                            <p class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px; color: #fd7e14;">Pendientes</p>
                            <h3 class="mb-0 fw-bold" style="color: #e55a00; font-size: 1.8rem; line-height: 1;">{{ $stats['pending'] }}</h3>
                            <small class="text-muted" style="font-size: 0.72rem;">Dosis por aplicar</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Próximos 7 días --}}
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('vaccines.records.index', ['status' => 'upcoming']) }}" class="text-decoration-none stat-filter-card {{ $activeFilter === 'upcoming' ? 'active-filter' : '' }}" data-filter="upcoming">
            <div class="card border-0 shadow-sm h-100 stat-card stat-upcoming" style="border-radius: 14px; overflow: hidden; transition: all 0.3s ease; cursor: pointer; {{ $stats['upcoming'] > 0 ? 'animation: pulse-border 2s infinite;' : '' }}">
                <div class="card-body p-0">
                    <div class="d-flex align-items-stretch">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width: 90px; background: linear-gradient(180deg, #dc3545, #a71d2a);">
                            <i class="bx bx-bell text-white" style="font-size: 36px;"></i>
                        </div>
                        <div class="flex-grow-1 p-3 d-flex flex-column justify-content-center">
                            <p class="text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1.5px; color: #dc3545;">Próximos 7 Días</p>
                            <h3 class="mb-0 fw-bold" style="color: #a71d2a; font-size: 1.8rem; line-height: 1;">{{ $stats['upcoming'] }}</h3>
                            <small class="text-muted" style="font-size: 0.72rem;">Vacunas por vencer</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
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
                    <option value="upcoming"  {{ request('status') == 'upcoming'  ? 'selected' : '' }}>Próximos 7 días</option>
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
                                <form action="{{ route('vaccines.records.destroy', $record->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este registro de vacuna permanentemente? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger waves-effect"
                                            title="Eliminar registro">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
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
