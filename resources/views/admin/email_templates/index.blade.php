@extends('layouts.app')

@section('content')
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        @include('admin.assets.sidebar')
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            @include('admin.assets.title_area')

            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-8 title-col">
                                <h4 class="maintitle">EMAIL TEMPLATES</h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('templates.create') }}"
                                    class="btn btn-primary button-st btn-wd btn_add_email">ADD
                                    EMAIL TEMPLATE</a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="table-responsive" width="100">
                                <table class="table table-stripped text-center Table_email_templates w-100">
                                    <thead class="text-center">
                                        <tr>
                                            <th>Action</th>
                                            <th>Name</th>
                                            <th>Subject</th>
                                            <th>Last Updated By</th>
                                            <th>Last Updated At</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main content area end -->
                <!-- footer area start-->
                @include('admin.assets.footer')

                <!-- footer area end-->
            </div>
            @include('admin.assets.javascript')
            <!-- Start datatable js -->
            <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
            <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var table = $('.Table_email_templates').DataTable({
                    serverSide: true,
                    processing: true,
                    searching: true,
                    ordering: true,
                    pageLength: {{ 50 }},
                    "processing": true,
                    'language': {
                        'loadingRecords': '&nbsp;',
                        'processing': 'Loading...'
                    },
                    scrollCollapse: true,
                    ajax: {
                        url: "{{ route('templates.getTemplates') }}",
                    },
                    columns: [{
                            data: 'action',
                            name: 'action'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'subject',
                            name: 'subject'
                        },
                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at'
                        }
                    ],
                });

                $(document).ready(function() {
                    reloadDatatable();
                });

                function reloadDatatable() {
                    setTimeout(function() {
                        $('.Table_email_templates').DataTable().ajax.reload();
                        reloadDatatable();

                    }, 65000);

                }
                $(document).on('click', '.btn-delete', function(e) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to Delete this Template",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, do it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: $(this).data('url'),
                                method: 'post',
                                success: function(data) {
                                    if (data.success) {
                                        toastr.success('Success!', 'Template Deleted successfully', {
                                            "positionClass": "toast-bottom-right"
                                        });
                                        $('.Table_email_templates').DataTable().ajax.reload();
                                    }
                                },
                                error: function() {
                                    toastr.error('Error!', 'Something went wrong', {
                                        "positionClass": "toast-bottom-right"
                                    });
                                },
                            });
                        }
                    })
                });
            </script>
        @endsection
