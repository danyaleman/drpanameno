<div class="repeater mb-4">
    <label class="fw-bold mb-2">Vacunas</label>

    <div data-repeater-list="vacunas">
        <div data-repeater-item class="row mb-3 align-items-center">

            <div class="col-md-5">
                <input type="text" name="tipo" class="form-control"
                       placeholder="Tipo de vacuna">
            </div>

            <div class="col-md-5">
                <input type="text" name="dosis" class="form-control"
                       placeholder="Dosis / Aplicación">
            </div>

            <div class="col-md-2 text-center">
                <button data-repeater-delete type="button"
                        class="btn btn-outline-danger btn-sm">X</button>
            </div>

        </div>
    </div>

    <button data-repeater-create type="button"
            class="btn btn-primary btn-sm">
        + Agregar vacuna
    </button>
</div>