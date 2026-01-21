@extends('layouts.master-layouts')
@section('title') {{ __('Update Invoice') }} @endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
@endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title') Editar Factura @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Factura @endslot
            @slot('li_3') Actualizar Factura @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <a href="{{ url('invoice') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Atrás') }}
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <blockquote>{{ __('Detalles de la Factura') }}</blockquote>
                        <form class="outer-repeater" action="{{ url('invoice/' . '' . $invoice_detail->id) }}" method="post">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Paciente') }}<span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control select2 sel_patient @error('patient_id') is-invalid @enderror"
                                        name="patient_id" id="patient">
                                        <option disabled selected>{{ __('Seleccionar Paciente') }}</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}" {{ $invoice_detail->patient->id == $patient->id?'selected':'' }}>
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
                                </div>
                                @if ($role == 'doctor' || $role == 'accountant')
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Cita ') }}<span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control select2 sel_appointment @error('appointment_id') is-invalid @enderror"
                                            name="appointment_id" id="appointment">
                                            <option disabled selected>{{ __('Seleccionar Cita') }}</option>
                                            @foreach ($appointment as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == $invoice_detail->appointment->id ? 'selected' : '' }}>
                                                {{ $item->appointment_date }}</option>
                                             @endforeach
                                        </select>
                                        @error('appointment_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                @endif
                                @if ($role == 'receptionist')
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Cita ') }}<span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control select2 sel_appointment @error('appointment_id') is-invalid @enderror"
                                            name="appointment_id" id="appointment">
                                            <option disabled selected>{{ __('Seleccionar Cita') }}</option>
                                            @foreach ($appointment as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == $invoice_detail->appointment->id ? 'selected' : '' }}>
                                                {{ $item->appointment_date }}</option>
                                             @endforeach
                                        </select>
                                        @error('appointment_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Doctor ') }}</label>
                                    <input type="text" class="form-control sel_doctor" readonly
                                    value="{{ @$invoice_detail->appointment->doctor->user->first_name .' '.@$invoice_detail->appointment->doctor->user->last_name }}">
                                    <input type="hidden" name="doctor_id" class="form-control sel_doctor_id">
                                    @error('doctor_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="created_by" value="{{ $user->id }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Metodo de Pago ') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('payment_mode') is-invalid @enderror"
                                        name="payment_mode">
                                        <option selected disabled>{{ __('Seleccionar Metodo de Pago') }}</option>
                                        <option value="Cash Payement" @if ($invoice_detail->payment_mode == 'Cash Payement') selected @endif>{{ __('Pago en Efectivo') }}
                                        </option>
                                        <option value="Cheque" @if ($invoice_detail->payment_mode == 'Cheque') selected @endif>{{ __('Cheque') }}</option>
                                        <option value="Debit/Credit Card" @if ($invoice_detail->payment_mode == 'Debit/Credit Card') selected @endif>
                                            {{ __('Tarjeta de Débito/Crédito') }}
                                        </option>
                                    </select>
                                    @error('payment_mode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Estado del Pago ') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('payment_status') is-invalid @enderror"
                                        name="payment_status">
                                        <option selected disabled>{{ __('Seleccionar Estado del Pago') }}</option>
                                        <option value="Paid" @if ($invoice_detail->payment_status == 'Paid') selected @endif>{{ __('Pagado') }}</option>
                                        <option value="Unpaid" @if ($invoice_detail->payment_status == 'Unpaid') selected @endif>{{ __('No Pagado') }}</option>
                                    </select>
                                    @error('payment_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <blockquote>{{ __('Detalle de Servicios') }}</blockquote>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class='repeater mb-4'>
                                        <div data-repeater-list="invoices" class="mb-3">
                                            <label>{{ __('Servicio ') }}<span
                                                    class="text-danger">*</span></label>
                                            @foreach ($invoice_detail->invoice_detail as $item)
                                                <div data-repeater-item class="mb-3 row">
                                                    <div class="col-md-5 col-6">
                                                        <input type="text" name="title" class="form-control"
                                                            placeholder="{{ __('Item title') }}" value="{{ $item->title }}"/>
                                                    </div>
                                                    <div class="col-md-5 col-6">
                                                        <input type="text" name="amount" class="form-control"
                                                            placeholder="{{ __('Costo') }}" value="{{ $item->amount }}" />
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
                                            value="Add Item" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Actualizar Factura') }}
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
            // Patient by appointment select
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
            // appointment by doctor select
            $('.sel_appointment').change(function(e) {
                e.preventDefault();
                var appointmentID = $(this).val();
                var token = $("input[name='_token']").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('appointment_by_doctor') }}",
                    data: {
                        appointment_id: appointmentID,
                        _token: token,
                    },
                    success: function(res) {
                        var rd = res.data[0];
                        $('.sel_doctor').val(rd.first_name + ' ' + rd.last_name);
                        $('.sel_doctor_id').val(rd.id);
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            });
        </script>
    @endsection
