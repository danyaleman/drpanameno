@extends('layouts.master-layouts')
@section('title') {{ __('Listado de Consultas') }} @endsection
@section('css')
    <style>
        /* ─── Premium Table Styles ─── */
        .premium-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            overflow: hidden;
            background: #fff;
        }
        
        .premium-header {
            background: linear-gradient(135deg, #10306c 0%, #1565C0 100%);
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .premium-header h4 {
            color: #fff;
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .table-premium thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e9ecef;
            padding: 14px 20px;
        }

        .table-premium tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .table-premium tbody tr:last-child td {
            border-bottom: none;
        }

        .table-premium tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Avatar */
        .patient-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .patient-info h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #212529;
        }

        .patient-info span {
            font-size: 0.8rem;
            color: #74788d;
        }

        /* Badges & Buttons */
        .badge-soft-primary {
            background-color: rgba(85, 110, 230, 0.1);
            color: #556ee6;
            padding: 6px 12px;
            border-radius: 50rem;
            font-weight: 500;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 3px;
            transition: all 0.2s;
            border: none;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-view { background-color: rgba(52, 195, 143, 0.1); color: #34c38f; }
        .btn-edit { background-color: rgba(85, 110, 230, 0.1); color: #556ee6; }
        .btn-delete { background-color: rgba(244, 106, 106, 0.1); color: #f46a6a; }
        .btn-email { background-color: rgba(241, 180, 76, 0.1); color: #f1b44c; }

        #pageloader {
            background: rgba(255, 255, 255, 0.8);
            display: none;
            height: 100%;
            position: fixed;
            width: 100%;
            z-index: 9999;
            top: 0; left: 0;
        }
        #pageloader img {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        }
    </style>
@endsection

@section('content')
    <div id="pageloader">
        <img src="{{ URL::asset('build/images/loader.gif') }}" alt="processing..." />
    </div>

    <!-- Título y Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Consultas Clínicas</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Consultas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card premium-card">
                
                {{-- Header + Filtros --}}
                <div class="premium-header flex-wrap" style="gap: 15px;">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <i class="bx bx-file-blank text-white font-size-22 me-2"></i>
                        <h4>Listado de Recetas y Consultas</h4>
                    </div>
                    
                    <form action="{{ route('prescription.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="input-group input-group-sm bg-white rounded" style="width: auto;">
                            <span class="input-group-text border-0 bg-transparent"><i class="bx bx-search text-muted"></i></span>
                            <input type="text" name="patient_name" class="form-control border-0 shadow-none" placeholder="Buscar por paciente..." value="{{ request('patient_name') }}">
                        </div>
                        <div class="input-group input-group-sm bg-white rounded" style="width: auto;">
                            <span class="input-group-text border-0 bg-transparent"><i class="bx bx-calendar text-muted"></i></span>
                            <input type="date" name="prescription_date" class="form-control border-0 shadow-none" value="{{ request('prescription_date') }}">
                        </div>
                        <button type="submit" class="btn btn-light btn-sm text-primary fw-bold waves-effect waves-light">
                            <i class="bx bx-filter-alt"></i> Filtrar
                        </button>
                        <a href="{{ route('prescription.index') }}" class="btn btn-outline-light btn-sm waves-effect">Limpiar</a>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-premium mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Paciente</th>
                                    @if($role != 'doctor') <th>Doctor</th> @endif
                                    <th>Fecha de Cita</th>
                                    <th>Horario</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $pageLimit = session()->has('page_limit') ? session()->get('page_limit') : Config::get('app.page_limit');
                                    $currentPage = $prescriptions->currentPage();
                                    $startIndex = ($currentPage - 1) * $pageLimit;
                                @endphp

                                @forelse ($prescriptions as $index => $prescription)
                                    @php
                                        // Datos del Paciente
                                        if($prescription->patient) {
                                            $pName = $prescription->patient->first_name . ' ' . $prescription->patient->last_name;
                                            $pMobile = $prescription->patient->mobile;
                                            $initials = substr($prescription->patient->first_name, 0, 1) . substr($prescription->patient->last_name, 0, 1);
                                        } else {
                                            $pName = 'N/A'; $pMobile = ''; $initials = 'NA';
                                        }
                                        
                                        // Color de avatar aleatorio (hash simple)
                                        $colors = ['#556ee6', '#34c38f', '#f1b44c', '#f46a6a', '#50a5f1'];
                                        $colorIndex = ord(substr($initials, 0, 1)) % count($colors);
                                        $avatarColor = $colors[$colorIndex];
                                        $avatarBg = $avatarColor . '20'; // Opacidad 20% hex
                                    @endphp
                                    <tr>
                                        <td><span class="text-muted font-size-13">{{ $startIndex + $index + 1 }}</span></td>
                                        
                                        {{-- Columna Paciente Premium --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="patient-avatar" style="background-color: {{ $avatarBg }}; color: {{ $avatarColor }};">
                                                    {{ strtoupper($initials) }}
                                                </div>
                                                <div class="patient-info">
                                                    <h6>{{ $pName }}</h6>
                                                    @if($pMobile)
                                                        <span><i class="bx bx-phone me-1"></i>{{ $pMobile }}</span>
                                                    @else
                                                        <span>Sin teléfono</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Columna Doctor (si aplica) --}}
                                        @if($role != 'doctor')
                                            <td>
                                                @if($prescription->doctor)
                                                    <span class="badge badge-soft-primary">Dr. {{ $prescription->doctor->first_name }} {{ $prescription->doctor->last_name }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        @endif

                                        {{-- Fecha --}}
                                        <td>
                                            <div class="d-flex align-items-center text-dark">
                                                <i class="bx bx-calendar text-primary me-2 font-size-16"></i>
                                                {{ $prescription->created_at->format('Y-m-d') }}
                                            </div>
                                        </td>

                                        {{-- Hora --}}
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bx bx-time me-1"></i>
                                                {{ $prescription->created_at->format('h:i A') }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="text-end">
                                            <a href="{{ url('prescription/' . $prescription->id) }}">
                                                <button class="btn-action btn-view" title="Ver Consulta">
                                                    <i class="bx bx-show font-size-18"></i>
                                                </button>
                                            </a>
                                            
                                            @if ($role == 'doctor')
                                                <a href="{{ url('prescription/' . $prescription->id . '/edit') }}">
                                                    <button class="btn-action btn-edit" title="Editar">
                                                        <i class="bx bx-pencil font-size-18"></i>
                                                    </button>
                                                </a>
                                                <button class="btn-action btn-delete" id="delete-prescription" data-id="{{ $prescription->id }}" title="Borrar">
                                                    <i class="bx bx-trash font-size-18"></i>
                                                </button>
                                            @endif

                                            @if ($role != 'patient')
                                                <button class="btn-action btn-email send-mail" data-id="{{ $prescription->id }}" title="Enviar por Email">
                                                    <i class="bx bx-envelope font-size-18"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="bx bx-folder-open text-muted font-size-40 mb-3"></i>
                                                <p class="text-muted mb-0">No se encontraron consultas registradas.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginación --}}
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <span class="text-muted small">
                            Mostrando {{ $prescriptions->firstItem() }} - {{ $prescriptions->lastItem() }} de {{ $prescriptions->total() }} registros
                        </span>
                        <div>{{ $prescriptions->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Delete
        $(document).on('click', '#delete-prescription', function() {
            var id = $(this).data('id');
            if (confirm('¿Estás seguro de que deseas eliminar esta consulta?')) {
                $('#pageloader').fadeIn();
                $.ajax({
                    type: "DELETE",
                    url: 'prescription/' + id,
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(data) {
                        toastr.success(data.message);
                        setTimeout(function(){ location.reload(); }, 1000);
                    },
                    error: function(data) {
                        $('#pageloader').fadeOut();
                        toastr.error('Error al eliminar');
                    }
                });
            }
        });

        // Email
        $('.send-mail').click(function() {
            var id = $(this).attr('data-id');
            if (confirm('¿Enviar copia de la consulta por correo?')) {
                $('#pageloader').fadeIn();
                $.ajax({
                    type: "get",
                    url: "prescription-email/" + id,
                    success: function(response) {
                        $('#pageloader').fadeOut();
                        toastr.success(response.message);
                    },
                    error: function(response) {
                        $('#pageloader').fadeOut();
                        toastr.error('Error enviando correo');
                    }
                });
            }
        });
    </script>
@endsection
