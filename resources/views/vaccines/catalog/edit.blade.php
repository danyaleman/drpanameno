@extends('layouts.master-layouts')

@section('title') Editar Vacuna @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">✏️ Editar Vacuna: {{ $vaccine->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vaccines.catalog.index') }}">Catálogo</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <form action="{{ route('vaccines.catalog.update', $vaccine->id) }}" method="POST" id="vaccine-form">
            @csrf
            @method('PUT')

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <h5 class="mb-0 text-white fw-bold"><i class="bx bx-injection me-2"></i>Datos de la Vacuna</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $vaccine->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Código interno</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $vaccine->code) }}">
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fabricante</label>
                            <input type="text" name="manufacturer" class="form-control"
                                   value="{{ old('manufacturer', $vaccine->manufacturer) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Total de dosis <span class="text-danger">*</span></label>
                            <input type="number" name="total_doses" id="total_doses"
                                   class="form-control @error('total_doses') is-invalid @enderror"
                                   value="{{ old('total_doses', $vaccine->total_doses) }}" min="1" max="20" required>
                            @error('total_doses')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                       value="1" {{ old('is_active', $vaccine->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Vacuna activa</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $vaccine->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3 bg-light">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bx bx-calendar-check me-2 text-primary"></i>Esquema de Dosis</h5>
                </div>
                <div class="card-body p-4">
                    <div id="doses-container">
                        @foreach($vaccine->schedules as $schedule)
                        <div class="dose-row border rounded p-3 mb-3 {{ $loop->first ? 'bg-light' : 'bg-white' }}">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary rounded-pill me-2">Dosis {{ $schedule->dose_number }}</span>
                            </div>
                            <div class="row g-2">
                                <input type="hidden" name="doses[{{ $loop->index }}][dose_number]" value="{{ $schedule->dose_number }}">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Etiqueta</label>
                                    <input type="text" name="doses[{{ $loop->index }}][dose_label]"
                                           class="form-control form-control-sm" value="{{ $schedule->dose_label }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Días de espera</label>
                                    <input type="number" name="doses[{{ $loop->index }}][days_after_previous]"
                                           class="form-control form-control-sm" value="{{ $schedule->days_after_previous }}"
                                           min="0" {{ $loop->first ? 'readonly style="background:#f8f9fa;"' : '' }}>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-semibold">Notas</label>
                                    <input type="text" name="doses[{{ $loop->index }}][notes]"
                                           class="form-control form-control-sm" value="{{ $schedule->notes }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('vaccines.catalog.index') }}" class="btn btn-outline-secondary waves-effect">
                    <i class="bx bx-arrow-back me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary waves-effect waves-light">
                    <i class="bx bx-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
