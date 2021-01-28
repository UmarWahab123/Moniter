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
                        {{-- <h2 class="text-uppercase">{{($website->getSiteDetails !=null )?$website->getSiteDetails->title:'N/A'}} <small></small></h2> --}}
                        <table class="table table-sm table-borderless w-50 custom-table"> 
                            <thead>
                                <th class="text-uppercase">
                                   <h2> {{($website->getSiteDetails !=null )?$website->getSiteDetails->title:'N/A'}}</h2>
                                </th>
                                            
                                <th>
                                    @if($website->uptime_status=='up')
                                        <span class="badge badge-success text-white px-4 text-uppercase ">Up</span>
                                    @elseif($website->uptime_status=='down')
                                        <span class="badge badge-danger text-white px-4 text-uppercase">Down</span>
                                    @else
                                        <span class="badge badge-warning text-white">Not Yet Checked</span>
                                    @endif
                                </th>
                            </thead>
                           <tbody>
                                 <tr>
                                    <th>Website</th>
                                    <td>{{$website->url}}</td>
                                </tr>
                                    <tr>
                                    <th>Certificate Check</th>
                                    <td>@if($website->certificate_check_enabled==1) <span class="badge badge-success "><i class="fa fa-check"></i></span> @else <span class="badge badge-danger "> <i class="fa fa-times"></i> </span> @endif</td>
                                </tr>
                                <tr>
                                    <th>Certificate Expiry Date</th>
                                    <td>{{$website->certificate_expiration_date}}</td>
                                </tr>
                                  @php
                                    if($website->getSiteLogs!=null)
                                    {
                                        $logs=$website->getSiteLogs->first();
                                    }
                                @endphp
                                <tr>
                                    <th>Last Down</th>
                                    <td>{{($logs!=null)?date('Y-m-d H:i:s',strtotime($logs->down_time)):'--'}}</td>
                                </tr>
                                <tr>
                                    <th>Last Up</th>
                                    <td>{{($logs!=null)?date('Y-m-d H:i:s',strtotime($logs->up_time)):'--'}}</td>
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
                                            <th>Up Time </th>
                                            <th>Down Reason </th>
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
                searching: false,
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
                    {
                        data: 'down_reason',
                        name: 'down_reason'
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
