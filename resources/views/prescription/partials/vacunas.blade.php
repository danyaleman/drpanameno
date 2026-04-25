<div class="row g-3">
    <!-- INFO: Al guardar, el sistema calculará automáticamente la fecha de la próxima dosis -->
    <div class="col-12 mb-2">
        <div class="alert alert-info d-flex align-items-center mb-0 p-2" style="font-size: 13px;">
            <i class="bx bx-info-circle font-size-18 me-2"></i>
            Opcional. Al registrar una vacuna aquí, se guardará junto con la consulta médica.
        </div>
    </div>

    {{-- Vacuna --}}
    <div class="col-md-7">
        <label class="form-label fw-semibold">Vacuna</label>
        <select name="vaccine_catalog_id" id="vaccine_catalog_id" class="form-select select2-vaccine">
            <option value="">— No registrar vacuna —</option>
            @foreach($vaccines as $vaccine)
                <option value="{{ $vaccine->id }}">{{ $vaccine->name }} @if($vaccine->code) ({{ $vaccine->code }}) @endif</option>
            @endforeach
        </select>
    </div>

    {{-- Dosis --}}
    <div class="col-md-5">
        <label class="form-label fw-semibold">
            Dosis aplicada
            <span id="dose-loading" class="spinner-border spinner-border-sm text-primary ms-1" style="display:none;" role="status"></span>
        </label>
        <select name="dose_number" id="dose_number" class="form-select">
            <option value="">— Seleccione una vacuna primero —</option>
        </select>
        <input type="hidden" name="dose_label" id="dose_label">
    </div>

    {{-- Fecha de aplicación --}}
    <div class="col-md-4 mt-3">
        <label class="form-label fw-semibold">Fecha de aplicación</label>
        <input type="date" name="applied_date" class="form-control" value="{{ date('Y-m-d') }}">
    </div>

    {{-- Número de lote --}}
    <div class="col-md-4 mt-3">
        <label class="form-label fw-semibold">Número de lote</label>
        <input type="text" name="lot_number" class="form-control" placeholder="Ej: LOT-2024-001">
    </div>

    {{-- Aplicada por --}}
    <div class="col-md-4 mt-3">
        <label class="form-label fw-semibold">Aplicada por</label>
        <input type="text" name="applied_by" class="form-control" placeholder="Nombre del profesional">
    </div>

    {{-- Observaciones --}}
    <div class="col-12 mt-3">
        <label class="form-label fw-semibold">Observaciones de Vacunación</label>
        <textarea name="vaccine_notes" class="form-control" rows="2" placeholder="Reacciones, observaciones, condiciones especiales..."></textarea>
    </div>
</div>