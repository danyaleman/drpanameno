@extends('layouts.master-layouts')

@section('title', 'Editar Consulta')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
@endsection

@section('content')

{{-- ENCABEZADO DINÁMICO DEL PACIENTE --}}
<div class="card mb-3 shadow-lg" id="patient-header-card" style="background: linear-gradient(135deg, #556ee6 0%, #34469d 100%); color: white; border-radius: 10px;">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="avatar-lg me-3">
                <img src="" id="patient_img" class="img-fluid rounded-circle border border-white" style="width: 80px; height: 80px; object-fit: cover; display: none; border-width: 3px !important;">
                <div id="patient_initials" class="rounded-circle bg-light text-primary d-flex justify-content-center align-items-center" style="width: 80px; height: 80px; font-size: 32px; font-weight: bold;">
                    <i class="bx bx-user"></i>
                </div>
            </div>
            <div>
                <h2 id="patient_name" class="mb-1 text-white" style="font-weight: 700; text-transform: uppercase;">
                    @if(isset($preloadPatient))
                        {{ $preloadPatient->first_name }} {{ $preloadPatient->last_name }}
                    @else
                        Seleccione un paciente
                    @endif
                </h2>
                <h5 id="patient_info" class="text-white-50 mb-0"></h5>
            </div>
        </div>
    </div>
</div>

<form action="{{ url('prescription/' . $prescription->id) }}" method="POST" enctype="multipart/form-data" id="prescription-form" style="">
@csrf
<input type="hidden" name="_method" value="PATCH" />
<input type="hidden" name="id" value="{{ $prescription->id }}" id="form_id" />

<input type="hidden" name="patient_id_hidden" id="patient_id_hidden" value="{{ $prescription->patient_id ?? '' }}">
<input type="hidden" name="appointment_id" id="appointment_id_hidden" value="{{ $prescription->appointment_id ?? '' }}">
<input type="hidden" id="info_dui_hidden" value="">

