@extends('layouts.master-layouts')
@section('title') {{ __('Pacientes') }} @endsection

@section('css')
    <!-- Datatables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet" />
    <style>
        /* ── Premium DataTable Overrides ── */
        #patientList_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        #patientList_wrapper .dataTables_filter input:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 3px rgba(26,115,232,.15);
            outline: none;
        }
        #patientList_wrapper .dataTables_length label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: #5a6a85;
        }
        #patientList_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.85rem;
        }
        #patientList_wrapper .dataTables_info {
            font-size: 0.85rem;
            color: #5a6a85;
        }
        #patientList_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            margin: 0 2px;
        }
        #patientList_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg,#1a73e8,#0d47a1) !important;
            color: #fff !important;
            border: none !important;
        }
        #patientList_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e8f0fe !important;
            color: #1a73e8 !important;
            border: 1px solid #e8f0fe !important;
        }
        #patientList_wrapper .dt-buttons .btn {
            border-radius: 6px;
            font-size: 0.8rem;
            padding: 5px 14px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #5a6a85;
            margin-right: 4px;
            transition: all 0.2s;
        }
        #patientList_wrapper .dt-buttons .btn:hover {
            background: #1a73e8;
            color: #fff;
            border-color: #1a73e8;
        }
        #patientList {
            border: none !important;
        }
        #patientList thead th {
            background: #f8f9fa !important;
            border: none !important;
            font-size: 0.8rem;
            font-weight: 600;
            color: #6c757d;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 14px 16px !important;
        }
        #patientList tbody td {
            border: none !important;
            border-bottom: 1px solid #f0f2f5 !important;
            padding: 12px 16px !important;
            vertical-align: middle;
        }
        #patientList tbody tr:hover {
            background-color: #f8f9ff !important;
        }
    </style>
@endsection

