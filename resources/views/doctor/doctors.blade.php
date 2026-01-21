@extends('layouts.master-layouts')
@section('css')
    <!-- Datatables -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet" />

    <style type="text/css">
        #doctorList_length label {
            display: inline-flex;
            align-items: center;
            gap: 04px;
        }
    </style>
@endsection
@section('title')
    {{ __('List of Doctors') }}
@endsection
@section('content')
    <!-- start page title -->
    @component('components.breadcrumb')
        @slot('title')
            Doctor List
        @endslot
        @slot('li_1')
            Dashboard
        @endslot
        @slot('li_2')
            Doctors
        @endslot
    @endcomponent
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div></div>
            <div class="card">
                <div class="card-body">
                    @if ($role != 'patient' && $role != 'receptionist')
                        <a href=" {{ route('doctor.create') }} ">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i class="bx bx-plus font-size-16 align-middle me-2"></i> {{ __('New Doctor') }}
                            </button>
                        </a>
                    @endif
                    <table id="doctorList" class="table table-bordered dt-responsive nowrap "
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ __('Sr. No') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Contact No') }}</th>
                                <th>{{ __('Email') }}</th>
                                @if ($role != 'patient')
                                    <th>{{ __('Pending Appointment') }}</th>
                                    <th>{{ __('Complete Appointment') }}</th>
                                @endif
                                @if ($role != 'patient')
                                    <th>{{ __('Option') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <!-- load data using yajra datatables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
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
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js">
    </script>
    <!-- Init js-->
    <script src="{{ URL::asset('build/js/pages/notification.init.js') }}"></script>
    <script>
        //load datatable 
        $(document).ready(function() {
            var role = '{{ $role }}';

            $('#doctorList').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-secondary'
                    },
                    {
                        extend: 'excel',
                        className: 'excelButton'
                    },
                    {
                        extend: 'pdf',
                        className: 'excelButton'
                    }
                ],
                ajax: "{{ route('doctor.index') }}",
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'doctor.title',
                    name: 'title',
                    sortable: false,
                    visible: true
                }, {
                    data: 'name',
                    name: 'name',
                    sortable: false,
                    visible: true
                }, {
                    data: 'doctor.department.name',
                    name: 'department',
                    sortable: false,
                    visible: true
                }, {
                    data: 'mobile',
                    name: 'mobile'
                }, {
                    data: 'email',
                    name: 'email'
                }, {
                    data: 'pending_appointment',
                    name: 'pending_appointment',
                    orderable: false,
                    searchable: false,
                    visible: (role != 'patient') ? true : false
                }, {
                    data: 'completed_appointment',
                    name: 'completed_appointment',
                    orderable: false,
                    searchable: false,
                    visible: (role != 'patient') ? true : false
                }, {
                    data: 'option',
                    name: 'option',
                    orderable: false,
                    searchable: false,
                    visible: (role != 'patient') ? true : false
                }, ],
                pagingType: 'full_numbers',
                "drawCallback": function() {
                    $('.dataTables_paginate > .pagination').addClass('justify-content-end');
                    $('.dataTables_filter').addClass('d-flex justify-content-end');
                }
            });
        });

        // delete Doctor
        $(document).on('click', '#delete-doctor', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure want to delete doctor?')) {
                $.ajax({
                    type: "DELETE",
                    url: 'doctor/' + id,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    beforeSend: function() {
                        $('#pageloader').show()
                    },
                    success: function(response) {
                        toastr.success(response.message, 'Success Alert', {
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
