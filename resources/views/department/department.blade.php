@extends('layouts.master-layouts')
@section('title')
    {{ __('List of Departments') }}
@endsection
@section('css')
    <!-- Datatables -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet" />
@endsection
@section('content')
    <!-- start page title -->
    @component('components.breadcrumb')
        @slot('title')
            Department List
        @endslot
        @slot('li_1')
            Dashboard
        @endslot
        @slot('li_2')
            Departments
        @endslot
    @endcomponent
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($role == 'admin')
                        <a href=" {{ route('department.create') }} ">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i class="bx bx-plus font-size-16 align-middle me-2"></i> {{ __('New Department') }}
                            </button>
                        </a>
                    @endif
                    <table id="departmentList" class="table table-bordered dt-responsive nowrap "
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ __('Sr. No') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('No. of Doctors') }}</th>
                                <th>{{ __('Option') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
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
            $('#departmentList').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                ajax: "{{ route('department.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        sortable: false,
                        visible: true
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'doctor_count',
                        name: 'doctor_count'
                    },
                    {
                        data: 'option',
                        name: 'option',
                        orderable: false,
                        searchable: false
                    },
                ],
                pagingType: 'full_numbers',
                "drawCallback": function() {
                    $('.dataTables_paginate > .pagination').addClass('justify-content-end');
                    $('.dataTables_filter').addClass('d-flex justify-content-end');
                }
            });
        });

        // Delete Prescription
        $(document).on('click', '#delete-department', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure want to delete department?')) {
                $.ajax({
                    type: "DELETE",
                    url: 'department/' + id,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    beforeSend: function() {
                        $('#pageloader').show()
                    },
                    success: function(response) {
                        toastr.success(response.message, 'Success Alert', {
                            timeOut: 1000
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
