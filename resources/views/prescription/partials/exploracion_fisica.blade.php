<div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Peso (lb)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="peso" id="peso_lb" class="form-control">
                                    <span class="input-group-text bg-light fw-bold text-primary" id="peso_kg_display">0.00 kg</span>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Talla (m)</label>
                                <input type="number" step="0.01" name="talla" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Frecuencia Respiratoria</label>
                                <input type="number" name="frec_respiratoria" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Temperatura (°C)</label>
                                <input type="number" step="0.1" name="temperatura" class="form-control">
                            </div>
</div>

<div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Presión Sistólica</label>
                                <input type="number" name="presion_arterial_sistolica" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Presión Diastólica</label>
                                <input type="number" name="presion_arterial_diastolica" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Frecuencia Cardíaca</label>
                                <input type="number" name="frec_cardiaca" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>SpO₂ (%)</label>
                                <input type="number" name="spo" class="form-control">
                            </div>
</div>

<div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Examen físico</label>
                                <textarea name="examen" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Observaciones adicionales</label>
                                <textarea name="observaciones_adicionales" class="form-control"></textarea>
                            </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const pesoLbInput = document.getElementById('peso_lb');
        const pesoKgDisplay = document.getElementById('peso_kg_display');

        if (pesoLbInput) {
            pesoLbInput.addEventListener('input', function () {
                let lbs = parseFloat(this.value);
                if (!isNaN(lbs) && lbs > 0) {
                    let kgs = lbs / 2.20462;
                    pesoKgDisplay.textContent = kgs.toFixed(2) + ' kg';
                } else {
                    pesoKgDisplay.textContent = '0.00 kg';
                }
            });
        }
    });
</script>
