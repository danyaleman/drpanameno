@extends('layouts.master-layouts')
@section('title') {{ __('Prescription Details') }} @endsection
    @section('content')
    @php
    function rango($valor, $min, $max) {
        if ($valor === null) return '';
        if ($valor < $min || $valor > $max) return 'text-danger fw-bold';
        return 'text-success';
    }
    @endphp
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title') Detalles de Expediente @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Listado de Expedientes @endslot
            @slot('li_3') Detalles de Expediente @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row d-print-none">
            <div class="col-12">
                <a href="{{ url('prescription') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                        <i
                            class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Regresar a Listado de Expedientes') }}
                    </button>
                </a>
                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light mb-4">
                    <i class="fa fa-print"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice-title">
                            <h4 class="float-end font-size-16">Expediente #{{ $prescription->id }}</h4>
                            <div class="mb-4">
                                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="logo" height="20" />
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-5">
                                <address>
                                    <strong>{{ __('Dr.') }}</strong><br>
                                    {{ @$user_details->appointment->doctor->user->first_name . ' ' . @$user_details->appointment->doctor->user->last_name }}<br>
                                    <i class="mdi mdi-phone"></i> {{ @$user_details->appointment->doctor->user->mobile }}<br>
                                    <i class="mdi mdi-email"></i> {{ @$user_details->appointment->doctor->user->email }}<br>
                                </address>
                            </div>
                            <div class="col-4">
                                <address>
                                    <strong>{{ __('Paciente') }}</strong><br>
                                    {{ $user_details->patient->first_name . ' ' . $user_details->patient->last_name }}<br>
                                    <i class="mdi mdi-phone"></i> {{ $user_details->patient->mobile }}<br>
                                    <i class="mdi mdi-email"></i> {{ $user_details->patient->email }}<br>
                                </address>
                            </div>
                            <div class="col-3">
                                <address>
                                    <strong>{{ __('Fecha: ') }}</strong>{{ $prescription->created_at }}<br>
                                    <strong>{{ __('Fecha de Cita: ') }}</strong>{{ $prescription->appointment->appointment_date . ' ' . $prescription->appointment->timeSlot->form . ' ' . $prescription->appointment->timeSlot->to }}<br>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5 mt-3 text-center">
                                <address>
                                    <strong>{{ __('Sintomas:') }}</strong><br>
                                    {{ $prescription->symptoms }}
                                </address>
                            </div>
                            <div class="col-5 mt-3 text-center">
                                <address>
                                    <strong>{{ __('Diagnostico:') }}</strong><br>
                                    {{ $prescription->diagnosis }}
                                </address>
                            </div>
                        </div>
                        @if($signos)
                        <hr>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4 class="text-center mb-3">{{ __('Signos Vitales  ') }}</h4>
                            </div>

                            <div class="col-md-3">
                                <strong>{{ __('Peso') }}:</strong><br>
                                {{ $signos->peso ? $signos->peso.' lb' : '-' }}
                            </div>

                            <div class="col-md-3">
                                <strong>{{ __('Altura') }}:</strong><br>
                                {{ $signos->talla ? $signos->talla.' m' : '-' }}
                            </div>

                           @php
                            $imc = null;
                            $imcClass = '';

                            if ($signos->peso && $signos->talla) {

                                // convertir libras a kilogramos
                                $pesoKg = $signos->peso * 0.453592;

                                // talla ya está en metros
                                $imc = round($pesoKg / ($signos->talla * $signos->talla), 2);

                                if ($imc < 18.5 || $imc >= 30) {
                                    $imcClass = 'text-danger fw-bold';
                                } elseif ($imc >= 25) {
                                    $imcClass = 'text-warning fw-bold';
                                } else {
                                    $imcClass = 'text-success';
                                }
                            }
                            @endphp

                            <div class="col-md-3">
                                <strong>{{ __('BMI (IMC)') }}:</strong><br>
                                <span class="{{ $imcClass }}">
                                    {{ $imc ?? '-' }}
                                </span>
                            </div>

                            @php
                            $imcLabel = '';
                            if ($imc) {
                                if ($imc < 18.5) $imcLabel = 'Debajo del Peso ideal';
                                elseif ($imc < 25) $imcLabel = 'Normal';
                                elseif ($imc < 30) $imcLabel = 'Sobrepeso';
                                else $imcLabel = 'Obesidad';
                            }
                            @endphp

                            <div>
                                <small class="{{ $imcClass }}">
                                    {{ $imcLabel }}
                                </small>
                            </div>

                            <div class="col-md-3">
                                <strong>{{ __('Temperatura') }}:</strong><br>
                                <span class="{{ rango($signos->temperatura, 36.1, 37.2) }}">
                                    {{ $signos->temperatura ?? '-' }} °C
                                </span>
                            </div>

                            <div class="col-md-3 mt-3">
                                <strong>{{ __('Frecuencia Cardiaca') }}:</strong><br>
                                <span class="{{ rango($signos->frec_cardiaca, 60, 100) }}">
                                    {{ $signos->frec_cardiaca ?? '-' }} bpm
                                </span>
                            </div>

                            <div class="col-md-3 mt-3">
                                <strong>{{ __('Frecuencia Respiratoria') }}:</strong><br>
                                <span class="{{ rango($signos->frec_respiratoria, 12, 20) }}">
                                    {{ $signos->frec_respiratoria ?? '-' }} rpm
                                </span>
                            </div>

                            @php
                                $paClass = 'text-success';
                                if (
                                    $signos->presion_arterial_sistolica >= 140 ||
                                    $signos->presion_arterial_diastolica >= 90
                                ) {
                                    $paClass = 'text-danger fw-bold';
                                } elseif (
                                    $signos->presion_arterial_sistolica >= 120 ||
                                    $signos->presion_arterial_diastolica >= 80
                                ) {
                                    $paClass = 'text-warning fw-bold';
                                }
                            @endphp

                                <div class="col-md-3 mt-3">
                                    <strong>{{ __('Presión Arterial') }}:</strong><br>
                                    <span class="{{ $paClass }}">
                                        {{ $signos->presion_arterial_sistolica }}/{{ $signos->presion_arterial_diastolica }} mmHg
                                    </span>
                                </div>


                            @php
                            $spoClass = 'text-success';
                            if ($signos->spo < 90) {
                                $spoClass = 'text-danger fw-bold';
                            } elseif ($signos->spo < 95) {
                                $spoClass = 'text-warning fw-bold';
                            }
                            @endphp

                            <div class="col-md-3 mt-3">
                                <strong>{{ __('SpO₂') }}:</strong><br>
                                <span class="{{ $spoClass }}">
                                    {{ $signos->spo ?? '-' }} %
                                </span>
                            </div>


                            @if($signos->examen)
                                <div class="col-12 mt-3">
                                    <strong>{{ __('Examenes') }}:</strong>
                                    <p>{{ $signos->examen }}</p>
                                </div>
                            @endif

                            @if($signos->observaciones_adicionales)
                                <div class="col-12 mt-2">
                                    <strong>{{ __('Observaciones Adicionales') }}:</strong>
                                    <p>{{ $signos->observaciones_adicionales }}</p>
                                </div>
                            @endif
                        </div>
                        @endif  
                        <div class="row">
                            <div class="col-md-6">
                                <div class="py-2 mt-3">
                                    <h3 class="font-size-15 fw-bold">{{ __('Medicamentos') }}</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 70px;">{{ __('No.') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Notes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($medicines as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->notes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="py-2 mt-3">
                                    <h3 class="font-size-15 fw-bold">{{ __('Examenes') }}</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 70px;">{{ __('No.') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Notes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($test_reports as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td> {{ $item->name }} </td>
                                                    <td> {{ $item->notes }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($vacunas->count())
                            <div class="col-md-12 mt-4">
                                <h3 class="font-size-15 fw-bold">Vacunas</h3>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tipo</th>
                                            <th>Dosis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vacunas as $v)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $v->tipo }}</td>
                                            <td>{{ $v->dosis }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            @if($prescription->archivos->count())
                            <hr>
                            <h5>{{ __('Archivos Clinicos') }}</h5>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('File') }}</th>
                                        <th>{{ __('Observations') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescription->archivos as $file)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ asset('storage/'.$file->url_file) }}" target="_blank">
                                                    {{ __('View File') }}
                                                </a>
                                            </td>
                                            <td>{{ $file->observaciones }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
