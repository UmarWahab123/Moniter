@extends('layouts.app')
@section('content')
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-12 mt-5">
                        <table class="table table-sm table-borderless w-50 custom-table">
                            <thead>
                                <th class="text-uppercase">
                                    <h2> {{ $server != null ? $server->name : '' }} </h2>
                                </th>
                            </thead>
                        </table>

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
                                    processing: '<img src={{ url('public/images/Spin-1s-200px.gif') }}/>'
                                    // 'processing': 'Loading...'
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

                });
            </script>
        @endsection
