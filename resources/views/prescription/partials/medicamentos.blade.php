<div class="repeater mb-4">
    <label class="fw-bold mb-2">Medicamentos</label>

    <div data-repeater-list="medicines">
        <div data-repeater-item class="row mb-3 align-items-center">

            <div class="col-md-4">
                <input type="text" name="name" class="form-control"
                       placeholder="Nombre del medicamento">
            </div>

            <div class="col-md-6">
                <textarea name="notes" class="form-control"
                          placeholder="Dosis, vía, frecuencia"></textarea>
            </div>

            <div class="col-md-2 text-center">
                <button data-repeater-delete type="button"
                        class="btn btn-outline-danger btn-sm">X</button>
            </div>

        </div>
    </div>

    <button data-repeater-create type="button"
            class="btn btn-primary btn-sm">
        + Agregar medicamento
    </button>
</div>