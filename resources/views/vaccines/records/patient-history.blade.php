@extends('layouts.master-layouts')

@section('title') Historial de Vacunas — {{ $patient->first_name }} {{ $patient->last_name }} @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">💉 Historial de Vacunas</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vaccines.records.index') }}">Vacunaciones</a></li>
                    <li class="breadcrumb-item active">Historial</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Tarjeta del paciente --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white"
                 style="width:70px;height:70px;font-size:1.8rem;background:linear-gradient(135deg,#1a73e8,#0d47a1);">
                {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
            </div>
            <div class="flex-grow-1">
                <h4 class="mb-1 fw-bold">{{ $patient->first_name }} {{ $patient->last_name }}</h4>
                <div class="d-flex flex-wrap gap-3 text-muted small">
                    @if($patient->dui)
                        <span><i class="bx bx-id-card me-1"></i>{{ $patient->dui }}</span>
                    @endif
                    @if($patient->phone_primary)
                        <span><i class="bx bx-phone me-1"></i>{{ $patient->phone_primary }}</span>
                    @endif
                    @if($patient->email)
                        <span><i class="bx bx-envelope me-1"></i>{{ $patient->email }}</span>
                    @endif
                    @if($patient->birth_date)
                        <span><i class="bx bx-cake me-1"></i>{{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }}</span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('vaccines.records.create', ['patient_id' => $patient->id]) }}"
                   class="btn btn-primary waves-effect">
                    <i class="bx bx-plus me-1"></i>Registrar Vacuna
                </a>
                <a href="{{ url('patient/' . $patient->id) }}" class="btn btn-outline-secondary waves-effect">
                    <i class="bx bx-user me-1"></i>Ver Paciente
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Historial agrupado por vacuna --}}
@if($records->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bx bx-injection font-size-48 d-block mb-3 text-primary opacity-50"></i>
            <p class="text-muted fw-medium mb-3">Este paciente no tiene registros de vacunación</p>
            <a href="{{ route('vaccines.records.create', ['patient_id' => $patient->id]) }}"
               class="btn btn-primary">
                <i class="bx bx-plus me-1"></i>Registrar primera vacuna
            </a>
        </div>
    </div>
@else
    @foreach($records as $vaccineId => $vaccineRecords)
    @php $vaccineName = $vaccineRecords->first()->vaccine->name ?? 'Vacuna desconocida'; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between"
             style="background:linear-gradient(135deg,#1a73e8,#0d47a1);">
            <div class="d-flex align-items-center">
                <div class="me-3" style="width:38px;height:38px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="bx bx-injection text-white font-size-18"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-white fw-bold">{{ $vaccineName }}</h6>
                    <small class="text-white-50">{{ $vaccineRecords->count() }} dosis registradas</small>
                </div>
            </div>
            @php
                $appliedCount = $vaccineRecords->where('status', 'applied')->count();
                $totalDoses   = $vaccineRecords->first()->vaccine->total_doses ?? $vaccineRecords->count();
                $pct = $totalDoses > 0 ? round(($appliedCount / $totalDoses) * 100) : 0;
            @endphp
            <div class="text-end">
                <small class="text-white-50 d-block">{{ $appliedCount }}/{{ $totalDoses }} dosis aplicadas</small>
                <div class="progress mt-1" style="width:120px;height:6px;background:rgba(255,255,255,0.2);">
                    <div class="progress-bar bg-white" style="width:{{ $pct }}%"></div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="ps-4 py-3 text-muted fw-semibold" style="font-size:0.8rem;">DOSIS</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;">FECHA PROGRAMADA</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;">FECHA APLICADA</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;">LOTE</th>
                            <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;">APLICADA POR</th>
                            <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;">ESTADO</th>
                            <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;">ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vaccineRecords as $record)
                        @php
                            $daysUntil = $record->scheduled_date
                                ? \Carbon\Carbon::today()->diffInDays($record->scheduled_date, false)
                                : null;
                        @endphp
                        <tr class="border-bottom">
                            <td class="ps-4 py-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 fw-medium">
                                    {{ $record->dose_label }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($record->scheduled_date)
                                    {{ \Carbon\Carbon::parse($record->scheduled_date)->format('d/m/Y') }}
                                    @if($record->status === 'pending' && $daysUntil !== null)
                                        @if($daysUntil === 0)
                                            <span class="badge bg-danger-subtle text-danger ms-1 small">HOY</span>
                                        @elseif($daysUntil > 0 && $daysUntil <= 7)
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
                                {{ $record->applied_date ? \Carbon\Carbon::parse($record->applied_date)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="py-3">
                                @if($record->lot_number)
                                    <code class="bg-light px-2 py-1 rounded small">{{ $record->lot_number }}</code>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3 small">{{ $record->applied_by ?? '—' }}</td>
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
                                @if($record->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success waves-effect"
                                            data-bs-toggle="modal"
                                            data-bs-target="#applyModalH{{ $record->id }}"
                                            title="Marcar como aplicada">
                                        <i class="bx bx-check me-1"></i>Aplicar
                                    </button>
                                @endif
                            </td>
                        </tr>

                        @if($record->status === 'pending')
                        <div class="modal fade" id="applyModalH{{ $record->id }}" tabindex="-1">
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
                                                <strong>{{ $vaccineName }}</strong> — {{ $record->dose_label }}
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Fecha de aplicación <span class="text-danger">*</span></label>
                                                    <input type="date" name="applied_date" class="form-control"
                                                           value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Número de lote</label>
                                                    <input type="text" name="lot_number" class="form-control">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Aplicada por</label>
                                                    <input type="text" name="applied_by" class="form-control">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Observaciones</label>
                                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="bx bx-check me-1"></i>Confirmar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
