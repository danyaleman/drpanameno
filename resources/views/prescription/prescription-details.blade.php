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

{{-- CARD DE SELECCIÓN DE PACIENTE Y CITA --}}
<div class="card mb-3" id="selection-card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bx bx-user-plus"></i> Seleccionar Paciente y Cita</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="fw-bold">Paciente <span class="text-danger">*</span></label>
                <select class="form-control select2 sel_patient" name="patient_id" required>
                    <option value="" selected disabled>Seleccionar paciente...</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" 
                            {{ (isset($preloadPatientId) && $preloadPatientId == $patient->id) ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="fw-bold">Cita <span class="text-danger">*</span></label>
                <select class="form-control select2 sel_appointment" name="appointment_id" required>
                    @if(isset($preloadAppointmentId) && $preloadAppointment)
                        <option value="{{ $preloadAppointmentId }}" selected>
                            Cita del {{ $preloadAppointment->appointment_date }}
                        </option>
                    @else
                        <option value="" selected disabled>Primero seleccione un paciente...</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
</div>

{{-- ENCABEZADO DINÁMICO DEL PACIENTE --}}
<div class="card mb-3 shadow-lg" id="patient-header-card" style="{{ isset($preloadPatientId) ? '' : 'display: none;' }}; background: linear-gradient(135deg, #556ee6 0%, #34469d 100%); color: white; border-radius: 10px;">
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

<form action="{{ route('prescription.store') }}" method="POST" enctype="multipart/form-data" id="prescription-form" style="{{ isset($preloadPatientId) ? '' : 'display: none;' }}">
@csrf
<input type="hidden" name="created_by" value="{{ $user->id }}">
<input type="hidden" name="patient_id_hidden" id="patient_id_hidden" value="{{ $preloadPatientId ?? '' }}">
<input type="hidden" name="appointment_id" id="appointment_id_hidden" value="{{ $preloadAppointmentId ?? '' }}">

<div class="row">

    {{-- ================= COLUMNA IZQUIERDA ================= --}}
    <div class="col-lg-8">

        {{-- TABS --}}
        <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px;">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-info-general"><i class="bx bx-id-card"></i> Info. General</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-consulta"><i class="bx bx-detail"></i> Consulta</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-exploracion"><i class="bx bx-body"></i> Exploración</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-evaluacion"><i class="bx bx-file"></i> Evaluación</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-receta"><i class="bx bx-plus-medical"></i> Receta</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-vacunas"><i class="bx bx-injection"></i> Vacunas</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-imagenes"><i class="bx bx-images"></i> Imágenes</a></li>
        </ul>

        <div class="tab-content text-muted">

            {{-- TAB: INFORMACIÓN GENERAL (SOLO LECTURA) --}}
            <div class="tab-pane fade show active" id="tab-info-general">
                <div class="card border">
                    <div class="card-body">
                        <h4 class="card-title text-primary mb-4">Datos del Paciente</h4>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="fw-bold text-dark">Nombre Completo</label>
                                <p class="form-control-plaintext border-bottom" id="info_full_name">-</p>
                            </div>
                            <div class="col-md-2">
                                <label class="fw-bold text-dark">Edad</label>
                                <p class="form-control-plaintext border-bottom" id="info_age">-</p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold text-dark">Fecha Nacimiento</label>
                                <p class="form-control-plaintext border-bottom" id="info_dob">-</p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold text-dark">Género</label>
                                <p class="form-control-plaintext border-bottom" id="info_gender">-</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="fw-bold text-dark">Teléfono</label>
                                <p class="form-control-plaintext border-bottom" id="info_mobile">-</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold text-dark">Email</label>
                                <p class="form-control-plaintext border-bottom" id="info_email">-</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold text-dark">Ocupación</label>
                                <p class="form-control-plaintext border-bottom" id="info_occupation">-</p>
                            </div>
                        </div>

                         <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="fw-bold text-dark">Dirección</label>
                                <p class="form-control-plaintext border-bottom" id="info_address">-</p>
                            </div>
                            <div class="col-md-2">
                                <label class="fw-bold text-dark">Estado Civil</label>
                                <p class="form-control-plaintext border-bottom" id="info_marital">-</p>
                            </div>
                            <div class="col-md-2">
                                <label class="fw-bold text-dark">Tipo Sangre</label>
                                <p class="form-control-plaintext border-bottom" id="info_blood">-</p>
                            </div>
                        </div>
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
                <textarea id="patologicos" name="pathological_history" class="form-control mb-3"></textarea>

                <label>Familiares</label>
                <textarea id="familiares" name="non_pathological_history" class="form-control mb-3"></textarea>

                <label>Alergias</label>
                <textarea id="alergias" name="medications_allergies" class="form-control"></textarea>
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

    // Verificar si hay precarga de paciente (viniendo desde calendario)
    var preloadPatientId = '{{ $preloadPatientId ?? '' }}';
    var preloadAppointmentId = '{{ $preloadAppointmentId ?? '' }}';
    
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
                    // Citas: Actualizar si es selección manual O si no hay cita precargada
                    if (isManualSelection || !preloadAppointmentId) {
                        $('.sel_appointment').html(res.options);
                        $('.sel_appointment').val('').trigger('change'); // Resetear selección
                    }

                    // Si hay una cita precargada y NO es selección manual, seleccionarla
                    if (!isManualSelection && preloadAppointmentId) {
                         $('.sel_appointment').val(preloadAppointmentId).trigger('change');
                    }

                    // Encabezado
                    $('#patient_name').text(res.patient.name);
                    $('#patient_info').text(res.patient.info);

                    // Imagen o Iniciales (Verificación segura)
                    var firstName = res.patient.first_name || '';
                    var lastName = res.patient.last_name || '';
                    
                    if (res.patient.profile_photo_url) {
                        $('#patient_img').attr('src', res.patient.profile_photo_url).show();
                        $('#patient_initials').hide();
                    } else {
                        $('#patient_img').hide();
                        var initials = (firstName.charAt(0) + lastName.charAt(0)).toUpperCase();
                        $('#patient_initials').text(initials).show();
                    }

                    // Llenar Tab Información General
                    $('#info_full_name').text(res.patient.name);
                    $('#info_age').text(res.patient.age ? res.patient.age + ' años' : '-');
                    $('#info_dob').text(res.patient.dob ?? '-');
                    $('#info_gender').text(res.patient.gender ?? '-');
                    $('#info_mobile').text(res.patient.mobile ?? '-');
                    $('#info_email').text(res.patient.email ?? '-');
                    $('#info_occupation').text(res.patient.occupation ?? '-');
                    $('#info_address').text(res.patient.address ?? '-');
                    $('#info_marital').text(res.patient.marital_status ?? '-');
                    $('#info_blood').text(res.patient.blood_group ?? '-');

                    // Antecedentes (Sidebar)
                    $('#patologicos').val(res.patient.pathological_history ?? '');
                    $('#familiares').val(res.patient.non_pathological_history ?? '');
                    $('#alergias').val(res.patient.medications_allergies ?? '');

                    // Mostrar formulario y header
                    $('#patient-header-card').slideDown();
                    $('#prescription-form').slideDown();

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
        $('.sel_appointment').empty().append('<option value="" selected disabled>Cargando citas...</option>');

        // Pequeño delay para asegurar que el DOM y variables internas de Select2 se asienten
        setTimeout(function() {
            loadPatientInfo(patientId, true);
        }, 50);
    });

    // Evento al cambiar la cita seleccionada
    $('.sel_appointment').on('change', function() {
        let appointmentId = $(this).val();
        $('#appointment_id_hidden').val(appointmentId);
        console.log('Cita seleccionada ID:', appointmentId);
    });

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

});
</script>
@endsection