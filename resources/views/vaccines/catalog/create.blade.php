@extends('layouts.master-layouts')

@section('title') Nueva Vacuna @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">💉 Nueva Vacuna en el Catálogo</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vaccines.catalog.index') }}">Catálogo</a></li>
                    <li class="breadcrumb-item active">Nueva Vacuna</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <form action="{{ route('vaccines.catalog.store') }}" method="POST" id="vaccine-form">
            @csrf

            {{-- Datos generales --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3" style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <h5 class="mb-0 text-white fw-bold"><i class="bx bx-injection me-2"></i>Datos de la Vacuna</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nombre de la vacuna <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Ej: COVID-19 Pfizer, Tétanos, Hepatitis B..."
                                   required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Código interno</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}" placeholder="Ej: COV-PFZ">
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fabricante</label>
                            <input type="text" name="manufacturer" class="form-control"
                                   value="{{ old('manufacturer') }}" placeholder="Ej: Pfizer-BioNTech">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Total de dosis <span class="text-danger">*</span></label>
                            <input type="number" name="total_doses" id="total_doses"
                                   class="form-control @error('total_doses') is-invalid @enderror"
                                   value="{{ old('total_doses', 1) }}" min="1" max="20" required>
                            @error('total_doses')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descripción / Indicaciones</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Descripción de la vacuna, indicaciones, contraindicaciones...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Esquema de dosis --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header py-3 bg-light">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bx bx-calendar-check me-2 text-primary"></i>Esquema de Dosis</h5>
                        <small class="text-muted">Define los intervalos entre dosis</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="doses-container">
                        <div class="dose-row border rounded p-3 mb-3 bg-light" data-index="0">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary rounded-pill me-2">Dosis 1</span>
                                <small class="text-muted">Primera dosis (no requiere espera)</small>
                            </div>
                            <div class="row g-2">
                                <input type="hidden" name="doses[0][dose_number]" value="1">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Etiqueta</label>
                                    <input type="text" name="doses[0][dose_label]" class="form-control form-control-sm"
                                           value="Dosis 1" placeholder="Ej: Dosis 1, Primera dosis...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Días de espera</label>
                                    <input type="number" name="doses[0][days_after_previous]" class="form-control form-control-sm"
                                           value="0" min="0" readonly style="background:#f8f9fa;">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-semibold">Notas</label>
                                    <input type="text" name="doses[0][notes]" class="form-control form-control-sm"
                                           placeholder="Opcional...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <i class="bx bx-info-circle me-1"></i>
                            Las dosis adicionales se generan automáticamente según el "Total de dosis" que ingreses arriba.
                            Puedes ajustar las etiquetas y los días de espera.
                        </small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('vaccines.catalog.index') }}" class="btn btn-outline-secondary waves-effect">
                    <i class="bx bx-arrow-back me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary waves-effect waves-light">
                    <i class="bx bx-save me-1"></i>Guardar Vacuna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalDosesInput = document.getElementById('total_doses');
    const container = document.getElementById('doses-container');

    function buildDoses(total) {
        container.innerHTML = '';
        for (let i = 0; i < total; i++) {
            const isFirst = i === 0;
            const doseNum = i + 1;
            const defaultLabel = doseNum === total && total > 1 ? 'Refuerzo' : `Dosis ${doseNum}`;
            const html = `
                <div class="dose-row border rounded p-3 mb-3 ${isFirst ? 'bg-light' : 'bg-white'}">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary rounded-pill me-2">Dosis ${doseNum}</span>
                        ${isFirst ? '<small class="text-muted">Primera dosis (no requiere espera)</small>' : ''}
                    </div>
                    <div class="row g-2">
                        <input type="hidden" name="doses[${i}][dose_number]" value="${doseNum}">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Etiqueta</label>
                            <input type="text" name="doses[${i}][dose_label]" class="form-control form-control-sm"
                                   value="${defaultLabel}" placeholder="Ej: Dosis ${doseNum}...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Días de espera</label>
                            <input type="number" name="doses[${i}][days_after_previous]" class="form-control form-control-sm"
                                   value="${isFirst ? 0 : 21}" min="0" ${isFirst ? 'readonly style="background:#f8f9fa;"' : ''}>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Notas</label>
                            <input type="text" name="doses[${i}][notes]" class="form-control form-control-sm"
                                   placeholder="Opcional...">
                        </div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }
    }

    totalDosesInput.addEventListener('input', function () {
        const val = parseInt(this.value) || 1;
        if (val >= 1 && val <= 20) buildDoses(val);
    });

    // Inicializar con el valor actual
    buildDoses(parseInt(totalDosesInput.value) || 1);
});
</script>
@endsection
