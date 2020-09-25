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
                {{-- <div class="col-12 mt-5">
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
                </div> --}}
                <div class="col-12 mt-5">
                        <table class="table table-borderless w-50"> 
                           <tbody>
                                 <tr>
                                    <th>Website</th>
                                    <td>{{$website->url}}</td>
                                </tr>
                                    <tr>
                                    <th>Certificate Check</th>
                                    <td>@if($website->certificate_check_enabled==1) <span class="badge badge-success ">True</span> @else <span class="badge badge-danger "> False </span> @endif</td>
                                </tr>
                                    <tr>
                                    <th>Status</th>
                                    <td>@if($website->uptime_status=='up') <span class="badge badge-success ">Up</span> @else <span class="badge badge-danger ">Down</span> @endif</td>
                                </tr>
                           </tbody>
                        </table>
                    <div class="card">
                        <div class="card-body">
                            <span class="h5 ">Website Logs</span>
                            <div class="table-responsive mt-3">

                                <table id="websitesDetailsDataTable" class="table table-stripped text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Down Time </th>
                                            <th>Up TIme </th>
                                           
                                           

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

        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            var table = $('#websitesDetailsDataTable').DataTable({
                // "bAutoWidth": false,
                processing: true,
                searching: true,
                ordering: true,
                pageLength: {{50}},
                "processing": true,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },
                scrollCollapse: true,
                ajax: {
                    url:"{{ url('admin/website-logs') }}"+"/"+{{$website_id}},
                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                },
                columns: [
                 
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'down_time',
                        name: 'down_time'
                    },
                    {
                        data: 'up_time',
                        name: 'up_time'
                    },

                ],

            });

            $(document).ready(function(){
                    reloadDatatable();                
            });

            function reloadDatatable()
            {
                 setTimeout(function(){           
                    $('#websitesDetailsDataTable').DataTable().ajax.reload();
                    reloadDatatable();              

                }, 65000);
                
            }

         

        </script>
        @endsection
