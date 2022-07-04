@extends('layouts.app')

@section('content')
    <!--[if lt IE 8]>
                                                    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
                                                <![endif]-->
    <!-- preloader area start -->
    {{-- <div id="preloader">
        <div class="loader"></div>
    </div> --}}
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        @include('superAdmin.assets.sidebar')
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            {{-- @include('admin.assets.header') --}}

            <!-- header area end -->
            <!-- page title area start -->
            @include('superAdmin.assets.title_area')

            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="row">
                    <!-- data table start -->

                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <span class="h4">Users</span>
                                    <table id="usersDataTable" class=" table table-stripped text-center">
                                        <thead>
                                            <tr>

                                                <th>Name </th>
                                                <th>Email </th>
                                                <th>Total Websites </th>
                                                <th>Status </th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                    </div>
                </div>
                <!-- main content area end -->
                <!-- footer area start-->
                @include('superAdmin.assets.footer')

                <!-- footer area end-->
            </div>

            @include('superAdmin.assets.javascript')
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
                var table = $('#usersDataTable').DataTable({
                    // "bAutoWidth": false,
                    serverSide: true,
                    processing: true,
                    searching: true,
                    ordering: true,
                    "processing": true,
                    'language': {
                        'loadingRecords': '&nbsp;',
                        'processing': 'Loading...'
                    },

                    scrollCollapse: true,
                    ajax: "{{ route('superAdmin.get-data') }}",
                    columns: [

                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'counter',
                            name: 'counter'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },


                    ],
                });
                $(document).on('click', '.user-status', function(e) {
                    var id = $(this).val();
                    var status = $(this).data('status');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to Susupemd/Un-Suspend the selected User!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, do it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '{{ route('superAdmin.user-status') }}',
                                method: 'get',
                                data: {
                                    id: id,
                                    status: status
                                },
                                success: function(data) {
                                    toastr.success('Success!', 'Action Performed successfully', {
                                        "positionClass": "toast-bottom-right"
                                    });
                                    $('#usersDataTable').DataTable().ajax.reload();
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
                $(document).on('click', '.btn_delete', function(e) {
                    var id = $(this).val();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to Delete the selected User. You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, do it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '{{ route("superAdmin.user-delete") }}',
                                method: 'post',
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    toastr.success('Success!', 'User Deleted successfully', {
                                        "positionClass": "toast-bottom-right"
                                    });
                                    $('#usersDataTable').DataTable().ajax.reload();
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
