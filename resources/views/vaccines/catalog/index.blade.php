@extends('layouts.master-layouts')

@section('title') Catálogo de Vacunas @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">💉 Catálogo de Vacunas</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Catálogo de Vacunas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between py-3"
                 style="background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);">
                <div class="d-flex align-items-center">
                    <i class="bx bx-injection text-white font-size-22 me-2"></i>
                    <h5 class="mb-0 text-white fw-bold">Vacunas Registradas</h5>
                </div>
                <a href="{{ route('vaccines.catalog.create') }}" class="btn btn-light btn-sm waves-effect">
                    <i class="bx bx-plus me-1"></i> Nueva Vacuna
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">#</th>
                                <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">VACUNA</th>
                                <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">CÓDIGO</th>
                                <th class="py-3 text-muted fw-semibold" style="font-size:0.8rem;letter-spacing:0.05em;">FABRICANTE</th>
                                <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">DOSIS</th>
                                <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">APLICACIONES</th>
                                <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">ESTADO</th>
                                <th class="py-3 text-muted fw-semibold text-center" style="font-size:0.8rem;letter-spacing:0.05em;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vaccines as $vaccine)
                            <tr class="border-bottom">
                                <td class="ps-4 py-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 flex-shrink-0">
                                            <div class="avatar-title rounded-circle fw-bold"
                                                 style="background: linear-gradient(135deg,#1a73e8,#0d47a1); color:#fff; font-size:1.1rem;">
                                                <i class="bx bx-injection"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold text-dark">{{ $vaccine->name }}</p>
                                            @if($vaccine->description)
                                                <small class="text-muted">{{ Str::limit($vaccine->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($vaccine->code)
                                        <code class="bg-light px-2 py-1 rounded">{{ $vaccine->code }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="py-3">{{ $vaccine->manufacturer ?? '—' }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 fw-medium">
                                        {{ $vaccine->total_doses }} dosis
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 fw-medium">
                                        {{ $vaccine->records_count }} aplicaciones
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    @if($vaccine->is_active)
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                            <i class="bx bx-check-circle me-1"></i>Activa
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                                            <i class="bx bx-x-circle me-1"></i>Inactiva
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('vaccines.catalog.edit', $vaccine->id) }}"
                                           class="btn btn-sm btn-outline-primary waves-effect"
                                           title="Editar vacuna">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('vaccines.catalog.toggle', $vaccine->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm {{ $vaccine->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} waves-effect"
                                                    title="{{ $vaccine->is_active ? 'Desactivar' : 'Activar' }}">
                                                <i class="bx {{ $vaccine->is_active ? 'bx-pause' : 'bx-play' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-injection font-size-48 d-block mb-3 text-primary opacity-50"></i>
                                        <p class="mb-2 fw-medium">No hay vacunas en el catálogo</p>
                                        <a href="{{ route('vaccines.catalog.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bx bx-plus me-1"></i>Agregar primera vacuna
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
