<div class="repeater-wrapper mb-4" data-type="archivos">
    <label class="fw-bold mb-2">Archivos clínicos</label>

    <div class="repeater-container">
        <div class="repeater-item row mb-3 align-items-center">

            <div class="col-md-5">
                <input type="file" name="archivos[0][file]" class="form-control">
            </div>

            <div class="col-md-5">
                <input type="text" name="archivos[0][observaciones]" class="form-control"
                       placeholder="Observaciones">
            </div>

            <div class="col-md-2 text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item">X</button>
            </div>

        </div>
    </div>

    <button type="button" class="btn btn-primary btn-sm btn-add-item">
        + Agregar archivo
    </button>
</div>