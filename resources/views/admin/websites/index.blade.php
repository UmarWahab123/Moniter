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
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                    
                                       <figure class="highcharts-figure">
                                            <div id="container"></div>
                                            <p class="highcharts-description">
                                                Website up and down status logs.
                                            </p>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <span class="h4">Websites</span>
                            <button class="btn btn-primary btn-sm float-right" id="addWebsiteBtn"> <i class="fa fa-plus"></i> Add Website</button>
                            <div class="table-responsive">
                                <table id="websitesDataTable" class="table table-stripped text-center">
                                    <thead>
                                        <tr>

                                            <th>#</th>
                                            <th>Website </th>
                                            <th>Status Changed On </th>
                                            <th>Last Checked On </th>
                                            <th>Status </th>
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
        <div class="modal fade" id="addWebsiteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Website</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="addWebsiteForm">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">URL</label>
                                <input type="text" name="url" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Url">
                            </div>
                            <div class="form-check d-none">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                            <button type="submit" id="websiteSubmitBtn" class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>
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
            var table = $('#websitesDataTable').DataTable({
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
                ajax: "{{ route('websites') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'status_change_on',
                        name: 'status_change_on'
                    },
                    {
                        data: 'last_status_check',
                        name: 'last_status_check'
                    },
                     {
                        data: 'status',
                        name: 'status'
                    },


                ],

            });

            $('#addWebsiteBtn').on('click', function() {
                $('#addWebsiteModal').modal('show');
            });
            $('#addWebsiteForm').on('submit', function(e) {
                e.preventDefault();
                var form = $('#addWebsiteForm').serialize();

                $.ajax({
                    url: '{{ route('add-website') }}', 
                    method: 'post',
                    data: form,
                    beforeSend: function() {
                        $('#websiteSubmitBtn').prop('disabled', true);
                        $('#websiteSubmitBtn').html('Please wait...');
                    },
                    success: function(data) {
                        $('#addWebsiteModal').modal('hide');
                        $('#addWebsiteForm')[0].reset();
                        toastr.success('Success!', 'Website added successfully and being monitored' ,{"positionClass": "toast-bottom-right"});
                        $('#websitesDataTable').DataTable().ajax.reload();
                        $('#websiteSubmitBtn').prop('disabled', false);
                        $('#websiteSubmitBtn').html('Submit');
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                        $('#websiteSubmitBtn').prop('disabled', false);
                        $('#websiteSubmitBtn').html('Submit');
                    },
                })
            });
Highcharts.chart('container', {

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

});
        </script>
        @endsection
