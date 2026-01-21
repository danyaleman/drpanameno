@extends('layouts.master-layouts')
@section('title')
    {{ __('List of Accountants') }}
@endsection
@section('css')
    <!-- Datatables -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet" />

    <style type="text/css">
        #accountantList_length label {
            display: inline-flex;
            align-items: center;
            gap: 04px;
        }
    </style>
@endsection
@section('content')
    <!-- start page title -->
    @component('components.breadcrumb')
        @slot('title')
            Accountant List
        @endslot
        @slot('li_1')
            Dashboard
        @endslot
        @slot('li_2')
            Accountants
        @endslot
    @endcomponent
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($role == 'admin')
                        <a href=" {{ route('accountant.create') }} ">
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                                <i class="bx bx-plus font-size-16 align-middle me-2"></i> {{ __('New Accountant') }}
                            </button>
                        </a>
                    @endif
                    <table id="accountantList" class="table table-bordered dt-responsive nowrap "
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>{{ __('Sr. No') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Contact No') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Option') }}</th>
                            </tr>
                        </thead>
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
            $('#accountantList').DataTable({
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                ajax: "{{ route('accountant.index') }}",
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
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'email',
                        name: 'email'
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
        $(document).on('click', '#delete-accountant', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure want to delete accountant?')) {
                $.ajax({
                    type: "DELETE",
                    url: 'accountant/' + id,
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
