<div class="repeater-wrapper mb-4" data-type="vacunas">
    <label class="fw-bold mb-2">Vacunas</label>

    <div class="repeater-container">
        <div class="repeater-item row mb-3 align-items-center">

            <div class="col-md-5">
                <input type="text" name="vacunas[0][tipo]" class="form-control"
                       placeholder="Tipo de vacuna">
            </div>

            <div class="col-md-5">
                <input type="text" name="vacunas[0][dosis]" class="form-control"
                       placeholder="Dosis / Aplicación">
            </div>

            <div class="col-md-2 text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item">X</button>
            </div>

        </div>
    </div>

    <button type="button" class="btn btn-primary btn-sm btn-add-item">
        + Agregar vacuna
    </button>
</div>