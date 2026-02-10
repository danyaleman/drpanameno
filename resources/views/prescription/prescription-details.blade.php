@extends('layouts.master-layouts')

@section('title', 'Crear Expediente')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
<style>
    .sticky-right {
        position: sticky;
        top: 90px;
    }
</style>
@endsection

@section('content')

{{-- ENCABEZADO DINÁMICO DEL PACIENTE --}}
<div class="card mb-3">
    <div class="card-body">
        <h4 id="patient_name">Seleccione un paciente</h4>
        <small id="patient_info" class="text-muted"></small>
    </div>
</div>

<form action="{{ route('prescription.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="created_by" value="{{ $user->id }}">

<div class="row">

    {{-- ================= COLUMNA IZQUIERDA ================= --}}
    <div class="col-lg-8">

        {{-- TABS --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-general">Datos generales</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-consulta">Consulta</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-exploracion">Exploración física</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-evaluacion">Evaluación</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-receta">Receta médica</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-vacunas">Vacunas</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-imagenes">Imágenes</a></li>
        </ul>

        <div class="tab-content">

            {{-- DATOS GENERALES --}}
            <div class="tab-pane fade show active" id="tab-general">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Paciente</label>
                        <select class="form-control select2 sel_patient" name="patient_id">
                            <option selected disabled>Seleccionar</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Cita</label>
                        <select class="form-control select2 sel_appointment" name="appointment_id"></select>
                    </div>
                </div>
            </div>

            {{-- CONSULTA --}}
            <div class="tab-pane fade" id="tab-consulta">
                <div class="mb-3">
                    <label>Síntomas</label>
                    <textarea name="symptoms" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Consulta por</label>
                    <textarea name="consulta_por" class="form-control"></textarea>
                </div>
            </div>

            {{-- EXPLORACIÓN FÍSICA --}}
            <div class="tab-pane fade" id="tab-exploracion">
                @include('prescription.partials.exploracion_fisica')
            </div>

            {{-- EVALUACIÓN --}}
            <div class="tab-pane fade" id="tab-evaluacion">
                <div class="mb-3">
                    <label>Diagnóstico</label>
                    <textarea name="diagnostico" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Estudios de laboratorio</label>
                    <textarea name="estudios_laboratorios" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Tratamiento</label>
                    <textarea name="tratamiento" class="form-control"></textarea>
                </div>
            </div>

            {{-- RECETA --}}
            <div class="tab-pane fade" id="tab-receta">
                @include('prescription.partials.medicamentos')
            </div>

            {{-- VACUNAS --}}
            <div class="tab-pane fade" id="tab-vacunas">
                @include('prescription.partials.vacunas')
            </div>

            {{-- IMÁGENES --}}
            <div class="tab-pane fade" id="tab-imagenes">
                @include('prescription.partials.archivos')
            </div>

        </div>
    </div>

    {{-- ================= COLUMNA DERECHA FIJA ================= --}}
    <div class="col-lg-4">
        <div class="card sticky-right">
            <div class="card-header"><strong>Antecedentes y Alergias</strong></div>
            <div class="card-body">
                <label>Patológicos</label>
                <textarea id="patologicos" class="form-control mb-3" readonly></textarea>

                <label>Familiares</label>
                <textarea id="familiares" class="form-control mb-3" readonly></textarea>

                <label>Alergias</label>
                <textarea id="alergias" class="form-control" readonly></textarea>
            </div>
        </div>
    </div>

</div>

<div class="mt-3">
    <button class="btn btn-primary">Crear Expediente</button>
</div>

</form>
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

    // Evento al seleccionar paciente
    $('.sel_patient').on('select2:select', function (e) {
        let patientId = e.params.data.id;
        console.log('Paciente seleccionado ID:', patientId);

        // Limpiar campos antes de cargar
        $('#patient_name').text('Cargando...');
        $('#patient_info').text('');
        $('.sel_appointment').empty();

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
                    // Citas
                    $('.sel_appointment').html(res.options);
                    // Actualizar Select2 de citas para que reconozca los nuevos options
                    $('.sel_appointment').trigger('change');

                    // Encabezado
                    $('#patient_name').text(res.patient.name);
                    $('#patient_info').text(res.patient.info);

                    // Antecedentes
                    $('#patologicos').val(res.patient.pathological_history ?? '');
                    $('#familiares').val(res.patient.non_pathological_history ?? '');
                    $('#alergias').val(res.patient.medications_allergies ?? '');
                } else {
                    // Manejar caso de error controlado
                    $('#patient_name').text('Paciente sin perfil médico');
                    alert(res.message);
                }
            },
            error: function (err) {
                console.error('ERROR AJAX:', err);
                $('#patient_name').text('Error al cargar');
            }
        });
    });
});
</script>
@endsection