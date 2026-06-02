<div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Peso (lb)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="peso" id="peso_lb" class="form-control"
                                        value="{{ old('peso', $signos->peso ?? '') }}">
                                    <span class="input-group-text bg-light fw-bold text-primary" id="peso_kg_display">0.00 kg</span>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Talla (m)</label>
                                <input type="number" step="0.01" name="talla" class="form-control"
                                    value="{{ old('talla', $signos->talla ?? '') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Frecuencia Respiratoria</label>
                                <input type="number" name="frec_respiratoria" class="form-control"
                                    value="{{ old('frec_respiratoria', $signos->frec_respiratoria ?? '') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Temperatura (°C)</label>
                                <input type="number" step="0.1" name="temperatura" class="form-control"
                                    value="{{ old('temperatura', $signos->temperatura ?? '') }}">
                            </div>
</div>

<div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Presión Sistólica</label>
                                <input type="number" name="presion_arterial_sistolica" class="form-control"
                                    value="{{ old('presion_arterial_sistolica', $signos->presion_arterial_sistolica ?? '') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Presión Diastólica</label>
                                <input type="number" name="presion_arterial_diastolica" class="form-control"
                                    value="{{ old('presion_arterial_diastolica', $signos->presion_arterial_diastolica ?? '') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>Frecuencia Cardíaca</label>
                                <input type="number" name="frec_cardiaca" class="form-control"
                                    value="{{ old('frec_cardiaca', $signos->frec_cardiaca ?? '') }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>SpO₂ (%)</label>
                                <input type="number" name="spo" class="form-control"
                                    value="{{ old('spo', $signos->spo ?? '') }}">
                            </div>
</div>

<div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Examen físico</label>
                                <textarea name="examen" class="form-control">{{ old('examen', $signos->examen ?? '') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Observaciones adicionales</label>
                                <textarea name="observaciones_adicionales" class="form-control">{{ old('observaciones_adicionales', $signos->observaciones_adicionales ?? '') }}</textarea>
                            </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $(document).on('input', '#peso_lb', function() {
            let lbs = parseFloat($(this).val());
            if (!isNaN(lbs) && lbs > 0) {
                let kgs = lbs / 2.20462;
                $('#peso_kg_display').text(kgs.toFixed(2) + ' kg');
            } else {
                $('#peso_kg_display').text('0.00 kg');
            }
        });
        
        // Trigger on load in case it has a value already
        if ($('#peso_lb').val()) {
            $('#peso_lb').trigger('input');
        }
    });
</script>
