<div class="repeater-wrapper mb-4" data-type="medicines">
    <label class="fw-bold mb-2">Medicamentos</label>

    <div class="repeater-container">
        <div class="repeater-item row mb-3 align-items-center">

            <div class="col-md-4">
                <input type="text" name="medicines[0][name]" class="form-control"
                       placeholder="Nombre del medicamento">
            </div>

            <div class="col-md-6">
                <textarea name="medicines[0][notes]" class="form-control"
                          placeholder="Dosis, vía, frecuencia"></textarea>
            </div>

            <div class="col-md-2 text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item">X</button>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-primary btn-sm btn-add-item">
            + Agregar medicamento
        </button>
        <button type="button" class="btn btn-success btn-sm ms-2" onclick="generarRecetaPDF()">
            <i class="bx bx-printer"></i> Imprimir/PDF Receta
        </button>
    </div>
</div>