@extends('layouts.master-layouts')
@section('title')
    {{ __('Update prescription') }}
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
@endsection

    @section('content')
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        {{ __('Editar Expediente') }}
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('prescription') }}">{{ __('Expediente') }}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                {{ __('Update prescription') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote>{{ __('Detalles de Expediente') }}</blockquote>
                        <form class="outer-repeater" action="{{ url('prescription/' . '' . $prescription->id) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="PATCH" />
                            <input type="hidden" name="id" value="{{ $prescription->id }}" id="form_id" />

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Paciente ') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel_patient @error('patient_id') is-invalid @enderror"
                                        name="patient_id" id="patient">
                                        <option disabled selected>{{ __('Select Patient') }}</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}"
                                                {{ $patient->id == $prescription->patient->id ? 'selected' : '' }}>
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
                                    <label class="form-label">{{ __('Cita :') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel_appointment @error('appointment_id') is-invalid @enderror"
                                        name="appointment_id" id="appointment">
                                        <option disabled selected>{{ __('Select Appointment') }}</option>
                                        @foreach ($appointment as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == $prescription->appointment->id ? 'selected' : '' }}>
                                                {{ $item->appointment_date }}</option>
                                        @endforeach
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
                                        id="symptoms" placeholder="{{ __('Add Symptoms') }}"
                                        rows="3">@if (old('symptoms')){{ old('symptoms') }}@endif {{ $prescription->symptoms }}</textarea>
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
                                        id="diagnosis" placeholder="{{ __('Add Diagnosis') }}"
                                        rows="3">@if (old('diagnosis')){{ old('diagnosis') }}@endif{{ $prescription->diagnosis }}</textarea>
                                    @error('diagnosis')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <blockquote>{{ __('Signos vitales, Medicamentos, Examenes y Vacunas') }}</blockquote>   
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Peso (lb)</label>
                                    <input type="text" name="peso" class="form-control"
                                        value="{{ old('peso', optional($signos)->peso) }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Talla (m)</label>
                                    <input type="text" name="talla" class="form-control"
                                        value="{{ old('talla', optional($signos)->talla) }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Temperatura (°C)</label>
                                    <input type="text" name="temperatura" class="form-control"
                                        value="{{ old('temperatura', optional($signos)->temperatura) }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">SpO₂ (%)</label>
                                    <input type="text" name="spo" class="form-control"
                                        value="{{ old('spo', optional($signos)->spo) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Frecuencia Cardíaca</label>
                                    <input type="text" name="frec_cardiaca" class="form-control"
                                        value="{{ old('frec_cardiaca', optional($signos)->frec_cardiaca) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Frecuencia Respiratoria</label>
                                    <input type="text" name="frec_respiratoria" class="form-control"
                                        value="{{ old('frec_respiratoria', optional($signos)->frec_respiratoria) }}">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Presión Arterial</label>
                                    <div class="input-group">
                                        <input type="text" name="presion_arterial_sistolica" class="form-control"
                                            placeholder="Sistólica"
                                            value="{{ old('presion_arterial_sistolica', optional($signos)->presion_arterial_sistolica) }}">
                                        <span class="input-group-text">/</span>
                                        <input type="text" name="presion_arterial_diastolica" class="form-control"
                                            placeholder="Diastólica"
                                            value="{{ old('presion_arterial_diastolica', optional($signos)->presion_arterial_diastolica) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Examen Físico</label>
                                    <textarea name="examen" class="form-control"
                                            rows="3">{{ old('examen', optional($signos)->examen) }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Observaciones Adicionales</label>
                                    <textarea name="observaciones_adicionales" class="form-control"
                                            rows="3">{{ old('observaciones_adicionales', optional($signos)->observaciones_adicionales) }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class='repeater mb-4'>
                                        <div data-repeater-list="medicines" class="mb-3">
                                            <label>{{ __('Medicines ') }}<span class="text-danger">*</span></label>
                                            @foreach ($medicines as $item)
                                                <div data-repeater-item class="mb-3 row">
                                                    <div class="col-md-5 col-6">
                                                        <input type="text" name="medicine" class="form-control"
                                                            placeholder="{{ __('Medicine Name') }}"
                                                            value="{{ $item->name }}" />
                                                    </div>
                                                    <div class="col-md-5 col-6">
                                                        <textarea type="text" name="notes" class="form-control"
                                                            placeholder="{{ __('Notes...') }}">{{ $item->notes }}</textarea>
                                                    </div>
                                                    <div class="col-md-2 col-4">
                                                        <input data-repeater-delete type="button"
                                                            class="fcbtn btn btn-outline btn-danger btn-1d btn-sm inner"
                                                            value="X" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input data-repeater-create type="button" class="btn btn-primary"
                                            value="Agregar medicina" />
                                    </div>
                                </div>
                                @if ($test_reports->count() == 0)
                                    <div class="col-md-6">
                                        <div class='repeater mb-4'>
                                            <div data-repeater-list="test_reports" class="mb-3">
                                                <label>{{ __('Test Reports ') }}<span
                                                        class="text-danger">*</span></label>
                                                <div data-repeater-item class="mb-3 row">
                                                    <div class="col-md-5 col-6">
                                                        <input type="text" name="test_report" class="form-control"
                                                            placeholder="{{ __('Test Report Name') }}" />
                                                    </div>
                                                    <div class="col-md-5 col-6">
                                                        <textarea type="text" name="notes" class="form-control"
                                                            placeholder="{{ __('Notes...') }}"></textarea>
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
                                @else
                                    <div class="col-md-6">
                                        <div class='repeater mb-4'>
                                            <div data-repeater-list="test_reports" class="mb-3">
                                                <label>{{ __('Examenes ') }}<span
                                                        class="text-danger">*</span></label>
                                                @foreach ($test_reports as $item)
                                                    <div data-repeater-item class="mb-3 row">
                                                        <div class="col-md-5 col-6">
                                                            <input type="text" name="test_report" class="form-control"
                                                                placeholder="{{ __('Nombre del examen') }}"
                                                                value="{{ $item->name }}" />
                                                        </div>
                                                        <div class="col-md-5 col-6">
                                                            <textarea type="text" name="notes" class="form-control"
                                                                placeholder="{{ __('Notas...') }}">{{ $item->notes }}</textarea>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <input data-repeater-delete type="button"
                                                                class="fcbtn btn btn-outline btn-danger btn-1d btn-sm inner"
                                                                value="X" />
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <input data-repeater-create type="button" class="btn btn-primary"
                                                value="Agregar examen" />
                                        </div>
                                    </div>
                            <blockquote>{{ __('Vacunas') }}</blockquote>

                            <div class="repeater mb-4">
                                <div data-repeater-list="vacunas">
                                    @if($vacunas->count())
                                        @foreach ($vacunas as $v)
                                            <div data-repeater-item class="row mb-3">
                                                <div class="col-md-5">
                                                    <input type="text" name="tipo" class="form-control"
                                                        value="{{ $v->tipo }}"
                                                        placeholder="Tipo de vacuna" />
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="dosis" class="form-control"
                                                        value="{{ $v->dosis }}"
                                                        placeholder="Dosis" />
                                                </div>
                                                <div class="col-md-2">
                                                    <input data-repeater-delete type="button"
                                                        class="btn btn-outline-danger btn-sm"
                                                        value="X" />
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
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
                                    @endif
                                </div>

                                <input data-repeater-create type="button"
                                    class="btn btn-primary"
                                    value="Agregar vacuna" />
                            </div>


                                @endif

                                @if(isset($prescription) && $prescription->archivos->count())
                                <blockquote>{{ __('Archivos Clínicos Guardados') }}</blockquote>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Archivo') }}</th>
                                            <th>{{ __('Observaciones') }}</th>
                                            <th width="120">{{ __('Acciones') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prescription->archivos as $archivo)
                                            <tr>
                                                <td>
                                                    <a href="{{ asset('storage/'.$archivo->url_file) }}" target="_blank">
                                                        {{ basename($archivo->url_file) }}
                                                    </a>
                                                </td>
                                                <td>{{ $archivo->observaciones }}</td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('archivo.destroy', $archivo->id) }}"
                                                        onsubmit="return confirm('¿Eliminar este archivo clínico?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-danger btn-sm">
                                                            {{ __('Eliminar') }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                              <blockquote>{{ __('Archivos Clinicos') }}</blockquote>

                                <div class="repeater mb-4">
                                    <div data-repeater-list="archivos">
                                        <div data-repeater-item class="row mb-3">
                                            <div class="col-md-5">
                                                <input type="file" name="file" class="form-control" >
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
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Actualizar Expediente') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
            $('.sel_patient').change(function(e) {
                e.preventDefault();
                $('.sel_appointment').empty();
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