@section('content')
{{-- Encabezado de página --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">👥 Base de Datos de Pacientes</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pacientes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Tarjeta de estadísticas rápida --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <i class="bx bx-group text-white font-size-22"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-semibold">TOTAL PACIENTES</p>
                    <h4 class="mb-0 fw-bold" id="stat-total">—</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#28a745,#1e7e34);">
                    <i class="bx bx-user-plus text-white font-size-22"></i>
                </div>
                <div>
                    <p class="text-muted mb-1 small fw-semibold">NUEVOS ESTE MES</p>
                    <h4 class="mb-0 fw-bold text-success" id="stat-new">—</h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Buscador y filtros avanzados --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body p-3">
        <div class="row align-items-end g-2">
            <div class="col-md-2">
                <label for="filterAgeMin" class="form-label" style="font-size: 0.75rem; font-weight: 600; color: #6c757d; letter-spacing: 0.5px;">EDAD MÍN (AÑOS)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-down-arrow-alt"></i></span>
                    <input type="number" id="filterAgeMin" class="form-control border-start-0 ps-0" placeholder="Ej: 18" min="0">
                </div>
            </div>
            <div class="col-md-2">
                <label for="filterAgeMax" class="form-label" style="font-size: 0.75rem; font-weight: 600; color: #6c757d; letter-spacing: 0.5px;">EDAD MÁX (AÑOS)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-up-arrow-alt"></i></span>
                    <input type="number" id="filterAgeMax" class="form-control border-start-0 ps-0" placeholder="Ej: 60" min="0">
                </div>
            </div>
            <div class="col-md-3">
                <label for="filterDepto" class="form-label" style="font-size: 0.75rem; font-weight: 600; color: #6c757d; letter-spacing: 0.5px;">DEPARTAMENTO</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bx bx-map"></i></span>
                    <select id="filterDepto" class="form-select border-start-0 ps-0">
                        <option value="">Cualquier ubicación</option>
                        <option value="Ahuachapán">Ahuachapán</option>
                        <option value="Cabañas">Cabañas</option>
                        <option value="Chalatenango">Chalatenango</option>
                        <option value="Cuscatlán">Cuscatlán</option>
                        <option value="La Libertad">La Libertad</option>
                        <option value="La Paz">La Paz</option>
                        <option value="La Unión">La Unión</option>
                        <option value="Morazán">Morazán</option>
                        <option value="San Miguel">San Miguel</option>
                        <option value="San Salvador">San Salvador</option>
                        <option value="San Vicente">San Vicente</option>
                        <option value="Santa Ana">Santa Ana</option>
                        <option value="Sonsonate">Sonsonate</option>
                        <option value="Usulután">Usulután</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" id="btnFilter" class="btn btn-primary w-100 shadow-sm font-size-13 fw-bold">
                    <i class="bx bx-filter-alt me-1"></i> Filtrar
                </button>
            </div>
            <div class="col-md-3 text-end">
                <div class="bg-primary-subtle text-primary rounded px-3 py-2 d-inline-block fw-semibold" id="filterResultLabel" style="font-size:13px; min-width:180px;">
                    <i class="bx bx-select-multiple me-1 align-middle font-size-16"></i> <span id="lblCount">—</span> registros
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabla de pacientes --}}
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between py-3"
         style="background: linear-gradient(135deg,#1a73e8,#0d47a1);">
        <div class="d-flex align-items-center">
            <i class="bx bx-list-ul text-white font-size-20 me-2"></i>
            <h5 class="mb-0 text-white fw-bold">Listado de Pacientes</h5>
        </div>
        <a href="{{ route('patient.create') }}" class="btn btn-light btn-sm waves-effect">
            <i class="bx bx-plus me-1"></i>Agregar Paciente
        </a>
    </div>
    <div class="card-body">
        <table id="patientList" class="table table-hover align-middle mb-0 w-100">
            <thead>
                <tr>
                    <th>PACIENTE</th>
                    <th>EDAD</th>
                    <th>DIRECCIÓN</th>
                    <th>TELÉFONO</th>
                    <th>CORREO ELECTRÓNICO</th>
                    <th class="text-center">ACCIONES</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <!-- Datatables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- Init js-->
    <script src="{{ URL::asset('build/js/pages/notification.init.js') }}"></script>
    <script>
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex, rowData, counter) {
            if (settings.nTable.id !== 'patientList') return true;

            var minAgeStr = $('#filterAgeMin').val();
            var maxAgeStr = $('#filterAgeMax').val();
            var minAge = parseInt(minAgeStr, 10);
            var maxAge = parseInt(maxAgeStr, 10);
            var deptoStr = $('#filterDepto').val().toLowerCase();

            var ageStr = rowData.age || '';
            var ageMatch = ageStr.match(/\d+/);
            var age = ageMatch ? parseInt(ageMatch[0], 10) : null;
            var addStr = (rowData.address || '').toLowerCase();

            // Filtro por dirección depto
            if (deptoStr !== '' && addStr.indexOf(deptoStr) === -1) {
                return false;
            }

            // Filtro de edad mínima
            if (!isNaN(minAge) && minAgeStr !== "") {
                if (age === null || age < minAge) return false;
            }

            // Filtro de edad máxima
            if (!isNaN(maxAge) && maxAgeStr !== "") {
                if (age === null || age > maxAge) return false;
            }

            return true;
        });

        $(document).ready(function() {
            var table = $('#patientList').DataTable({
                processing: true,
                serverSide: false,
                dom: '<"row mb-3"<"col-sm-6"B><"col-sm-6"f>>rt<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="bx bx-copy me-1"></i>Copiar',
                        className: 'btn btn-sm'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bx bx-file me-1"></i>Excel',
                        className: 'btn btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="bx bx-file-blank me-1"></i>PDF',
                        className: 'btn btn-sm'
                    }
                ],
                ajax: "{{ route('patient.index') }}",
                columns: [
                    {
                        data: null,
                        name: 'name',
                        sortable: true,
                        render: function(data, type, row) {
                            var firstName = row.first_name || 'N/A';
                            var lastName = row.last_name || '';
                            var fullName = firstName + ' ' + lastName;
                            var initials = (firstName.charAt(0) + (lastName ? lastName.charAt(0) : '')).toUpperCase();
                            return '<div class="d-flex align-items-center">' +
                                '<div class="avatar-sm me-3 flex-shrink-0">' +
                                    '<div class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold">' +
                                        initials +
                                    '</div>' +
                                '</div>' +
                                '<div>' +
                                    '<p class="mb-0 fw-semibold text-dark">' + fullName + '</p>' +
                                '</div>' +
                            '</div>';
                        }
                    },
                    {
                        data: 'age',
                        name: 'age',
                        render: function(data) {
                            return '<span class="text-muted">' + (data || 'N/A') + '</span>';
                        }
                    },
                    {
                        data: 'address',
                        name: 'address',
                        render: function(data) {
                            return '<span class="text-muted">' + (data || 'N/A') + '</span>';
                        }
                    },
                    {
                        data: 'mobile',
                        name: 'mobile',
                        render: function(data) {
                            return '<span class="fw-medium"><i class="bx bx-phone text-muted me-1"></i>' + (data || 'N/A') + '</span>';
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data) {
                            return '<span class="text-muted"><i class="bx bx-envelope me-1"></i>' + (data || 'N/A') + '</span>';
                        }
                    },
                    {
                        data: 'option',
                        name: 'option',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                language: {
                    processing: '<div class="d-flex align-items-center gap-2"><div class="spinner-border spinner-border-sm text-primary"></div> Cargando...</div>',
                    search: '<i class="bx bx-search text-muted"></i>',
                    searchPlaceholder: 'Buscar paciente...',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ pacientes',
                    infoEmpty: 'No hay pacientes registrados',
                    infoFiltered: '(filtrado de _MAX_ pacientes totales)',
                    zeroRecords: '<div class="text-center py-4"><i class="bx bx-search font-size-48 d-block mb-3 text-primary opacity-50"></i><p class="fw-medium text-muted mb-0">No se encontraron pacientes</p></div>',
                    emptyTable: '<div class="text-center py-4"><i class="bx bx-user-plus font-size-48 d-block mb-3 text-primary opacity-50"></i><p class="fw-medium text-muted mb-2">No hay pacientes registrados</p><a href="{{ route('patient.create') }}" class="btn btn-primary btn-sm"><i class="bx bx-plus me-1"></i>Agregar primer paciente</a></div>',
                    paginate: {
                        first: '<i class="bx bx-chevrons-left"></i>',
                        last: '<i class="bx bx-chevrons-right"></i>',
                        next: '<i class="bx bx-chevron-right"></i>',
                        previous: '<i class="bx bx-chevron-left"></i>'
                    }
                },
                pagingType: 'full_numbers',
                pageLength: 15,
                order: [[0, 'asc']],
                drawCallback: function(settings) {
                    // Actualizar stats
                    var info = this.api().page.info();
                    $('#stat-total').text(info.recordsTotal);
                    $('#lblCount').text(info.recordsDisplay);
                }
            });

            // Enlazamos filtros a la tabla
            $('#filterAgeMin, #filterAgeMax').on('keyup', function() { table.draw(); });
            $('#filterDepto').on('change', function() { table.draw(); });
            $('#btnFilter').on('click', function() { table.draw(); });

            // Calcular pacientes nuevos este mes via los datos cargados
            table.on('xhr', function() {
                var data = table.ajax.json().data;
                if (data) {
                    var now = new Date();
                    var currentMonth = now.getMonth();
                    var currentYear = now.getFullYear();
                    var newThisMonth = 0;
                    data.forEach(function(row) {
                        if (row.created_at) {
                            var d = new Date(row.created_at);
                            if (d.getMonth() === currentMonth && d.getFullYear() === currentYear) {
                                newThisMonth++;
                            }
                        }
                    });
                    $('#stat-new').text(newThisMonth);
                }
            });
        });

        // Delete patient
        $(document).on('click', '#delete-patient', function() {
            var id = $(this).data('id');
            if (confirm('¿Estás seguro de que quieres eliminar este paciente?')) {
                $.ajax({
                    type: "DELETE",
                    url: 'patient/' + id,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    beforeSend: function() {
                        $('#pageloader').show()
                    },
                    success: function(response) {
                        toastr.success(response.message, 'Éxito', {
                            timeOut: 2000
                        });
                        location.reload();
                    },
                    error: function(response) {
                        toastr.error(response.responseJSON.message, {
                            timeOut: 20000
                        });
                    },
                    complete: function() {
                        $('#pageloader').hide();
                    }
                });
            }
        });
    </script>
@endsection
