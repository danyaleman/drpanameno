@extends('layouts.master-layouts')
@section('title') {{ __('Appointment list') }} @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('title') Listado de Citas @endslot
            @slot('li_1') Dashboard @endslot
            @slot('li_2') Citas @endslot
        @endcomponent
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="PendingAppointmentList" role="tabpanel">
                                <table class="table table-bordered dt-responsive nowrap "
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>{{ __('No.') }}</th>
                                            <th>{{ __('Nombre de Doctor') }}</th>
                                            <th>{{ __('Fecha') }}</th>
                                            <th>{{ __('Hora') }}</th>
                                            <th>{{ __('Estado') }}</th>
                                            <th>{{ __('Acción') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (session()->has('page_limit'))
                                            @php
                                                $per_page = session()->get('page_limit');
                                            @endphp
                                        @else
                                            @php
                                                $per_page = Config::get('app.page_limit');
                                            @endphp
                                        @endif
                                        @php
                                            $currentpage = $appointment->currentPage();
                                        @endphp
                                        @forelse ($appointment as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 + $per_page * ($currentpage - 1) }}</td>
                                                <td> {{ @$item->doctor->user->first_name . ' ' . @$item->doctor->user->last_name }}
                                                </td>
                                                <td>{{ $item->appointment_date }}</td>
                                                <td>{{ $item->timeSlot->from . ' to ' . $item->timeSlot->to }}</td>
                                                <td>
                                                    @if ($item->status == 0)
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif ($item->status == 1 )
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif ($item->status == 2 )
                                                        <span class="badge bg-danger">Cancel</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->status == 0)
                                                        <button type="button" class="btn btn-danger cancel"
                                                            data-id="{{ $item->id }}">Cancelar</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <p>No se encontraron registros</p>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="col-md-12 text-center mt-3">
                                    <div class="d-flex justify-content-start">
                                        Showing {{ $appointment->firstItem() }} to {{ $appointment->lastItem() }} of
                                        {{ $appointment->total() }} entries
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {{ $appointment->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <!-- Init js-->
        <script src="{{ URL::asset('build/js/pages/notification.init.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/appointment.js') }}"></script>
    @endsection
