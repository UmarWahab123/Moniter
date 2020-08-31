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
        {{-- @include('assets.header') --}}

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
                            <span class="h4">Users</span>
                            <button class="btn btn-primary btn-sm float-right" id="addUserBtn"> <i class="fa fa-plus"></i> Add User</button>
                            <div class="table-responsive">
                                <table id="usersDataTable" class="table table-stripped text-center">
                                    <thead>
                                        <tr>

                                            <th>#</th>
                                            <th>Name </th>
                                            <th>Email </th>
                                            <th>Role </th>
                                            <th> </th>
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
        <div class="modal fade" id="addUserModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="addUserForm">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="" placeholder="Enter Name">
                            </div>
                              <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email">
                            </div>
                           
                            <div class="form-check d-none">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                            <button type="submit" id="userSubmitBtn" class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
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
            var table = $('#usersDataTable').DataTable({
                // "bAutoWidth": false,
                processing: true,
                searching: false,
                ordering: true,
                "processing": true,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },

                scrollCollapse: true,
                ajax: "{{ route('users') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
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

            $('#addUserBtn').on('click', function() {
                $('#addUserModal').modal('show');
            });
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                var form = $('#addUserForm').serialize();

                $.ajax({
                    url: '{{ route('add-user') }}', 
                    method: 'post',
                    data: form,
                    beforeSend: function() {
                        $('#userSubmitBtn').prop('disabled', true);
                        $('#userSubmitBtn').html('Please wait...');
                    },
                    success: function(data) {
                        $('#addUserModal').modal('hide');
                        $('#addUserForm')[0].reset();
                        toastr.success('Success!', 'User added successfully' ,{"positionClass": "toast-bottom-right"});
                        $('#usersDataTable').DataTable().ajax.reload();
                        $('#userSubmitBtn').prop('disabled', false);
                        $('#userSubmitBtn').html('Submit');
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                        $('#userSubmitBtn').prop('disabled', false);
                        $('#userSubmitBtn').html('Submit');
                    },
                })
            });
            $(document).on('click','.btn-danger', function(e) {
                var id = $(this).val();
                $.ajax({
                    url: '{{ route('suspend-user') }}', 
                    method: 'get',
                    data: {id:id},
                    success: function(data) {
                        toastr.success('Success!', 'User suspended successfully' ,{"positionClass": "toast-bottom-right"});
                        $('#usersDataTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                
                    },
                })
            });
{{-- Highcharts.chart('container', {

    title: {
        text: 'Up and Down Time Check of Servers'
    },

    subtitle: {
        text: ''
    },

    yAxis: {
        title: {
            text: 'Number of Employees'
        }
    },

    xAxis: {
        accessibility: {
            rangeDescription: 'Range: 2010 to 2017'
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'Installation',
        data: [1, 2]
    },  {
        name: 'Other',
        data: [1, 1.8]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

}); --}}
        </script>
        @endsection
