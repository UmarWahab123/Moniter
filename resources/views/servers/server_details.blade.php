@extends('layouts.app')
@section('content')
        <div class="main-content-inner">
            <div class="sales-report-area sales-style-two pt-2">
            <table class="table table-sm table-borderless w-50 mt-3 custom-table">
                    <thead>
                        <th class="text-uppercase">
                            <h1> {{ $server != null ? $server->name : '' }} Detail </h1>
                        </th>
                    </thead>
                </table>
                <div class="row">
                    <div class="col-xl-3 col-ml-3 col-md-3 mt-2">
                        <div class="single-report">
                        <h3 class="pt-3 ">{{ $server != null ? $server->name : '' }} Basic Info</h3>
                        <div class="s-sale-inner pt--30 mb-3">
                            <div class=" d-flex justify-content-between mt-2">
                                <label for="user_name" class="bg-white pl-0 font-weight-bold">Server Name</label>
                                @if(@$server->name != null)
                                <p class="bg-info badge text-white"> 
                                  {{@$server->name}}  
                               </p>
                                  @else
                                  <span class="bg-info badge text-white">N/A</span>
                                @endif
                            </div>
                            <div class=" d-flex justify-content-between mt-2">
                                <label for="user_name" class="bg-white pl-0 font-weight-bold">IP Address</label>
                                @if(@$server->ip_address != null)
                                <p class="bg-info badge text-white"> 
                                    {{@$server->ip_address}}  </p>
                                    @else
                                    <span class="bg-info badge text-white">N/A</span>
                                  @endif
                            </div>
                            <div class=" d-flex justify-content-between mt-2">
                                <label for="user_name" class="bg-white pl-0 font-weight-bold">Operating System</label>
                                @if(@$server->os != null)
                                <p class="bg-info badge text-white">
                                    {{@$server->os}} </p> 
                                    @else
                                   <span class="bg-info badge text-white">N/A</span>
                                 @endif
                            </div>
                            <div class=" d-flex justify-content-between mt-2 ">
                                <label for="user_name" class="bg-white pl-0 font-weight-bold">User Name</label>
                                @if(@$server->userInfo->name != null)
                                <p class="bg-info badge text-white">
                                    {{@$server->userInfo->name}}  </p>
                                    @else
                                    <span class="bg-info badge text-white">N/A</span>
                                 @endif
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="main-content-inner">
        
                <div class="row">
                    <div class="col-12">
                    <button class="btn btn-primary btn-sm float-right" value="{{Auth::user()->id}}" id="assignWebsiteBtn"> <i
                            class="fa fa-plus"></i> Assign Website</button>
                    
                        <ul class="nav nav-pills mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active cpu-usage-tab" id="cpu-usage-tab" data-toggle="tab"
                                    href="#cpu_usage" role="tab" aria-controls="cpu_usage" aria-selected="true">CPU
                                    USAGE</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link ram-usage-tab" id="ram-usage-tab" data-toggle="tab" href="#ram_usage"
                                    role="tab" aria-controls="ram_usage" aria-selected="false">RAM USAGE</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link disk_usage-tab" id="disk_usage-tab" data-toggle="tab" href="#disk_usage"
                                    role="tab" aria-controls="disk_usage" aria-selected="false">DISK USAGE</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link os-release-tab" id="os-release-tab" data-toggle="tab" href="#os_release"
                                    role="tab" aria-controls="os_release" aria-selected="false">OS RELEASE</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link total-users-tab" id="total-users-tab" data-toggle="tab"
                                    href="#total_users" role="tab" aria-controls="total_users"
                                    aria-selected="false">TOTAL USERS</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link incidents-tab" id="incidents-tab" data-toggle="tab"
                                    href="#incidents" role="tab" aria-controls="incidents"
                                    aria-selected="false">Incidents</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link services-tab" id="services-tab" data-toggle="tab"
                                    href="#services" role="tab" aria-controls="services"
                                    aria-selected="false">Services</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="cpu_usage" role="tabpanel"
                                aria-labelledby="cpu-usage-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="h5 ">CPU USAGE LOGS</span>
                                        <div class="table-responsive mt-3">
                                            <table id="cpu_usage_logs_table" class="table table-stripped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>User</th>
                                                        <th>Nice</th>
                                                        <th>System</th>
                                                        <th>Iowait</th>
                                                        <th>Steal</th>
                                                        <th>Idle</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="ram_usage" role="tabpanel" aria-labelledby="ram-usage-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="h5 ">RAM USAGE LOGS</span>
                                        <ul class="nav nav-tabs mt-3" id="myTab1" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active ram-memory-tab" id="ram-memory-tab"
                                                    data-toggle="tab" href="#ram_memory" role="tab"
                                                    aria-controls="ram_memory" aria-selected="true">MEMORY</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link ram-swap-tab" id="ram-swap-tab" data-toggle="tab"
                                                    href="#ram_swap" role="tab" aria-controls="ram_swap"
                                                    aria-selected="false">SWAP</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent1">
                                            <div class="tab-pane fade show active" id="ram_memory" role="tabpanel"
                                                aria-labelledby="ram-memory-tab">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="h5 ">RAM MEMORY LOGS</span>
                                                        <div class="table-responsive mt-3">
                                                            <table id="ram_memory_usage_logs_table"
                                                                class="table table-stripped text-center">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Total</th>
                                                                        <th>Used</th>
                                                                        <th>Free</th>
                                                                        <th>Shared</th>
                                                                        <th>Buff/Cache</th>
                                                                        <th>Available</th>
                                                                        <th>Created At</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="ram_swap" role="tabpanel"
                                                aria-labelledby="ram-swap-tab">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <span class="h5 ">RAM SWAP LOGS</span>
                                                        <div class="table-responsive mt-3">
                                                            <table id="ram_swap_usage_logs_table"
                                                                class="table table-stripped text-center">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Total</th>
                                                                        <th>Used</th>
                                                                        <th>Free</th>
                                                                        <th>Created At</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="disk_usage" role="tabpanel" aria-labelledby="disk_usage-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="h5 ">DISK USAGE LOGS</span>
                                        <div class="table-responsive mt-3">
                                            <table id="disk_usage_logs_table" class="table table-stripped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Size</th>
                                                        <th>Used</th>
                                                        <th>Available</th>
                                                        <th>Used (%)</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="os_release" role="tabpanel" aria-labelledby="os-release-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="h5 ">OS RELEASE DETAIL</span>
                                        <div class="table-responsive mt-3">
                                            <table id="os_release_logs_table" class="table table-stripped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Version</th>
                                                        <th>ID</th>
                                                        <th>ID Like</th>
                                                        <th>Pretty Name</th>
                                                        <th>Version ID</th>
                                                        <th>Home URL</th>
                                                        <th>Support URL</th>
                                                        <th>BUG Report URL</th>
                                                        <th>Privacy Policy URL</th>
                                                        <th>Version Code Name</th>
                                                        <th>UBUNTU Code Name</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="total_users" role="tabpanel"
                                aria-labelledby="os-release-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="h5 ">TOTAL USERS</span>
                                        <div class="table-responsive mt-3">
                                            <table id="total_users_logs_table" class="table table-stripped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="incidents" role="tabpanel"
                                aria-labelledby="incidents">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="h5 ">Incidents</span>
                                        <div class="table-responsive mt-3">
                                            <table id="incidents_logs_table" class="table table-stripped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Size</th>
                                                        <th>Used</th>
                                                        <th>Available</th>
                                                        <th>Used (%)</th>
                                                        <th>Created At</th> 
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                           </div>
                           <div class="tab-pane fade" id="services" role="tabpanel"
                                aria-labelledby="services">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="h5 ">Services</span>
                                        <div class="table-responsive mt-3">
                                            <table id="services_logs_table" class="table table-stripped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Size</th>
                                                        <th>Used</th>
                                                        <th>Available</th>
                                                        <th>Used (%)</th>
                                                        <th>Created At</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                       </div>
                                    </div>
                               </div>
                            </div>
                      </div>
      
                    </div>
