@extends('layouts.master-layouts')
@section('title') {{ __('Notification list') }} @endsection

@section('content')
@component('components.breadcrumb')
    @slot('title') Lista de Notificaciones @endslot
    @slot('li_1') Dashboard @endslot
    @slot('li_2') Notificaciones @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
                <div class="d-flex align-items-center">
                    <i class="bx bx-bell text-white font-size-22 me-2"></i>
                    <h5 class="mb-0 text-white fw-bold">Notificaciones</h5>
                </div>
                <span class="badge bg-white text-primary fw-bold px-3 py-2">
                    {{ $notification->total() }} en total
                </span>
            </div>

            <div class="card-body p-0">
                @forelse ($notification as $item)
                    @php
                        $isVaccine   = $item->notification_type_id == 5;
                        $isInvoice   = $item->notification_type_id == 4;
                        $isUnread    = $item->read_at === null;

                        // Datos seguros para tipos no-vacuna
                        $apptPatient = null;
                        $invPatient  = null;
                        if (!$isVaccine) {
                            if ($isInvoice) {
                                $invPatient = optional(optional($item->invoice_user)->patient);
                            } else {
                                $apptPatient = optional(optional($item->appointment_user)->patient);
                            }
                        }
                    @endphp

                    <a href="/notification/{{ $item->id }}"
                       class="text-reset d-block border-bottom {{ $isUnread ? '' : 'opacity-75' }}"
                       style="{{ $isUnread ? 'background:#f8f9ff;' : 'background:#fff;' }}">
                        <div class="d-flex align-items-start p-3 gap-3">

                            {{-- Avatar / Ícono --}}
                            @if($isVaccine)
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title rounded-circle fw-bold"
                                              style="background:linear-gradient(135deg,#0dcaf0,#0a8fa8);color:#fff;font-size:1.1rem;">
                                            💉
                                        </span>
                                    </div>
                                </div>
                            @else
                                <img src="@if(optional($item->user)->profile_photo) {{ URL::asset('storage/images/users/' . $item->user->profile_photo) }} @else {{ URL::asset('build/images/users/noImage.png') }} @endif"
                                     class="rounded-circle avatar-xs flex-shrink-0" alt="avatar">
                            @endif

                            {{-- Contenido --}}
                            <div class="flex-grow-1 overflow-hidden">
                                @if($isVaccine)
                                    {{-- Notificación de vacuna --}}
                                    <h6 class="mb-1 fw-bold text-info">💉 Vacuna Pendiente</h6>
                                    <p class="mb-1 text-dark small">{{ $item->title }}</p>
                                    <a href="{{ route('vaccines.records.index') }}"
                                       class="btn btn-sm btn-outline-info py-0 px-2 mt-1" style="font-size:0.75rem;">
                                        <i class="bx bx-list-ul me-1"></i>Ver registros
                                    </a>

                                @elseif($isInvoice)
                                    {{-- Notificación de factura --}}
                                    <h6 class="mb-1 fw-semibold">
                                        @if($invPatient)
                                            <a href="{{ url('patient/' . $invPatient->id) }}" class="text-dark">
                                                {{ $invPatient->first_name }} {{ $invPatient->last_name }}
                                            </a> —
                                        @endif
                                        {{ $item->title }}
                                    </h6>
                                    @if(optional($item->invoice_user)->created_at)
                                        <p class="mb-0 small text-muted">
                                            Fecha factura: {{ $item->invoice_user->created_at->format('d/m/Y') }}
                                        </p>
                                    @endif

                                @else
                                    {{-- Notificaciones de cita (tipos 1, 2, 3) --}}
                                    <h6 class="mb-1 fw-semibold">
                                        @if($apptPatient && $apptPatient->id)
                                            <a href="{{ url('patient/' . $apptPatient->id) }}" class="text-dark">
                                                {{ $apptPatient->first_name }} {{ $apptPatient->last_name }}
                                            </a> —
                                        @endif
                                        {{ $item->title }}
                                        @if(optional($item->user)->id)
                                            <span class="text-muted fw-normal">por
                                                @if(optional(optional($item->user)->roles)->first()?->slug == 'doctor')
                                                    <a href="{{ url('doctor/' . $item->user->id) }}">{{ $item->user->first_name }} {{ $item->user->last_name }}</a>
                                                @else
                                                    {{ $item->user->first_name }} {{ $item->user->last_name }}
                                                @endif
                                            </span>
                                        @endif
                                    </h6>
                                    @if(optional($item->appointment_user)->appointment_date)
                                        <p class="mb-0 small text-muted">
                                            📅 {{ \Carbon\Carbon::parse($item->appointment_user->appointment_date)->format('d/m/Y') }}
                                            @if(optional($item->appointment_user->timeSlot)->from)
                                                · {{ $item->appointment_user->timeSlot->from }} – {{ $item->appointment_user->timeSlot->to }}
                                            @endif
                                        </p>
                                    @endif
                                @endif

                                {{-- Tiempo --}}
                                <p class="mb-0 mt-1 text-muted" style="font-size:0.75rem;">
                                    <i class="mdi mdi-clock-outline me-1"></i>{{ $item->created_at->diffForHumans() }}
                                    @if($isUnread)
                                        <span class="badge bg-primary ms-2" style="font-size:0.65rem;">Nuevo</span>
                                    @endif
                                </p>
                            </div>

                            {{-- Indicador sin leer --}}
                            @if($isUnread)
                                <div class="flex-shrink-0 align-self-center">
                                    <span class="rounded-circle d-inline-block"
                                          style="width:8px;height:8px;background:#1a73e8;"></span>
                                </div>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bx bx-bell-off font-size-48 d-block mb-3 opacity-50"></i>
                        <p class="fw-medium mb-0">No hay notificaciones</p>
                    </div>
                @endforelse
            </div>

            @if($notification->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $notification->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
