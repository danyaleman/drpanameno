<div class="repeater mb-4">
    <label class="fw-bold mb-2">Archivos clínicos</label>

    <div data-repeater-list="archivos">
        <div data-repeater-item class="row mb-3 align-items-center">

            <div class="col-md-5">
                <input type="file" name="file" class="form-control">
            </div>

            <div class="col-md-5">
                <input type="text" name="observaciones" class="form-control"
                       placeholder="Observaciones">
            </div>

            <div class="col-md-2 text-center">
                <button data-repeater-delete type="button"
                        class="btn btn-outline-danger btn-sm">X</button>
            </div>

        </div>
    </div>

    <button data-repeater-create type="button"
            class="btn btn-primary btn-sm">
        + Agregar archivo
    </button>
</div>