<div class="row">

    {{-- ================= COLUMNA IZQUIERDA ================= --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 10px;">
            <div class="card-body p-4">
                {{-- TABS --}}
                <ul class="nav nav-pills nav-justified bg-light rounded mb-4" role="tablist" style="padding: 6px;">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-info-general" role="tab" style="font-weight: 600;">
                            <i class="bx bx-id-card font-size-18 d-block mb-1"></i> Info General
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-consulta" role="tab" style="font-weight: 600;">
                            <i class="bx bx-detail font-size-18 d-block mb-1"></i> Consulta
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-exploracion" role="tab" style="font-weight: 600;">
                            <i class="bx bx-body font-size-18 d-block mb-1"></i> Examen Físico
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-evaluacion" role="tab" style="font-weight: 600;">
                            <i class="bx bx-file font-size-18 d-block mb-1"></i> Evaluación y Receta
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-vacunas" role="tab" style="font-weight: 600;">
                            <i class="fas fa-syringe font-size-18 d-block mb-1"></i> Vacunas
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-imagenes" role="tab" style="font-weight: 600;">
                            <i class="bx bx-images font-size-18 d-block mb-1"></i> Imágenes
                        </a>
                    </li>
                </ul>

                <div class="tab-content text-muted">

            {{-- TAB: INFORMACIÓN GENERAL (SOLO LECTURA) --}}
            <div class="tab-pane fade show active" id="tab-info-general">
                <div class="card border-0 shadow-sm mb-0" style="border-radius: 12px;">
                    <div class="card-header bg-primary text-white" style="border-radius: 12px 12px 0 0; border: none; padding: 16px 20px;">
                        <h5 class="mb-0 text-white font-size-16"><i class="bx bx-user-circle me-2 align-middle"></i><strong>Datos Demográficos y de Contacto</strong></h5>
                    </div>
                    <div class="card-body bg-light-subtle p-4" style="border-radius: 0 0 12px 12px;">
                        
                        <div class="row g-4">
                            <!-- Col 1 -->
                            <div class="col-md-6 col-xl-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-20">
                                            <i class="bx bx-id-card"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Nombre Completo</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold" id="info_full_name">-</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-20">
                                            <i class="bx bx-calendar"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Edad y Nacimiento</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold"><span id="info_age">-</span> <span class="text-muted font-size-13 fw-normal ms-1 border-start ps-2" id="info_dob">-</span></h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-20">
                                            <i class="bx bx-male-female"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Género y Est. Civil</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold"><span id="info_gender">-</span> <span class="text-muted font-size-13 fw-normal ms-1 border-start ps-2" id="info_marital">-</span></h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Col 2 -->
                            <div class="col-md-6 col-xl-4 border-start border-light-subtle">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-success-subtle text-success rounded-circle font-size-20">
                                            <i class="bx bx-phone-call"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Teléfono Móvil</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold" id="info_mobile">-</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle font-size-20">
                                            <i class="bx bx-envelope"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Email</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold" style="word-break: break-all;" id="info_email">-</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-danger-subtle text-danger rounded-circle font-size-20">
                                            <i class="bx bx-donate-blood"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Tipo Sangre</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold" id="info_blood">-</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Col 3 -->
                            <div class="col-md-12 col-xl-4 border-start border-light-subtle">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-warning-subtle text-warning rounded-circle font-size-20">
                                            <i class="bx bx-briefcase"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Profesión o Trabajo</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold"><span id="info_occupation">-</span> <span class="text-muted font-size-13 fw-normal ms-1 border-start ps-2" id="info_workplace" title="Lugar de Trabajo">-</span></h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-secondary text-white rounded-circle font-size-20">
                                            <i class="bx bx-user-plus"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Referido por</p>
                                        <h5 class="font-size-14 mb-0 text-dark fw-bold" id="info_referred_by">-</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div class="avatar-sm me-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle font-size-20">
                                            <i class="bx bx-map"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1 font-size-12 text-uppercase fw-semibold">Dirección</p>
                                        <h5 class="font-size-13 mb-0 text-dark fw-bold" style="line-height:1.4;" id="info_address">-</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="tab-pane fade" id="tab-consulta">

                {{-- ── SECCIÓN: TIPO Y COSTO DE CONSULTA ── --}}
                <div class="card border-0 mb-4" style="background: linear-gradient(135deg,#f0f4ff 0%,#e8f0fe 100%); border-radius:12px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bx bx-dollar-circle me-1"></i> Tipo y Costo de Consulta
                        </h6>

                        <div class="row g-3">
                            {{-- Tipo de Consulta --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="bx bx-list-ul me-1 text-primary"></i> Tipo de Consulta
                                </label>
                                <select name="tipo_consulta_id" id="tipo_consulta_id" class="form-select">
                                    <option value="">— Seleccionar tipo —</option>
                                    @foreach($tipoConsultas as $tc)
                                        <option value="{{ $tc->id }}"
                                            {{ old('tipo_consulta_id', $prescription->tipo_consulta_id) == $tc->id ? 'selected' : '' }}>
                                            {{ $tc->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Código de tarifa --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="bx bx-barcode me-1 text-primary"></i> Código / Tarifa
                                    <span id="codigo-loading" class="spinner-border spinner-border-sm text-primary ms-1" style="display:none;"></span>
                                </label>
                                <select name="codigo_id" id="codigo_id" class="form-select"
                                    data-current="{{ $prescription->codigo_id }}">
                                    <option value="">— Seleccione tipo primero —</option>
                                </select>
                            </div>

                            {{-- Precio editable --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="bx bx-money me-1 text-success"></i> Precio Consulta
                                    <small class="text-muted fw-normal">(editable)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white fw-bold border-0">$</span>
                                    <input type="number" name="precio_consulta" id="precio_consulta"
                                        class="form-control fw-bold fs-5"
                                        step="0.01" min="0"
                                        value="{{ old('precio_consulta', $prescription->precio_consulta) }}"
                                        placeholder="0.00"
                                        style="color:#198754;">
                                </div>
                                <small class="text-muted">Puede ajustar el precio a criterio del médico.</small>
                            </div>
                        </div>

                        {{-- Resumen visual del costo --}}
                        <div id="precio-resumen" class="mt-3 p-3 rounded-3 {{ $prescription->tipo_consulta_id ? '' : 'd-none' }}"
                            style="background:rgba(25,135,84,0.08); border:1px solid rgba(25,135,84,0.2);">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <span class="text-muted me-2" style="font-size:13px;">Tipo:</span>
                                    <strong id="res-tipo" class="text-dark">{{ optional($prescription->tipoConsulta)->nombre ?? '—' }}</strong>
                                    <span class="mx-2 text-muted">·</span>
                                    <span class="text-muted me-2" style="font-size:13px;">Código:</span>
                                    <strong id="res-codigo" class="text-dark">{{ optional($prescription->codigo)->codigo ?? '—' }}</strong>
                                </div>
                                <div>
                                    <span class="badge bg-success-subtle text-success px-3 py-2 fs-6 fw-bold">
                                        <i class="bx bx-dollar me-1"></i>
                                        <span id="res-precio">{{ number_format($prescription->precio_consulta ?? 0, 2) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SECCIÓN: MOTIVO E HISTORIA ── --}}
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fw-semibold text-dark mb-0">
                                <i class="bx bx-message-rounded-dots me-1 text-primary"></i> Consulta por / Motivo
                            </label>
                            <button type="button" class="btn-mic" id="mic-consulta_por"
                                data-target="consulta_por" title="Dictar con voz">
                                <i class="bx bx-microphone"></i>
                                <span class="mic-label">Dictar</span>
                            </button>
                        </div>
                        <textarea name="consulta_por" id="consulta_por" class="form-control" rows="3"
                            placeholder="Describa el motivo principal de la consulta...">{{ old('consulta_por', $prescription->consulta_por) }}</textarea>
                        <div id="status-consulta_por" class="mic-status d-none"></div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fw-semibold text-dark mb-0">
                                <i class="bx bx-history me-1"></i> Historia Clínica
                                <span class="badge bg-secondary-subtle text-secondary ms-1 fw-normal" style="font-size:10px;">
                                    Queda registrada en el expediente
                                </span>
                            </label>
                            <button type="button" class="btn-mic" id="mic-diagnosis"
                                data-target="diagnosis" title="Dictar con voz">
                                <i class="bx bx-microphone"></i>
                                <span class="mic-label">Dictar</span>
                            </button>
                        </div>
                        <textarea name="diagnosis" id="diagnosis" class="form-control" rows="5"
                            placeholder="Resumen de antecedentes relevantes, evolución del padecimiento, procedimientos previos, tratamientos anteriores...">{{ old('diagnosis', $prescription->diagnosis) }}</textarea>
                        <div id="status-diagnosis" class="mic-status d-none"></div>
                    </div>
                </div>

            </div>


            {{-- EXPLORACIÓN FÍSICA --}}
            <div class="tab-pane fade" id="tab-exploracion">
                @include('prescription.partials.exploracion_fisica')
            </div>

            {{-- EVALUACIÓN Y RECETA (MERGED) --}}
            <div class="tab-pane fade" id="tab-evaluacion">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="fw-bold">Diagnóstico (Evaluación)</label>
                        <textarea name="diagnostico" class="form-control" rows="3" placeholder="Describa el diagnóstico...">{{ old('diagnostico', $prescription->diagnosis) }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold">Estudios de Laboratorio</label>
                        <textarea name="estudios_laboratorios" class="form-control" rows="3" placeholder="Exámenes a realizar...">{{ optional(\App\Evaluacion::where('prescription_id', $prescription->id)->first())->estudios_laboratorios }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold">Tratamiento / Receta Médica</label>
                        <textarea name="tratamiento" id="tratamiento_texto" class="form-control" rows="6" placeholder="Escriba aquí todo el tratamiento, medicamentos, indicaciones y dosis recomendadas...">{{ optional(\App\Evaluacion::where('prescription_id', $prescription->id)->first())->medicamentos }}</textarea>
                        
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-success fw-bold shadow-sm" style="border-radius: 6px;" onclick="generarRecetaPDF()">
                                <i class="bx bx-printer align-middle me-1 font-size-16"></i> Imprimir Receta / Generar PDF
                            </button>
                            <small class="text-muted d-block mt-1">Imprimirá el contenido del campo "Tratamiento / Receta Médica".</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VACUNAS --}}
            <div class="tab-pane fade" id="tab-vacunas">
                @include('prescription.partials.vacunas')
            </div>

            {{-- IMÁGENES --}}
            <div class="tab-pane fade" id="tab-imagenes">
                @if(isset($prescription) && $prescription->archivos->count())
                    <hr>
                    <h5>Archivos Clínicos Guardados</h5>
                    <table class="table table-bordered" id="tabla-archivos-guardados">
                        <thead>
                            <tr>
                                <th>Archivo</th>
                                <th>Observaciones</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescription->archivos as $archivo)
                                <tr id="fila-archivo-{{ $archivo->id }}">
                                    <td>
                                        <a href="{{ asset('storage/'.$archivo->url_file) }}" target="_blank">
                                            {{ basename($archivo->url_file) }}
                                        </a>
                                    </td>
                                    <td>{{ $archivo->observaciones }}</td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-danger btn-sm btn-eliminar-archivo"
                                            data-id="{{ $archivo->id }}"
                                            data-url="{{ route('archivo.destroy', $archivo->id) }}"
                                            data-token="{{ csrf_token() }}">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <br/>@include('prescription.partials.archivos')
            </div>

                </div> <!-- End tab-content -->
            </div> <!-- End card-body -->
        </div> <!-- End card -->
    </div>

    {{-- ================= COLUMNA DERECHA ================= --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0" style="border-radius: 10px;">
            <div class="card-header bg-primary text-white" style="border-radius: 10px 10px 0 0; padding: 15px 20px;">
                <h5 class="mb-0 text-white font-size-16"><i class="bx bx-history me-2"></i><strong>Antecedentes y Alergias</strong></h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Patológicos</label>
                    <textarea id="patologicos" name="pathological_history" class="form-control" rows="3" placeholder="Antecedentes médicos, cirugías, etc."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Familiares</label>
                    <textarea id="familiares" name="non_pathological_history" class="form-control" rows="3" placeholder="Enfermedades hereditarias, hábitos..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-danger"><i class="bx bx-error-circle"></i> Alergias</label>
                    <textarea id="alergias" name="medications_allergies" class="form-control border-danger" rows="3" placeholder="Medicamentos, alimentos, etc."></textarea>
                </div>

                <hr class="text-muted mb-4">

                <div class="d-grid mt-2">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 8px; font-weight: bold;">
                        <i class="bx bx-save me-1 font-size-18 align-middle"></i> Actualizar Consulta
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

</form>

{{-- ================= HISTORIA CLÍNICA (CONSULTAS PREVIAS) ================= --}}
<div class="row mt-4" id="clinical-history-section" style="display: none;">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 10px;">
            <div class="card-header bg-dark text-white" style="border-radius: 10px 10px 0 0; padding: 15px 20px;">
                <h5 class="mb-0 text-white font-size-16"><i class="bx bx-list-ul me-2"></i><strong>Historia Clínica (Últimas Consultas)</strong></h5>
            </div>
            <div class="card-body bg-light-subtle p-4" style="border-radius: 0 0 10px 10px;">
                <div class="accordion accordion-flush" id="historyAccordion">
                    <!-- Se llenará vía AJAX -->
                </div>
                <div id="no-history-msg" class="text-center text-muted" style="display: none;">
                    <i class="bx bx-info-circle font-size-24 mb-2"></i>
                    <p>No se encontraron consultas previas para este paciente.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>

<script>
$(document).ready(function () {
    // Inicializar Select2
    $('.select2').select2({
        placeholder: 'Seleccionar',
        allowClear: true,
        width: '100%'
    });

    // Verificar si hay precarga de paciente (viniendo desde calendario)
    var preloadPatientId = '{{ $prescription->patient_id }}';
    var preloadAppointmentId = '{{ $prescription->appointment_id }}';
    
    if (preloadPatientId) {
        // Si viene precargado, cargar automáticamente la información (NO es manual)
        loadPatientInfo(preloadPatientId, false);
    }

    // Función para cargar información del paciente
    function loadPatientInfo(patientId, isManualSelection = false) {
        console.log('Cargando paciente ID:', patientId, 'Manual:', isManualSelection);

        $.ajax({
            url: "{{ route('patient_clinical_info') }}",
            type: "POST",
            data: {
                patient_id: patientId,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                console.log('RESPUESTA BACKEND:', res);

                if (res.isSuccess) {
                    // Citas: (Removidos en edición)
                    // Si hay una cita precargada y NO es selección manual, seleccionarla
                    // if (!isManualSelection && preloadAppointmentId) {
                    //      $('.sel_appointment').val(preloadAppointmentId).trigger('change');
                    // }

                    // Encabezado
                    $('#patient_name').text(res.patient.name);
                    $('#patient_info').text(res.patient.info);

                    // Imagen o Iniciales (Verificación segura)
                    var firstName = res.patient.first_name || '';
                    var lastName = res.patient.last_name || '';
                    
                    if (res.patient.profile_photo_url) {
                        $('#patient_img').attr('src', res.patient.profile_photo_url).show();
                        $('#patient_initials').removeClass('d-flex').hide();
                    } else {
                        $('#patient_img').hide();
                        var initials = (firstName.charAt(0) + lastName.charAt(0)).toUpperCase();
                        $('#patient_initials').text(initials).addClass('d-flex').show();
                    }

                    // Llenar Tab Información General
                    $('#info_full_name').text(res.patient.name);
                    $('#info_dui_hidden').val(res.patient.dui ?? '');
                    $('#info_age').text(res.patient.age ? res.patient.age + ' años' : '-');
                    $('#info_dob').text(res.patient.dob ?? '-');
                    $('#info_gender').text(res.patient.gender ?? '-');
                    $('#info_mobile').text(res.patient.mobile ?? '-');
                    $('#info_email').text(res.patient.email ?? '-');
                    $('#info_occupation').text(res.patient.occupation ?? '-');
                    $('#info_address').text(res.patient.address ?? '-');
                    $('#info_marital').text(res.patient.marital_status ?? '-');
                    $('#info_blood').text(res.patient.blood_group ?? '-');
                    $('#info_workplace').text(res.patient.workplace ?? '-');
                    $('#info_referred_by').text(res.patient.referred_by ?? '-');

                    // Antecedentes (Sidebar)
                    $('#patologicos').val(res.patient.pathological_history ?? '');
                    $('#familiares').val(res.patient.non_pathological_history ?? '');
                    $('#alergias').val(res.patient.medications_allergies ?? '');

                    // Signos Vitales (Examen Físico)
                    if (res.patient.signos) {
                        $('input[name="peso"]').val(res.patient.signos.peso ?? '').trigger('input'); // Trigger input for kg calculation
                        $('input[name="talla"]').val(res.patient.signos.talla ?? '');
                        $('input[name="frec_respiratoria"]').val(res.patient.signos.frec_respiratoria ?? '');
                        $('input[name="temperatura"]').val(res.patient.signos.temperatura ?? '');
                        $('input[name="presion_arterial_sistolica"]').val(res.patient.signos.presion_arterial_sistolica ?? '');
                        $('input[name="presion_arterial_diastolica"]').val(res.patient.signos.presion_arterial_diastolica ?? '');
                        $('input[name="frec_cardiaca"]').val(res.patient.signos.frec_cardiaca ?? '');
                        $('input[name="spo"]').val(res.patient.signos.spo ?? '');
                        $('textarea[name="examen"]').val('');
                        $('textarea[name="observaciones_adicionales"]').val('');
                    } else {
                        // Limpiar si no hay previos
                        $('#tab-exploracion input, #tab-exploracion textarea').val('');
                        $('#peso_kg_display').text('0.00 kg');
                    }

                    // Mostrar formulario y header
                    $('#patient-header-card').slideDown();
                    $('#prescription-form').slideDown();

                    // Cargar Historia Clínica
                    if (res.patient.historial && res.patient.historial.length > 0) {
                        let html = '';
                        res.patient.historial.forEach(function(item, index) {
                            let vacunasHtml = '';
                            if (item.vacunas && item.vacunas.length > 0) {
                                vacunasHtml = '<div class="mt-2 text-primary"><strong>Vacunas:</strong><ul class="mb-0 ps-3">';
                                item.vacunas.forEach(function(v) { vacunasHtml += `<li>${v.name || 'N/A'}</li>`; });
                                vacunasHtml += '</ul></div>';
                            }

                            let examenesHtml = '';
                            if (item.evaluacion && item.evaluacion.estudios_laboratorios) {
                                examenesHtml = `<div class="mt-2 text-info"><strong>Laboratorios recomendados:</strong> <p class="mb-0">${item.evaluacion.estudios_laboratorios}</p></div>`;
                            }

                            let archivosHtml = '';
                            if (item.archivos && item.archivos.length > 0) {
                                archivosHtml = '<div class="mt-2 text-secondary"><strong>Imágenes o Archivos:</strong><ul class="mb-0 ps-3">';
                                item.archivos.forEach(function(a) { 
                                    archivosHtml += `<li><i class="bx bx-image"></i> ${a.titulo || 'Archivo Adjunto'}</li>`; 
                                });
                                archivosHtml += '</ul></div>';
                            }

                            html += `
                            <div class="accordion-item shadow-sm border mb-2" style="border-radius: 8px; overflow: hidden;">
                                <h2 class="accordion-header" id="heading-${index}">
                                    <button class="accordion-button fw-bold text-dark bg-white ${index !== 0 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="${index === 0 ? 'true' : 'false'}" aria-controls="collapse-${index}">
                                        <i class="bx bx-calendar-event text-primary me-2"></i> Consulta del ${item.date} 
                                        <span class="badge bg-primary-subtle text-primary ms-auto" style="font-size: 11px;">#${item.id}</span>
                                    </button>
                                </h2>
                                <div id="collapse-${index}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading-${index}" data-bs-parent="#historyAccordion">
                                    <div class="accordion-body bg-light-subtle row p-4">
                                        <div class="col-md-6 border-end">
                                            <strong class="text-muted text-uppercase font-size-11"><i class="bx bx-message-rounded-dots"></i> Consulta Por:</strong>
                                            <p class="mt-1">${item.consulta_por || 'No especificado'}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong class="text-muted text-uppercase font-size-11"><i class="bx bx-file"></i> Evaluación / Diagnóstico:</strong>
                                            <p class="mt-1 mb-0">${item.diagnostico || 'No especificado'}</p>
                                            ${examenesHtml}
                                            ${vacunasHtml}
                                            ${archivosHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });
                        $('#historyAccordion').html(html).show();
                        $('#no-history-msg').hide();
                        $('#clinical-history-section').slideDown();
                    } else {
                        $('#historyAccordion').hide();
                        $('#no-history-msg').show();
                        $('#clinical-history-section').slideDown();
                    }

                    // Actualizar campos hidden
                    $('#patient_id_hidden').val(patientId);
                } else {
                    $('#patient_name').text('Paciente sin perfil médico');
                    alert(res.message);
                }
            },
            error: function (err) {
                console.error('ERROR AJAX:', err);
                $('#patient_name').text('Error al cargar datos');
            }
        });
    }

    // Evento al seleccionar paciente manualmente (Revertido a select2:select con delay de seguridad)
    $('.sel_patient').on('select2:select', function (e) {
        let data = e.params.data;
        let patientId = data.id;
        
        console.log('Evento SELECT2 disparado. ID:', patientId);
        
        // Limpiar campos visualmente
        $('#patient_name').text('Cargando...');
        $('#patient_info').text('Obteniendo datos...');

        // Pequeño delay para asegurar que el DOM y variables internas de Select2 se asienten
        setTimeout(function() {
            loadPatientInfo(patientId, true);
        }, 50);
    });

    // ── Carga dinámica de CÓDIGOS al cambiar Tipo de Consulta ──────
    const tipoSelect    = document.getElementById('tipo_consulta_id');
    const codigoSelect  = document.getElementById('codigo_id');
    const precioInput   = document.getElementById('precio_consulta');
    const codigoLoading = document.getElementById('codigo-loading');
    const precioResumen = document.getElementById('precio-resumen');

    function actualizarResumen() {
        const tipoText   = tipoSelect ? tipoSelect.options[tipoSelect.selectedIndex]?.text : '';
        const codigoText = codigoSelect ? codigoSelect.options[codigoSelect.selectedIndex]?.text : '';
        const precio     = precioInput ? parseFloat(precioInput.value || 0).toFixed(2) : '0.00';
        if (tipoText && tipoText !== '— Seleccionar tipo —') {
            document.getElementById('res-tipo').textContent   = tipoText;
            document.getElementById('res-codigo').textContent = codigoText || '—';
            document.getElementById('res-precio').textContent = precio;
            precioResumen.classList.remove('d-none');
        } else {
            precioResumen.classList.add('d-none');
        }
    }

    function cargarCodigos(tipoId, currentCodigoId, callback) {
        if (!tipoId) return;
        codigoLoading.style.display = 'inline-block';
        codigoSelect.disabled = true;

        fetch(`/prescription/codigos-por-tipo/${tipoId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            codigoSelect.innerHTML = '<option value="">— Seleccionar código —</option>';
            data.forEach(c => {
                const opt = document.createElement('option');
                opt.value          = c.id;
                opt.dataset.precio = c.precio;
                opt.textContent    = `${c.codigo}  ($${parseFloat(c.precio).toFixed(2)})`;
                if (currentCodigoId && c.id == currentCodigoId) opt.selected = true;
                codigoSelect.appendChild(opt);
            });
            codigoSelect.disabled = false;
            if (callback) callback();
        })
        .catch(err => {
            console.error('Error codigos:', err);
            codigoSelect.innerHTML = '<option value="">⚠ Error — intente de nuevo</option>';
        })
        .finally(() => { codigoLoading.style.display = 'none'; });
    }

    if (tipoSelect) {
        // Pre-cargar códigos si hay tipo ya seleccionado (modo edición)
        const initTipoId    = tipoSelect.value;
        const initCodigoId  = codigoSelect ? codigoSelect.dataset.current : null;
        if (initTipoId) {
            cargarCodigos(initTipoId, initCodigoId, actualizarResumen);
        }

        tipoSelect.addEventListener('change', function () {
            precioInput.value = '';
            cargarCodigos(this.value, null, actualizarResumen);
            actualizarResumen();
        });

        codigoSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            if (selected && selected.dataset.precio !== undefined) {
                precioInput.value = parseFloat(selected.dataset.precio).toFixed(2);
            }
            actualizarResumen();
        });

        precioInput.addEventListener('input', actualizarResumen);
    }

    // ── Carga dinámica de dosis al cambiar vacuna ──────────────────

    const vaccineSelect  = document.getElementById('vaccine_catalog_id');
    const doseSelect     = document.getElementById('dose_number');
    const doseLabelInput = document.getElementById('dose_label');
    const doseLoading    = document.getElementById('dose-loading');

    if (vaccineSelect) {
        // Inicializar Select2 para la vacuna
        $(vaccineSelect).select2({
            placeholder: '— No registrar vacuna —',
            allowClear: true,
            width: '100%'
        });

        $(vaccineSelect).on('change', function () {
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
                const schedules = Array.isArray(data) ? data : (data.schedules || []);

                doseSelect.innerHTML = '<option value="">— Seleccionar dosis —</option>';

                if (schedules.length === 0) {
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

        doseSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            doseLabelInput.value = selected ? (selected.dataset.label || selected.textContent) : '';
        });
    }

    /* =========================================================
     *  LOGICA MANUAL PARA REPEATER (SIN LIBRERÍA EXTERNA)
     * ========================================================= */
    
    // Agregar Item
    $(document).on('click', '.btn-add-item', function() {
        let wrapper = $(this).closest('.repeater-wrapper');
        let container = wrapper.find('.repeater-container');
        let type = wrapper.attr('data-type'); // 'medicines', 'vacunas', 'archivos'
        
        // Clonar el primer item (plantilla base)
        let newItem = container.find('.repeater-item').first().clone();
        
        // Limpiar inputs del nuevo item
        newItem.find('input, textarea, select').val('');
        
        // Calcular nuevo índice basado en cantidad actual
        let newIndex = container.find('.repeater-item').length;
        
        // Actualizar nombres de inputs con el nuevo índice
        newItem.find('input, textarea, select').each(function() {
            let name = $(this).attr('name');
            if (name) {
                // Reemplaza medicines[0][algo] por medicines[1][algo]
                // Se usa regex para reemplazar el primer número entre corchetes
                let newName = name.replace(/\[\d+\]/, '[' + newIndex + ']');
                $(this).attr('name', newName);
            }
        });
        
        // Mostrar (por si estaba oculto) y agregar
        newItem.show();
        container.append(newItem);
    });

    // Eliminar Item
    $(document).on('click', '.btn-remove-item', function() {
        let container = $(this).closest('.repeater-container');
        
        // Validar que quede al menos uno
        if (container.find('.repeater-item').length > 1) {
            if(confirm('¿Eliminar esta fila?')) {
                $(this).closest('.repeater-item').remove();
                // (Opcional: Reindexar nombres si fuera estricto, pero PHP suele manejar índices salteados en POST si es array)
            }
        } else {
            alert('Debe quedar al menos un registro.');
        }
    });

    // ── Eliminar archivos guardados vía AJAX ─────────────────────────
    $(document).on('click', '.btn-eliminar-archivo', function () {
        const btn   = $(this);
        const id    = btn.data('id');
        const url   = btn.data('url');
        const token = btn.data('token');

        if (!confirm('¿Eliminar este archivo?')) return;

        btn.prop('disabled', true).text('Eliminando...');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-HTTP-Method-Override': 'DELETE',
                'Accept': 'application/json',
            },
            body: new URLSearchParams({ _method: 'DELETE', _token: token })
        })
        .then(r => {
            if (r.ok || r.redirected) {
                // Quitar la fila de la tabla
                $('#fila-archivo-' + id).fadeOut(300, function () {
                    $(this).remove();
                    // Si ya no quedan filas, ocultar toda la sección
                    if ($('#tabla-archivos-guardados tbody tr').length === 0) {
                        $('#tabla-archivos-guardados').closest('div').prev('hr').remove();
                        $('#tabla-archivos-guardados').closest('div').find('h5').remove();
                        $('#tabla-archivos-guardados').remove();
                    }
                });
            } else {
                alert('Error al eliminar el archivo. Intente de nuevo.');
                btn.prop('disabled', false).text('Eliminar');
            }
        })
        .catch(() => {
            alert('Error de conexión al eliminar el archivo.');
            btn.prop('disabled', false).text('Eliminar');
        });
    });

    window.generarRecetaPDF = function() {
        let pName = $('#info_full_name').text();
        let pDui = $('#info_dui_hidden').val() || '-';
        
        let dateObj = new Date();
        let today = dateObj.toLocaleDateString('es-ES');

        let medsText = $('#tratamiento_texto').val();
        let medicinesHtml = '';
        
        if (medsText && medsText.trim() !== '') {
            medicinesHtml = `
            <div style="margin-bottom: 12px; font-size: 13px; color: #111; white-space: pre-wrap; line-height: 1.6;">${medsText.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>`;
        }

        if (medicinesHtml === '') {
            alert('Por favor, escriba el tratamiento/receta médida en la pestaña "Evaluación y Receta" para poder imprimir o generar el PDF.');
            return;
        }

        let logoUrl = "{{ asset('build/images/logo-dark2.png') }}";

        let printWindow = window.open('', '_blank');
        let htmlContent = `
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Orden Médica - ${pName}</title>
            <style>
                /* Forzamos el tamaño físico del papel en el navegador (Medio Carta / Statement) */
                @page { 
                    size: 5.5in 8.5in; /* equivale a 13.97cm de ancho x 21.59cm de alto */
                    margin: 0; 
                }
                html, body {
                    width: 13.97cm;
                    height: 21.59cm;
                    margin: 0 !important;
                    padding: 0 !important;
                    background-color: #fff;
                }
                body {
                    font-family: Arial, sans-serif;
                    color: #000;
                    -webkit-print-color-adjust: exact;
                }
                .recipe-container {
                    width: 100%;
                    height: 100%;
                    padding: 0.8cm 1cm; /* Margen interno para que no pegue a la orilla del papel */
                    box-sizing: border-box;
                    margin: 0;
                    display: flex;
                    flex-direction: column;
                    background-color: #fff;
                }
                .header-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .header-table td {
                    vertical-align: middle;
                }
                .doctor-info {
                    text-align: center;
                }
                .doctor-name {
                    font-size: 22px;
                    font-weight: normal;
                    margin-bottom: 8px;
                    color: #000;
                    letter-spacing: -0.3px;
                }
                .doctor-spec {
                    font-size: 12px;
                    color: #111;
                    line-height: 1.3;
                }
                .title-receta {
                    text-align: center;
                    font-size: 16px;
                    margin-top: 15px;
                    margin-bottom: 15px;
                }
                .patient-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                    background-color: #fff;
                }
                .patient-table td {
                    border: 1px solid #555;
                    padding: 6px 8px;
                    font-size: 12px;
                }
                .patient-table .label-td {
                    font-weight: normal;
                    width: 60px;
                }
                .patient-table .val-td {
                    width: 55%;
                }
                .patient-table .empty-td {
                    width: auto;
                }
                .meds-box {
                    border: 1px solid #555;
                    flex-grow: 1;
                    padding: 15px;
                    margin-bottom: 15px;
                    background-color: #fff;
                }
                .footer-box {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                    background-color: #fff;
                }
                .footer-box td {
                    border: 1px solid #555;
                    text-align: center;
                    font-size: 11px;
                    padding: 6px;
                }
                .address {
                    text-align: center;
                    font-size: 10px;
                    color: #111;
                    margin-bottom: 5px;
                }
            </style>
        </head>
        <body>
            <div class="recipe-container">
                <table class="header-table">
                    <tr>
                        <td style="width: 25%; text-align: center;">
                            <img src="${logoUrl}" style="max-width: 80px;" alt="Logo">
                        </td>
                        <td style="width: 75%;" class="doctor-info">
                            <div class="doctor-name">Dr. Jorge Panameño MSc.</div>
                            <div class="doctor-spec">Medicina Interna – Medicina Tropical<br>Enfermedades Infecciosas y Parasitarias</div>
                            <div class="doctor-spec" style="font-size:10px; margin-top:3px;">Miembro de IDSA, Sociedad Americana de Enfermedades Infecciosas</div>
                        </td>
                    </tr>
                </table>
                
                <div class="title-receta">Orden Médica</div>
                
                <table class="patient-table">
                    <tr>
                        <td class="label-td">Fecha:</td>
                        <td class="val-td">${today}</td>
                        <td rowspan="3" class="empty-td">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="label-td">Nombre:</td>
                        <td class="val-td">${pName}</td>
                    </tr>
                    <tr>
                        <td class="label-td">No. Id:</td>
                        <td class="val-td">${pDui}</td>
                    </tr>
                </table>
                
                <div class="meds-box">
                    ${medicinesHtml}
                </div>
                
                <table class="footer-box">
                    <tr><td>Receta Exclusiva. Cada caso es diferente, no se automedique</td></tr>
                    <tr><td>Enviar resultados vía WhatsApp al +503 7989-2046</td></tr>
                </table>
                
                <div class="address">
                    81 Av Sur Cl Juan J Cañas Edif 2 Nivel 2 Local 6 Centro Médico Escalón, SS. Telf.: 2264-6691
                </div>
            </div>
            
            <script>
                window.onload = function() {
                    setTimeout(function() {
                        window.print();
                        window.close();
                    }, 500);
                };
            <\/script>
        </body>
        </html>
        `;

        printWindow.document.write(htmlContent);
        printWindow.document.close();
    };

});
</script>

{{-- ══ WEB SPEECH API — Dictado voz a texto ══ --}}
<style>
.btn-mic {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; font-size: 13px; font-weight: 600;
    border: 2px solid #556ee6; border-radius: 20px;
    background: transparent; color: #556ee6;
    cursor: pointer; transition: all .2s ease;
    white-space: nowrap; user-select: none; line-height: 1.4;
}
.btn-mic i { font-size: 16px; }
.btn-mic:hover { background: #556ee6; color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(85,110,230,.35); }
.btn-mic.recording {
    background: #dc3545; border-color: #dc3545; color: #fff;
    animation: mic-pulse 1.2s ease-in-out infinite;
}
@keyframes mic-pulse {
    0%,100% { box-shadow: 0 0 0 4px rgba(220,53,69,.25); }
    50%      { box-shadow: 0 0 0 9px rgba(220,53,69,.06); }
}
.btn-mic.unsupported { opacity:.45; cursor:not-allowed; border-color:#adb5bd; color:#adb5bd; }
.mic-status {
    font-size: 12px; margin-top: 5px; padding: 4px 10px;
    border-radius: 6px; color: #495057;
    background: rgba(85,110,230,.07); border-left: 3px solid #556ee6;
}
.mic-status.listening { border-color:#dc3545; background:rgba(220,53,69,.06); }
.mic-status.done      { border-color:#198754; background:rgba(25,135,84,.06); }
</style>

<script>
(function () {
    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SR) {
        document.querySelectorAll('.btn-mic').forEach(b => {
            b.classList.add('unsupported');
            b.title = 'Tu navegador no soporta dictado. Usa Chrome o Edge.';
            b.querySelector('.mic-label').textContent = 'No disponible';
        });
        return;
    }
    let activeRec = null;
    document.querySelectorAll('.btn-mic').forEach(btn => {
        const targetId = btn.dataset.target;
        const textarea = document.getElementById(targetId);
        const statusEl = document.getElementById('status-' + targetId);
        if (!textarea) return;
        btn.addEventListener('click', () => {
            if (btn.classList.contains('recording')) { activeRec && activeRec.stop(); return; }
            activeRec && activeRec.stop();
            const rec = new SR();
            rec.lang = 'es-ES'; rec.continuous = true; rec.interimResults = true;
            activeRec = rec;
            let baseText  = textarea.value;  // texto previo al dictado
            let finalText = '';               // texto final acumulado durante el dictado
            rec.onstart = () => {
                btn.classList.add('recording');
                btn.querySelector('.mic-label').textContent = 'Grabando...';
                statusEl.textContent = 'Escuchando... hable ahora';
                statusEl.className = 'mic-status listening'; statusEl.classList.remove('d-none');
                textarea.focus();
            };
            rec.onresult = (e) => {
                let interim = '';
                for (let i = e.resultIndex; i < e.results.length; i++) {
                    const t = e.results[i][0].transcript;
                    if (e.results[i].isFinal) {
                        finalText += t + ' ';
                    } else {
                        interim += t;
                    }
                }
                textarea.value = baseText + finalText + interim;
                textarea.scrollTop = textarea.scrollHeight;
                if (interim) statusEl.textContent = interim.slice(-80);
            };
            rec.onerror = (e) => {
                const msgs = { 'not-allowed':'Permiso denegado. Habilite el microfono en el navegador.','no-speech':'No se detecto voz. Intente de nuevo.','network':'Error de red.','audio-capture':'No se detecto microfono.' };
                statusEl.textContent = msgs[e.error] || ('Error: ' + e.error);
                statusEl.className = 'mic-status'; resetBtn(btn, statusEl);
            };
            rec.onend = () => {
                const fullText = textarea.value;
                textarea.value = fullText.trim() ? fullText : (baseText + finalText);
                textarea.dispatchEvent(new Event('input',  { bubbles: true }));
                textarea.dispatchEvent(new Event('change', { bubbles: true }));
                const n = textarea.value.trim().length;
                statusEl.textContent = n ? ('✓ Completado — ' + n + ' caracteres guardados.') : 'Finalizado sin texto detectado.';
                statusEl.className = 'mic-status done'; resetBtn(btn, statusEl); activeRec = null;
            };
            rec.start();
        });
    });
    function resetBtn(btn, statusEl) {
        btn.classList.remove('recording');
        btn.querySelector('.mic-label').textContent = 'Dictar';
        setTimeout(() => statusEl.classList.add('d-none'), 4500);
    }
})();
</script>
@endsection
