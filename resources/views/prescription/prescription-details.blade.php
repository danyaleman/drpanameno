@extends('layouts.master-layouts')
@section('title') {{ __('Create Prescription') }} @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
@endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title')Crear Expediente @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Expedientes @endslot
            @slot('li_3') Crear Expediente @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <a href="{{ url('prescription') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                        <i
                            class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Regresar a Expedientes') }}
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote>{{ __('Detalles de Expediente') }}</blockquote>
                        <form class="outer-repeater" action="{{ route('prescription.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Paciente ') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel_patient @error('patient_id') is-invalid @enderror"
                                        name="patient_id" id="patient">
                                        <option disabled selected>{{ __('Seleccionar Paciente') }}</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}" @if (old('patient_id') == $patient->id) selected @endif>
                                                {{ $patient->first_name }} {{ $patient->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Cita ') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel_appointment @error('appointment_id') is-invalid @enderror"
                                        name="appointment_id" id="appointment">
                                        <option disabled selected>{{ __('Seleccionar Cita') }}</option>
                                    </select>
                                    @error('appointment_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="created_by" value="{{ $user->id }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Sintomas ') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('symptoms') is-invalid @enderror" name="symptoms"
                                        id="symptoms" placeholder="{{ __('Agregar Sintomas') }}"
                                        rows="3">@if (old('symptoms')){{ old('symptoms') }}@endif</textarea>
                                    @error('symptoms')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Diagnostico ') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('diagnosis') is-invalid @enderror" name="diagnosis"
                                        id="diagnosis" placeholder="{{ __('Agregar Diagnostico') }}"
                                        rows="3">@if (old('diagnosis')){{ old('diagnosis') }}@endif</textarea>
                                    @error('diagnosis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <blockquote>{{ __('Signos vitales, Medicamentos, Examenes y Vacunas ') }}</blockquote>
                            <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Peso (lb)</label>
                                <input type="number" step="0.01" name="peso" class="form-control">
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='repeater mb-4'>
                                        <div data-repeater-list="medicines" class="mb-3">
                                            <label>{{ __('Medicamentos ') }}<span class="text-danger">*</span></label>
                                            <div data-repeater-item class="mb-3 row">
                                                <div class="col-md-5 col-6">
                                                    <input type="text" name="medicine" class="form-control"
                                                        placeholder="{{ __('Nombre del Medicamento') }}" />
                                                </div>
                                                <div class="col-md-5 col-6">
                                                    <textarea type="text" name="notes" class="form-control"
                                                        placeholder="{{ __('Dosis...') }}"></textarea>
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <input data-repeater-delete type="button"
                                                        class="fcbtn btn btn-outline btn-danger btn-1d btn-sm inner"
                                                        value="X" />
                                                </div>
                                            </div>
                                        </div>
                                        <input data-repeater-create type="button" class="btn btn-primary"
                                            value="Add Medicine" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='repeater mb-4'>
                                        <div data-repeater-list="test_reports" class="mb-3">
                                            <label>{{ __('Examenes ') }}</label>
                                            <div data-repeater-item class="mb-3 row">
                                                <div class="col-md-5 col-6">
                                                    <input type="text" name="test_report" class="form-control"
                                                        placeholder="{{ __('Nombre del Examen') }}" />
                                                </div>
                                                <div class="col-md-5 col-6">
                                                    <textarea type="text" name="notes" class="form-control"
                                                        placeholder="{{ __('Notas...') }}"></textarea>
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <input data-repeater-delete type="button"
                                                        class="fcbtn btn btn-outline btn-danger btn-1d btn-sm inner"
                                                        value="X" />
                                                </div>
                                            </div>
                                        </div>
                                        <input data-repeater-create type="button" class="btn btn-primary"
                                            value="Add Test Report" />
                                    </div>
                                </div>
                            </div>

                            <blockquote>{{ __('Vacunas') }}</blockquote>

                            <div class="repeater mb-4">
                                <div data-repeater-list="vacunas">
                                        <div data-repeater-item class="row mb-3">
                                            <div class="col-md-5">
                                                <input type="text" name="tipo" class="form-control"
                                                    placeholder="Tipo de vacuna" />
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="dosis" class="form-control"
                                                    placeholder="Dosis" />
                                            </div>
                                            <div class="col-md-2">
                                                <input data-repeater-delete type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    value="X" />
                                            </div>
                                        </div>
                                </div>

                                <input data-repeater-create type="button"
                                    class="btn btn-primary"
                                    value="Agregar vacuna" />
                            </div>

                            <blockquote>{{ __('Archivos Clinicos') }}</blockquote>

                            <div class="repeater mb-4">
                                <div data-repeater-list="archivos">
                                    <div data-repeater-item class="row mb-3">
                                        <div class="col-md-5">
                                            <input type="file" name="file" class="form-control">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="observaciones" class="form-control"
                                                placeholder="{{ __('Observaciones') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input data-repeater-delete type="button"
                                                class="btn btn-danger btn-sm"
                                                value="X" />
                                        </div>
                                    </div>
                                </div>
                                <input data-repeater-create type="button"
                                    class="btn btn-primary"
                                    value="{{ __('Agregar archivo') }}">
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Crear Expediente') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
    @section('script')
        <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
        <!-- form mask -->
        <script src="{{ URL::asset('build/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>
        <!-- form init -->
        <script src="{{ URL::asset('build/js/pages/form-repeater.int.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/notification.init.js') }}"></script>
        <script>
            $('.sel_patient').on('change', function(e) {
                e.preventDefault();
                var patientId = $(this).val();
                var token = $("input[name='_token']").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('patient_by_appointment') }}",
                    data: {
                        patient_id: patientId,
                        _token: token,
                    },
                    success: function(res) {
                        $('.sel_appointment').html('');
                        $('.sel_appointment').html(res.options);
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            });
        </script>
    @endsection
