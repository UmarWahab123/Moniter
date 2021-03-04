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
    @include('admin.assets.sidebar')
    <!-- sidebar menu area end -->
    <!-- main content area start -->
    <div class="main-content">
        <!-- header area start -->
        {{-- @include('admin.assets.header') --}}

        <!-- header area end -->
        <!-- page title area start -->
        @include('admin.assets.title_area')

        <!-- page title area end -->
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
               
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <span class="h4">Devices</span>
                            <button class="btn btn-primary btn-sm float-right d-none" id=""> <i class="fa fa-plus"></i> Add Device</button>
                            <div class="table-responsive mt-3">
                                <table id="devicesDataTable" class=" table table-stripped text-center">
                                    <thead>
                                        <tr>

                                            <th>Device Name</th>
                                            <th>Logged In</th>
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
            var table = $('#devicesDataTable').DataTable({
                // "bAutoWidth": false,
                processing: true,
                searching: true,
                ordering: true,
                "processing": true,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },

                scrollCollapse: true,
                ajax: "{{ route('devices') }}",
                columns: [
                   
                    {
                        data: 'device_name',
                        name: 'device_name'
                    },
                    {
                        data: 'logged_in',
                        name: 'logged_in'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });
            $(document).on('click','.logout-btn', function(e) {

                var device_id = $(this).val();
                Swal.fire({
                title: 'Are you sure?',
                text: "You want to logout from this device",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
                }).then((result) => {
                if (result.value) {
                   $.ajax({
                    url: '{{ route('device-logout') }}', 
                    method: 'post',
                    data: {"_token": "{{ csrf_token() }}",device_id:device_id},
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                    success: function(data) {
                        toastr.success('Success!', 'Log out successfully' ,{"positionClass": "toast-bottom-right"});
                        $('#devicesDataTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                    },
                });
                }
                })
                
            });

        </script>
        @endsection
