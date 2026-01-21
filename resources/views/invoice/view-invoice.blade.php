@extends('layouts.master-layouts')
@section('title') {{ __('Detalle de Factura') }} @endsection
    @section('content')
        <!-- start page title -->
        @component('components.breadcrumb')
            @slot('title') Detalle de Factura @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Lista de Facturas @endslot
            @slot('li_3') Detalle de Factura @endslot
        @endcomponent
        <!-- end page title -->
        <div class="row d-print-none">
            <div class="col-12">
                <a href="{{ url('invoice') }}">
                    <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                        <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>{{ __('Volver a la Lista de Facturas') }}
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
                            <h4 class="float-end font-size-16">{{ __('Factura #') }} {{ $invoice_detail->id }}</h4>
                            <div class="mb-4">
                                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="logo" height="20" />
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <address>
                                    <strong>{{ __('Detalles del Paciente') }}</strong><br>
                                    {{ $invoice_detail->patient->first_name . ' ' . $invoice_detail->patient->last_name }}<br>
                                    {{-- {{ 'd' }} --}}
                                    <i class="mdi mdi-phone"></i> {{ $invoice_detail->patient->mobile }}<br>
                                    <i class="mdi mdi-email"></i> {{ $invoice_detail->patient->email }}<br>
                                </address>
                            </div>
                            <div class="col-3">
                                <address>
                                    <strong>{{ __('Detalles del Doctor') }}</strong><br>
                                    {{ @$invoice_detail->doctor->user->first_name . ' ' . @$invoice_detail->doctor->user->last_name }}<br>
                                    {{-- {{ 'd' }} --}}
                                    <i class="mdi mdi-phone"></i> {{ @$invoice_detail->doctor->user->mobile }}<br>
                                    <i class="mdi mdi-email"></i> {{ @$invoice_detail->doctor->user->email }}<br>
                                </address>
                            </div>
                            <div class="col-3">
                                <address>
                                    <strong>{{ __('Detalles del Pago') }}</strong><br>
                                    {{ __('Modo de Pago :') }} {{ $invoice_detail->payment_mode }}<br>
                                    {{ __('Estado del Pago :') }} {{ $invoice_detail->payment_status }}<br>
                                    @if ($invoice_detail->transaction != null)
                                        {{ __('Order Id :') }} {{ $invoice_detail->transaction->order_id }}<br>
                                        {{ __('Transaction No:') }} {{ $invoice_detail->transaction->transaction_no }}<br>
                                        {{ __('Payment Method:') }} {{ $invoice_detail->transaction->payment_method }}<br>
                                    @endif
                                </address>
                            </div>
                            <div class="col-3 pull-right">
                                <address>
                                    <strong>{{ __('Fecha de Factura: ') }}</strong>{{ $invoice->created_at }}<br>
                                    <strong>{{ __('Fecha de Cita: ') }}</strong>{{ $invoice->appointment->appointment_date . ' ' . $invoice->appointment->timeSlot->from . ' to ' . $invoice->appointment->timeSlot->to }}
                                </address>
                            </div>
                        </div>

                        <div class="py-2 mt-3">
                            <h3 class="font-size-15 fw-bold">{{ __('Detalles') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">{{ __('No.') }}</th>
                                        <th>{{ __('Servicio') }}</th>
                                        <th class="text-end">{{ __('Costo') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sub_total = 0;
                                    @endphp
                                    @foreach ($invoice_detail->invoice_detail as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td class="text-end">${{ $item->amount }}</td>
                                        </tr>
                                        @php
                                            $sub_total += $item->amount;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-end">{{ __('Sub Total') }}</td>
                                        <td class="text-end">${{ $sub_total }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="border-0 text-end">
                                            <strong>{{ __('Total') }}</strong>
                                        </td>
                                        <td class="border-0 text-end">
                                            <h4 class="m-0">${{ $sub_total }}</h4>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    @endsection
