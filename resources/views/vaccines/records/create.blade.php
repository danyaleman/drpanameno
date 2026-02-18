@extends('layouts.master-layouts')

@section('title') Registrar Vacuna @endsection

{{-- Select2 CSS --}}
@section('css')
<link href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        line-height: 1.5;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem + 2px);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
        color: #495057;
    }
    .select2-container { width: 100% !important; }
    .select2-dropdown { border: 1px solid #ced4da; border-radius: 0.25rem; }
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
    #dose-loading { display: none; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">💉 Registrar Vacuna Aplicada</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vaccines.records.index') }}">Vacunaciones</a></li>
                    <li class="breadcrumb-item active">Registrar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('vaccines.records.store') }}" method="POST" id="record-form">
            @csrf
            <div class="card shadow-sm border-0">
                <div class="card-header py-3" style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <h5 class="mb-0 text-white fw-bold"><i class="bx bx-injection me-2"></i>Datos de la Vacunación</h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <div class="row g-3">
                        {{-- Paciente con búsqueda Select2 --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Paciente <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id"
                                    class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">— Buscar paciente por nombre o DUI —</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ (old('patient_id', $selectedPatient?->id) == $patient->id) ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                        @if($patient->dui) — {{ $patient->dui }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Vacuna --}}
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Vacuna <span class="text-danger">*</span></label>
                            <select name="vaccine_catalog_id" id="vaccine_catalog_id"
                                    class="form-select @error('vaccine_catalog_id') is-invalid @enderror" required>
                                <option value="">— Seleccionar vacuna —</option>
                                @foreach($vaccines as $vaccine)
                                    <option value="{{ $vaccine->id }}"
                                        {{ old('vaccine_catalog_id') == $vaccine->id ? 'selected' : '' }}>
                                        {{ $vaccine->name }}
                                        @if($vaccine->code) ({{ $vaccine->code }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('vaccine_catalog_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Dosis --}}
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">
                                Dosis aplicada <span class="text-danger">*</span>
                                <span id="dose-loading" class="spinner-border spinner-border-sm text-primary ms-1" role="status"></span>
                            </label>
                            <select name="dose_number" id="dose_number"
                                    class="form-select @error('dose_number') is-invalid @enderror" required>
                                <option value="">— Seleccione una vacuna primero —</option>
                            </select>
                            <input type="hidden" name="dose_label" id="dose_label" value="{{ old('dose_label') }}">
                            @error('dose_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Fecha de aplicación --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha de aplicación <span class="text-danger">*</span></label>
                            <input type="date" name="applied_date"
                                   class="form-control @error('applied_date') is-invalid @enderror"
                                   value="{{ old('applied_date', date('Y-m-d')) }}" required>
                            @error('applied_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Número de lote --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Número de lote</label>
                            <input type="text" name="lot_number" class="form-control"
                                   value="{{ old('lot_number') }}" placeholder="Ej: LOT-2024-001">
                        </div>

                        {{-- Aplicada por --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Aplicada por</label>
                            <input type="text" name="applied_by" class="form-control"
                                   value="{{ old('applied_by') }}" placeholder="Nombre del profesional">
                        </div>

                        {{-- Observaciones --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Observaciones</label>
                            <textarea name="notes" class="form-control" rows="3"
                                      placeholder="Reacciones, observaciones, condiciones especiales...">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Info --}}
                        <div class="col-12">
                            <div class="alert alert-info mb-0 d-flex align-items-start gap-2">
                                <i class="bx bx-info-circle font-size-18 flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong>Cálculo automático:</strong> Al guardar, el sistema calculará automáticamente
                                    la fecha de la próxima dosis según el esquema de vacunación y creará un registro
                                    pendiente para recordatorio.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    <a href="{{ route('vaccines.records.index') }}" class="btn btn-outline-secondary waves-effect">
                        <i class="bx bx-arrow-back me-1"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-save me-1"></i>Registrar Vacuna
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
{{-- Select2 JS --}}
<script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Select2 para paciente ──────────────────────────────────────
    $('#patient_id').select2({
        placeholder: '— Buscar paciente por nombre o DUI —',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return 'No se encontraron pacientes'; },
            searching:  function() { return 'Buscando...'; }
        }
    });

    // ── Carga dinámica de dosis al cambiar vacuna ──────────────────
    const vaccineSelect  = document.getElementById('vaccine_catalog_id');
    const doseSelect     = document.getElementById('dose_number');
    const doseLabelInput = document.getElementById('dose_label');
    const doseLoading    = document.getElementById('dose-loading');

    vaccineSelect.addEventListener('change', function () {
        const vaccineId = this.value;

        doseSelect.innerHTML = '<option value="">— Seleccionar dosis —</option>';
        doseLabelInput.value = '';

        if (!vaccineId) return;

        // Mostrar spinner
        doseLoading.style.display = 'inline-block';
        doseSelect.disabled = true;

        fetch(`/vaccines/schedule/${vaccineId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(data => {
            // El endpoint devuelve { vaccine: {...}, schedules: [...] }
            const schedules = Array.isArray(data) ? data : (data.schedules || []);

            doseSelect.innerHTML = '<option value="">— Seleccionar dosis —</option>';

            if (schedules.length === 0) {
                // Sin esquema definido → opción manual
                const opt = document.createElement('option');
                opt.value = 1;
                opt.dataset.label = 'Dosis única';
                opt.textContent = 'Dosis única (sin esquema definido)';
                doseSelect.appendChild(opt);
            } else {
                schedules.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.dose_number;
                    opt.dataset.label = s.dose_label;
                    const intervalo = s.days_after_previous === 0
                        ? 'Primera dosis'
                        : `${s.days_after_previous} días después de la anterior`;
                    opt.textContent = `${s.dose_label} — ${intervalo}`;
                    doseSelect.appendChild(opt);
                });
            }
        })
        .catch(err => {
            console.error('Error cargando dosis:', err);
            doseSelect.innerHTML = '<option value="">⚠ Error al cargar — intente de nuevo</option>';
        })
        .finally(() => {
            doseLoading.style.display = 'none';
            doseSelect.disabled = false;
        });
    });

    // Sincronizar dose_label al cambiar selección de dosis
    doseSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        doseLabelInput.value = selected ? (selected.dataset.label || selected.textContent) : '';
    });
});
</script>
@endsection