<input type="hidden" class="user_permission_to_add_website" value={{ @$user_permission_to_add_website }}>

     <!-- to store no of servers in package and user servers count -->
<input type="hidden" class="user_website_added" value={{ $user_website_added }}>
<input type="hidden" class="no_of_website_allowed" value={{ $no_of_websites_allowed }}>
            <div class="modal fade" id="assignWebsiteModal">
            <div class="modal-dialog" style="max-width:1000px">
                <div class="modal-content">
                    <div class="modal-header">
                    <button class="btn btn-primary btn-sm float-right mb-2" id="showAddWebsiteFormBtn"> <i
                            class="fa fa-plus"></i> Add New Website</button>
                     <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                    <form id="addWebsiteForm" class="d-none">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="m-0">Choose <span class="text-danger">*</span></label>
                                <select name="protocol" id="protocol" class="form-control" style="min-height:45px;">
                                    <option value="" selected disabled>Choose</option>
                                    <option value="Http://">Http</option>
                                    <option value="Https://">Https</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="m-0">URL <span class="text-danger">*</span></label>
                                <input type="text" name="url" class="form-control" id="url"
                                    placeholder="Enter Url">
                            </div>
                            <div class="col-md-6">
                                <label class="m-0">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" id="title"
                                    placeholder="Enter Title">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="m-0">Email <span class="text-danger">*</span></label><small
                                    class="text-info float-right d-none"> (Emails should be comma seprated)</small>
                                <input type="text" name="emails" class="form-control" id="email"
                                    placeholder="Enter Email">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="m-0">Developer Email </label>
                                <input type="text" name="developer_email" class="form-control"
                                    id="developer_email" placeholder="Enter Developer Email">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="m-0">Owner Email </label>
                                <input type="text" name="owner_email" class="form-control" id="owner_email"
                                    placeholder="Enter Owner Email">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="m-0">Choose Server </label>
                                <select name="server_id" id="add_server" class="form-control" style="min-height:45px;">
                                    <option value="" selected disabled>Choose Server</option>
                                    @foreach ($all_servers as $server)
                                    <option value="{{ $server->id }}">{{ $server->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="m-0">Domain Expiry Date </label>
                                <input type="date" name="domain_expiry_date" class="form-control"  id="domain_expiry_date"
                                    placeholder="Select Domain Date">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="m-0">Domain Registrar </label>
                                <input type="text" name="domain_registrar" class="form-control" id="domain_registrar"
                                    placeholder="Enter Domain Registrar">
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-check mt-4">
                                    <input name="ssl" type="checkbox" class="form-check-input"
                                        id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">SSL Check</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="websiteSubmitBtn"
                            class="btn btn-primary pr-4 pl-4 mt-3 mb-4">Submit</button>
                    </form>
                    <h5 class="mb-2">All Added Websites</h5>
                     <div class="table-responsive user-added-website-table"></div>

                    </div>
                </div>
            </div>
        </div>
     @endsection
    @section('scripts')
            <script>
                $(document).ready(function() {

                    var server_id = "{{ $id }}";

                    if ($('.cpu-usage-tab').hasClass('active')) {
                        get_cpu_usage_logs();
                    }
                    if ($('.ram-usage-tab').hasClass('active')) {
                        if ($('.ram-memory-tab').hasClass('active')) {
                            get_ram_memory_usage_logs();
                        }
                        if ($('.ram-swap-tab').hasClass('active')) {
                            get_ram_swap_usage_logs();
                        }
                    }
                    if ($('.disk_usage-tab').hasClass('active')) {
                        get_disk_usage_logs();
                    }
                    if ($('.os-release-tab').hasClass('active')) {
                        get_os_release_logs();
                    }
                    if ($('.total-users-tab').hasClass('active')) {
                        get_total_users_logs();
                    } 
                    if ($('.incidents-tab').hasClass('active')) {
                        get_incidents_logs();
                    }
                    if ($('.services-tab').hasClass('active')) {
                        get_services_logs();
                    }

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

                    $(document).on('click', '.cpu-usage-tab', function(e) {
                        get_cpu_usage_logs();
                    });

                    $(document).on('click', '.ram-usage-tab', function(e) {
                        if ($('.ram-memory-tab').hasClass('active')) {
                            get_ram_memory_usage_logs();
                        }
                        if ($('.ram-swap-tab').hasClass('active')) {
                            get_ram_swap_usage_logs();
                        }
                    });

                    $(document).on('click', '.ram-memory-tab', function(e) {
                        get_ram_memory_usage_logs();
                    });

                    $(document).on('click', '.ram-swap-tab', function(e) {
                        get_ram_swap_usage_logs();
                    });

                    $(document).on('click', '.disk_usage-tab', function(e) {
                        get_disk_usage_logs();
                    });

                    $(document).on('click', '.os-release-tab', function(e) {
                        get_os_release_logs();
                    });

                    $(document).on('click', '.total-users-tab', function(e) {
                        get_total_users_logs();
                    });
                    $(document).on('click','.incidents-tab',function(e) {
                        get_incidents_logs();
                    });
                    $(document).on('click','.services-tab',function(e) {
                        get_services_logs();
                    });

                    function get_cpu_usage_logs() {
                        var for_type = "cpu_usage";
                        if (!($.fn.dataTable.isDataTable('#cpu_usage_logs_table'))) {
                            var table = $('#cpu_usage_logs_table').DataTable({
                                // "bAutoWidth": false,
                                serverSide: true,
                                processing: true,
                                searching: false,
                                ordering: true,
                                pageLength: {{ 25 }},
                                "processing": true,
                                'language': {
                                    'loadingRecords': '&nbsp;',
                                    'processing': 'Loading...'
                                },
                                scrollCollapse: true,
                                ajax: {
                                    url: "{{ url('server-logs-in-details') }}",
                                    data: {
                                        server_id: server_id,
                                        for_type: for_type
                                    },
                                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                                },
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false
                                    },
                                    {
                                        data: 'user',
                                        name: 'user'
                                    },
                                    {
                                        data: 'nice',
                                        name: 'nice'
                                    },
                                    {
                                        data: 'system',
                                        name: 'system'
                                    },
                                    {
                                        data: 'iowait',
                                        name: 'iowait'
                                    },
                                    {
                                        data: 'steal',
                                        name: 'steal'
                                    },
                                    {
                                        data: 'idle',
                                        name: 'idle'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                ],
                            });
                        } else {
                            $('#cpu_usage_logs_table').DataTable().ajax.reload();
                        }
                    }

                    function get_disk_usage_logs() {
                        var for_type = "disk_usage";
                        if (!($.fn.dataTable.isDataTable('#disk_usage_logs_table'))) {
                            var table = $('#disk_usage_logs_table').DataTable({
                                // "bAutoWidth": false,
                                serverSide: true,
                                processing: true,
                                searching: false,
                                ordering: true,
                                pageLength: {{ 25 }},
                                "processing": true,
                                'language': {
                                    'loadingRecords': '&nbsp;',
                                    // processing: '<img src={{ url('public/images/Spin-1s-200px.gif') }}/>'
                                    'processing': 'Loading...'
                                    
                                },
                                scrollCollapse: true,
                                ajax: {
                                    url: "{{ url('server-logs-in-details') }}",
                                    data: {
                                        server_id: server_id,
                                        for_type: for_type
                                    },
                                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                                },
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false
                                    },
                                    {
                                        data: 'size',
                                        name: 'size'
                                    },
                                    {
                                        data: 'used',
                                        name: 'used'
                                    },
                                    {
                                        data: 'available',
                                        name: 'available'
                                    },
                                    {
                                        data: 'used_percentage',
                                        name: 'used_percentage'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                ],
                            });
                        } else {
                            $('#disk_usage_logs_table').DataTable().ajax.reload();
                        }
                    }

                    function get_ram_memory_usage_logs() {
                        var for_type = "ram_usage";
                        var for_type_ram = "memory";
                        if (!($.fn.dataTable.isDataTable('#ram_memory_usage_logs_table'))) {
                            var table = $('#ram_memory_usage_logs_table').DataTable({
                                // "bAutoWidth": false,
                                serverSide: true,
                                processing: true,
                                searching: false,
                                ordering: true,
                                pageLength: {{ 25 }},
                                "processing": true,
                                'language': {
                                    'loadingRecords': '&nbsp;',
                                    'processing': 'Loading...'
                                },
                                scrollCollapse: true,
                                ajax: {
                                    url: "{{ url('server-logs-in-details') }}",
                                    data: {
                                        server_id: server_id,
                                        for_type: for_type,
                                        for_type_ram: for_type_ram
                                    },
                                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                                },
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false
                                    },
                                    {
                                        data: 'total',
                                        name: 'total'
                                    },
                                    {
                                        data: 'used',
                                        name: 'used'
                                    },
                                    {
                                        data: 'free',
                                        name: 'free'
                                    },
                                    {
                                        data: 'shared',
                                        name: 'shared'
                                    },
                                    {
                                        data: 'buff_cache',
                                        name: 'buff_cache'
                                    },
                                    {
                                        data: 'available',
                                        name: 'available'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                ],
                            });
                        } else {
                            $('#ram_memory_usage_logs_table').DataTable().ajax.reload();
                        }
                    }

                    function get_ram_swap_usage_logs() {
                        var for_type = "ram_usage";
                        var for_type_ram = "swap";
                        if (!($.fn.dataTable.isDataTable('#ram_swap_usage_logs_table'))) {
                            var table = $('#ram_swap_usage_logs_table').DataTable({
                                // "bAutoWidth": false,
                                serverSide: true,
                                processing: true,
                                searching: false,
                                ordering: true,
                                pageLength: {{ 25 }},
                                "processing": true,
                                'language': {
                                    'loadingRecords': '&nbsp;',
                                    'processing': 'Loading...'
                                },
                                scrollCollapse: true,
                                ajax: {
                                    url: "{{ url('server-logs-in-details') }}",
                                    data: {
                                        server_id: server_id,
                                        for_type: for_type,
                                        for_type_ram: for_type_ram
                                    },
                                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                                },
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false
                                    },
                                    {
                                        data: 'total',
                                        name: 'total'
                                    },
                                    {
                                        data: 'used',
                                        name: 'used'
                                    },
                                    {
                                        data: 'free',
                                        name: 'free'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                ],
                            });
                        } else {
                            $('#ram_swap_usage_logs_table').DataTable().ajax.reload();
                        }
                    }

                    function get_os_release_logs() {
                        var for_type = "os_release";
                        if (!($.fn.dataTable.isDataTable('#os_release_logs_table'))) {
                            var table = $('#os_release_logs_table').DataTable({
                                // "bAutoWidth": false,
                                serverSide: true,
                                processing: true,
                                searching: false,
                                ordering: true,
                                pageLength: {{ 25 }},
                                "processing": true,
                                'language': {
                                    'loadingRecords': '&nbsp;',
                                    'processing': 'Loading...'
                                },
                                scrollCollapse: true,
                                ajax: {
                                    url: "{{ url('server-logs-in-details') }}",
                                    data: {
                                        server_id: server_id,
                                        for_type: for_type
                                    },
                                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                                },
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false
                                    },
                                    {
                                        data: 'name',
                                        name: 'name'
                                    },
                                    {
                                        data: 'version',
                                        name: 'version'
                                    },
                                    {
                                        data: 'os_release_id',
                                        name: 'os_release_id'
                                    },
                                    {
                                        data: 'id_like',
                                        name: 'id_like'
                                    },
                                    {
                                        data: 'pretty_name',
                                        name: 'pretty_name'
                                    },
                                    {
                                        data: 'version_id',
                                        name: 'version_id'
                                    },
                                    {
                                        data: 'home_url',
                                        name: 'home_url'
                                    },
                                    {
                                        data: 'support_url',
                                        name: 'support_url'
                                    },
                                    {
                                        data: 'bug_report_url',
                                        name: 'bug_report_url'
                                    },
                                    {
                                        data: 'privacy_policy_url',
                                        name: 'privacy_policy_url'
                                    },
                                    {
                                        data: 'version_codename',
                                        name: 'version_codename'
                                    },
                                    {
                                        data: 'ubuntu_codename',
                                        name: 'ubuntu_codename'
                                    },
                                ],
                            });
                        } else {
                            $('#os_release_logs_table').DataTable().ajax.reload();
                        }
                    }

                    function get_total_users_logs() {
                        var for_type = "total_user";
                        if (!($.fn.dataTable.isDataTable('#total_users_logs_table'))) {
                            var table = $('#total_users_logs_table').DataTable({
                                // "bAutoWidth": false,
                                serverSide: true,
                                processing: true,
                                searching: false,
                                ordering: true,
                                pageLength: {{ 25 }},
                                "processing": true,
                                'language': {
                                    'loadingRecords': '&nbsp;',
                                    'processing': 'Loading...'
                                },
                                scrollCollapse: true,
                                ajax: {
                                    url: "{{ url('server-logs-in-details') }}",
                                    data: {
                                        server_id: server_id,
                                        for_type: for_type
                                    },
                                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                                },
                                columns: [{
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex',
                                        orderable: false,
                                        searchable: false
                                    },
                                    {
                                        data: 'name',
                                        name: 'name'
                                    },
                                ],
                            });
                        } else {
                            $('#total_users_logs_table').DataTable().ajax.reload();
                        }
                    }
                    function get_incidents_logs(){
                      
                      var for_type = "incident";
                      if (!($.fn.dataTable.isDataTable('#incidents_logs_table'))){
                          var table = $('#incidents_logs_table').DataTable({
                              serverSide: true,
                              processing: true,
                              searching: false,
                              ordering: true,
                              pageLength: {{ 25 }},
                              "processing": true,
                              'language': {
                                  'loadingRecords': '&nbsp;',
                                  'processing': 'Loading...'
                              },
                              scrollCollapse: true,
                              ajax: {
                                  url: "{{ url('server-logs-in-details') }}",
                                  data: {
                                      server_id: server_id,
                                      for_type: for_type
                                  },
                                },
                              columns: [{
                                      data: 'DT_RowIndex',
                                      name: 'DT_RowIndex',
                                      orderable: false,
                                      searchable: false
                                  },
                                  {
                                      data: 'name',
                                      name: 'name'
                                  },

                                  {
                                        data: 'used',
                                        name: 'used'
                                    },
                                    {
                                        data: 'available',
                                        name: 'available'
                                    },
                                    {
                                        data: 'used_percentage',
                                        name: 'used_percentage'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                              ],

                          });
                        }
                      
                          else {
                              $('#incidents_logs_table').DataTable().ajax.reload();

                          }
                  }
                  function get_services_logs(){
                      
                      var for_type = "service";
                      if (!($.fn.dataTable.isDataTable('#services_logs_table'))){
                          var table = $('#services_logs_table').DataTable({
                              serverSide: true,
                              processing: true,
                              searching: false,
                              ordering: true,
                              pageLength: {{ 25 }},
                              "processing": true,
                              'language': {
                                  'loadingRecords': '&nbsp;',
                                  'processing': 'Loading...'
                              },
                              scrollCollapse: true,
                              ajax: {
                                  url: "{{ url('server-logs-in-details') }}",
                                  data: {
                                      server_id: server_id,
                                      for_type: for_type
                                  },
                                },
                              columns: [{
                                      data: 'DT_RowIndex',
                                      name: 'DT_RowIndex',
                                      orderable: false,
                                      searchable: false
                                  },
                                  {
                                      data: 'name',
                                      name: 'name'
                                  },

                                  {
                                        data: 'used',
                                        name: 'used'
                                    },
                                    {
                                        data: 'available',
                                        name: 'available'
                                    },
                                    {
                                        data: 'used_percentage',
                                        name: 'used_percentage'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                              ],

                          });
                        }
                      
                          else {
                              $('#services_logs_table').DataTable().ajax.reload();

                          }
                  }

                });
             $('#assignWebsiteBtn').on('click', function() {
                @if (Auth::user()->email_verified_at == null)
                    toastr.info('Info!', 'Please Verify your account first', {
                        "positionClass": "toast-bottom-right"
                    });
                    return;
                @endif
              
                var user_id = $(this).val();
                $.ajax({
                    url: "{{ url('user-added-websites') }}",
                    method: 'get',
                    data: {
                        user_id: user_id
                    },
                    success: function(data) {
                        $('#assignWebsiteModal').modal('show');
                        $('.user-added-website-table').html(data.table_html);
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong', {
                            "positionClass": "toast-bottom-right"
                        });
                    },
                })
            });
            $("#showAddWebsiteFormBtn").click(function() {
              // check if user permission to add website
              @if(Auth::user()->role_id == 2)
                var user_permission_to_add_website = $('.user_permission_to_add_website').val();
                if(user_permission_to_add_website == 0 ){
                    toastr.info('Permission Failed!', 'User does not have the permission to add the website Sorry !', {
                        "positionClass": "toast-bottom-right"
                    });
                    return;
                }
              @endif
              // check if user exceeding limit
                var no_of_websites_allowed = parseInt($('.no_of_website_allowed').val());
                var user_website_added = parseInt($('.user_website_added').val());
                if(no_of_websites_allowed <= user_website_added){
                    toastr.info('Info!', 'Adding website limit reached. Please upgrade your package', {
                        "positionClass": "toast-bottom-right"
                    });
                    return;
                }

                // check if the element has the 'd-none' class
                if ($("#addWebsiteForm").hasClass("d-none")) {
          
                // if it does, remove the class
                $("#addWebsiteForm").removeClass("d-none");
                } else {
                // if it doesn't, add the class
                $("#addWebsiteForm").addClass("d-none");
                }
            });
            $(".close").click(function(){
            $("#addWebsiteForm").addClass("d-none");
            });
            $('#addWebsiteForm').on('submit', function(e) {
                e.preventDefault();
                var form = $('#addWebsiteForm').serialize();

                $.ajax({
                    url: '{{ url('admin/add-website') }}',
                    method: 'post',
                    data: form,
                    beforeSend: function() {
                        $('#websiteSubmitBtn').prop('disabled', true);
                        $('#websiteSubmitBtn').html('Please wait...');
                    },
                    success: function(data) {
                        if (data.success == true) {
                           
                            toastr.success('Success!',
                                'Website added successfully and being monitored', {
                                    "positionClass": "toast-bottom-right"
                                });
                            $('#addWebsiteForm')[0].reset();
                            $('#assignWebsiteBtn').trigger('click');
                            $('#websiteSubmitBtn').prop('disabled', false);
                            $('#websiteSubmitBtn').html('Submit');
                            $('.no_of_website_allowed').val(data.no_of_websites_allowed);
                            $('.user_website_added').val(data.user_website_added);
                        } else if (data.success == false) {
                            toastr.error('Error!', 'Something went wrong', {
                                "positionClass": "toast-bottom-right"
                            });
                            $('#websiteSubmitBtn').prop('disabled', false);
                            $('#websiteSubmitBtn').html('Submit');
                        }

                    },
                    error: function(request, status, error) {
                        // toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                        $('#websiteSubmitBtn').prop('disabled', false);
                        $('#websiteSubmitBtn').html('Submit');
                        $('.form-control').removeClass('is-invalid');
                        $('.form-control').next().remove();
                        json = $.parseJSON(request.responseText);
                        $.each(json.errors, function(key, value) {
                            $('input[name="' + key + '"]').after(
                                '<span class="invalid-feedback" role="alert"><strong>' +
                                value +
                                '</strong>');
                            $('input[name="' + key + '"]').addClass('is-invalid');
                        });
                    },
                })
            });
         $(document).on('click', '.server_website_delete', function(e) {
                var id = $(this).val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to Delete the Website !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, do it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:"{{ url('server-website/delete') }}",
                            method: 'post',
                            data: {
                                id: id,
                            },
                            success: function(data) {
                                toastr.success('Success!', 'Website Deleted successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('.no_of_website_allowed').val(data.no_of_websites_allowed);
                                $('.user_website_added').val(data.user_website_added);
                              $('#assignWebsiteBtn').trigger('click');
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
            $(document).on('click', '.btn-assign-unassign-website', function(e) {
                    var id = $(this).val();
                    var  server_id  = "{{$id}}"; 
                    $.ajax({
                        url: "{{ url('website/assign-status-change') }}",
                        method: 'post',
                        data: {
                            id: id,
                            server_id: server_id
                        },
                        success: function(data) {
                          if(data.success == true){
                            toastr.success('Success!', data.message, {
                                    "positionClass": "toast-bottom-right"
                                });
                             $('#assignWebsiteBtn').trigger('click');
                            }else if(data.success == false) {
                            toastr.error('Error!', 'Something went wrong', {
                                "positionClass": "toast-bottom-right"
                            });
                            }
                           },
                        })
                    });
            </script>
           @endsection
