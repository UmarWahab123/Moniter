@extends('layouts.app')
@section('content')
    <div class="page-container">
        <!-- sidebar menu area start -->
        @if (Auth::user()->role_id == 1)
            @include('admin.assets.sidebar')
        @else
            @include('user.assets.sidebar')
        @endif
        <!-- sidebar menu area end -->

        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            @if (Auth::user()->role_id == 1)
                @include('admin.assets.title_area')
            @else
                @include('user.assets.title_area')
            @endif

            <!-- page title area end -->
            <div class="main-content-inner">
                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary btn-sm float-right mb-2" id="addServer"> <i
                                        class="fa fa-plus"></i> Add Server</button>
                                <div class="table-responsive">

                                    <table id="servers_table" class="table table-stripped text-center">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>IP Address</th>
                                                <th>OS</th>
                                                <!-- <th>Key</th> -->
                                                <th>Added By</th>
                                                <th>File</th>
                                                <th>Server Logs</th>
                                                <th>Binded Websites</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- main content area end -->
                <!-- footer area start-->
                @if (Auth::user()->role_id == 1)
                    @include('admin.assets.footer')
                @else
                    @include('user.assets.footer')
                @endif

                <!-- footer area end-->
            </div>

            <div class="modal fade" id="addServerModal">
                <div class="modal-dialog" style="max-width:800px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Server</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="addServerForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-0">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="m-0">IP Address <span class="text-danger">*</span></label>
                                        <input type="text" name="ip_address" class="form-control" id="ip_address"
                                            placeholder="Enter IP Address">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="m-0">Operating System <span class="text-danger">*</span></label>
                                        <select name="operating_system" id="operating_system" class="form-control">
                                            <option value="" selected="">Choose OS</option>
                                            <!-- <option value="windows">Windows</option> -->
                                            <option value="linux">Linux</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" id="ServerSubmitBtn"
                                    class="btn btn-primary mt-4 pr-4 pl-4 pull-right">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="editServerModal">
                <div class="modal-dialog" style="max-width:800px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Server</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="editServerForm">
                                @csrf
                                <input type="hidden" name="id" id="editId">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-0">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="edit_name"
                                            placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="m-0">IP Address <span class="text-danger">*</span></label>
                                        <input type="text" name="ip_address" class="form-control" id="edit_ip_address"
                                            placeholder="Enter IP Address" disabled="true">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label class="m-0">Operating System <span class="text-danger">*</span></label>
                                        <select name="operating_system" id="edit_operating_system" class="form-control">
                                            <option value="" selected="">Choose OS</option>
                                            <!-- <option value="windows">Windows</option> -->
                                            <option value="linux">Linux</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" id="EditServerSubmitBtn"
                                    class="btn btn-primary mt-4 pr-4 pl-4 pull-right">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="binded_websites">
                <div class="modal-dialog" style="max-width:800px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Binded Websites</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="websites">Select Webiste to bind to this Server</label>
                                    <select name="websites" id="binded_websites_select" class="form-control" style="min-height: 45px;">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive mt-2 w-100">
                                        <table id="binded_websites_table" class="table table-stripped text-center">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>URL</th>
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

            @if (Auth::user()->role_id == 1)
                @include('admin.assets.javascript')
            @else
                @include('user.assets.javascript')
            @endif

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

                var table = $('#servers_table').DataTable({
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
                        url: "{{ url('get-servers') }}",
                        {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'ip_address',
                            name: 'ip_address'
                        },
                        {
                            data: 'os',
                            name: 'os'
                        },
                        // { data: 'key', name: 'key' },
                        {
                            data: 'added_by',
                            name: 'added_by'
                        },
                        {
                            data: 'file',
                            name: 'file'
                        },
                        {
                            data: 'server_logs',
                            name: 'server_logs'
                        },
                        {
                            data: 'binded_websites',
                            name: 'binded_websites'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],

                });

                $(document).on('keyup', '.form-control', function() {
                    $(this).removeClass('is-invalid');
                    $(this).next().remove();
                });

                $(document).on('change', '.form-control', function() {
                    $(this).removeClass('is-invalid');
                    $(this).next().remove();
                });

                $('#addServer').on('click', function() {
                    @if (Auth::user()->email_verified_at == null)
                        toastr.info('Info!', 'Please Verify your account first', {
                            "positionClass": "toast-bottom-right"
                        });
                        return;
                    @endif
                    $('#addServerModal').modal('show');
                });

                $('#addServerForm').on('submit', function(e) {
                    e.preventDefault();
                    var form = $('#addServerForm').serialize();

                    $.ajax({
                        url: '{{ url('add-server') }}',
                        method: 'post',
                        data: form,
                        beforeSend: function() {
                            $('#ServerSubmitBtn').prop('disabled', true);
                            $('#ServerSubmitBtn').html('Please wait...');
                        },
                        success: function(data) {
                            if (data.success == true) {
                                $('#addServerModal').modal('hide');
                                $('#addServerForm')[0].reset();
                                toastr.success('Success!', 'Server added successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('#servers_table').DataTable().ajax.reload();
                                $('#ServerSubmitBtn').prop('disabled', false);
                                $('#ServerSubmitBtn').html('Submit');
                            } else if (data.success == false) {
                                toastr.error('Error!', 'Something went wrong', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('#ServerSubmitBtn').prop('disabled', false);
                                $('#ServerSubmitBtn').html('Submit');
                            }
                        },
                        error: function(request, status, error) {
                            $('#ServerSubmitBtn').prop('disabled', false);
                            $('#ServerSubmitBtn').html('Submit');
                            $('.form-control').removeClass('is-invalid');
                            $('.form-control').next().remove();
                            json = $.parseJSON(request.responseText);
                            $.each(json.errors, function(key, value) {
                                $('input[name="' + key + '"]').after(
                                    '<span class="invalid-feedback" role="alert"><strong>' + value +
                                    '</strong>');
                                $('input[name="' + key + '"]').addClass('is-invalid');
                                $('select[name="' + key + '"]').after(
                                    '<span class="invalid-feedback" role="alert"><strong>' + value +
                                    '</strong>');
                                $('select[name="' + key + '"]').addClass('is-invalid');
                            });
                        },
                    })
                });

                $(document).on('click', '.btn-delete', function(e) {

                    var id = $(this).val();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to delete this? Websites binded with this server will also be deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, do it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('servers.destroy') }}",
                                method: 'post',
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    toastr.success('Success!', 'Server deleted successfully', {
                                        "positionClass": "toast-bottom-right"
                                    });
                                    $('#servers_table').DataTable().ajax.reload();
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

                $(document).on('click', '.btn-edit', function(e) {
                    var server_id = $(this).val();
                    $.ajax({
                        url: "{{ route('servers.edit') }}",
                        method: 'get',
                        data: {
                            server_id: server_id
                        },
                        success: function(data) {
                            $('#editServerModal').modal('show');
                            $('#edit_name').val(data.data['name']);
                            $('#edit_ip_address').val(data.data['ip_address']);
                            $('#edit_operating_system').val(data.data['os']);
                            $('#editId').val(server_id);
                        },
                        error: function() {
                            toastr.error('Error!', 'Something went wrong', {
                                "positionClass": "toast-bottom-right"
                            });
                        },
                    })


                });

                $(document).on('submit', '#editServerForm', function(e) {
                    e.preventDefault();
                    var formData = $('#editServerForm').serialize();
                    $.ajax({
                        url: "{{ route('servers.update') }}",
                        method: 'post',
                        data: formData,
                        success: function(data) {
                            $('#editServerModal').modal('hide');
                            toastr.success('Success!', 'Server updated successfully', {
                                "positionClass": "toast-bottom-right"
                            });
                            $('#servers_table').DataTable().ajax.reload();
                        },
                        error: function() {
                            toastr.error('Error!', 'Something went wrong', {
                                "positionClass": "toast-bottom-right"
                            });
                        },
                    })
                });

                $(document).ready(function() {
                    reloadDatatable();
                });

                function reloadDatatable() {
                    setTimeout(function() {
                        $('#servers_table').DataTable().ajax.reload();
                        reloadDatatable();
                    }, 65000);
                }
                let server_id;
                $(document).on('click', '.btn_binded_websites', function(e) {
                    server_id = $(this).val();
                    $.ajax({
                        url: "{{ route('servers.binded-websites') }}",
                        method: 'post',
                        data: {server_id: server_id},
                        success: function(data) {
                            if (data.success) {
                                $('#binded_websites').modal('show');
                                server_id = data.server_id;
                                $('#binded_websites_table').DataTable().ajax.reload();
                                $('#binded_websites_select').html("");
                                $('#binded_websites_select').append(data.html);
                            }
                        },
                        error: function() {
                            toastr.error('Error!', 'Something went wrong', {
                                "positionClass": "toast-bottom-right"
                            });
                        },
                    })
                });
                $('#binded_websites_table').DataTable({
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
                        url: "{{ route('get-binded-websites') }}",
                        data: function(data){data.id = server_id},
                    },
                    columns: [
                        {
                            data: 'title',
                            title: 'title'
                        },
                        {
                            data: 'url',
                            name: 'url'
                        }
                    ],

                });

                $(document).on('change','#binded_websites_select', function(e) {
                    let website_id = $(this).val();
                    $.ajax({
                        url: '{{ route("save-binded-websites") }}',
                        method: 'post',
                        data: {website_id:website_id, server_id:server_id},
                        success: function(data) {
                            if (data.success){
                                toastr.success('Success!', 'Wesite added to Specified server Successfully', {
                                    "positionClass": "toast-bottom-right"
                                });
                                $('#binded_websites_table').DataTable().ajax.reload();
                            }
                            else {
                                toastr.info('Info!', 'Website already added to this Server', {
                                    "positionClass": "toast-bottom-right"
                                });
                            }
                        }
                    });
                });
                // $(document).on('click','.feature', function(e) {

                //     var id = $(this).val();
                //     var status=$(this).data('status');
                //     Swal.fire({
                //     title: 'Are you sure?',
                //     text: "You want to feature this website",
                //     icon: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Yes, do it!'
                //     }).then((result) => {
                //     if (result.value) {
                //        $.ajax({
                //         url: '{{ url('admin/feature') }}',
                //         method: 'get',
                //         data: {id:id,status:status},
                //         success: function(data) {
                //         if(data.limit==0)
                //         {
                //             toastr.success('Success!', 'Website featured successfully' ,{"positionClass": "toast-bottom-right"});
                //         }
                //         else if(data.limit==1)
                //         {
                //             toastr.info('Info!', 'Featured limit reached' ,{"positionClass": "toast-bottom-right"});
                //         }
                //         else if(data.limit==2)
                //         {
                //             toastr.success('Success!', 'Website unfeatured successfully' ,{"positionClass": "toast-bottom-right"});
                //         }

                //             $('#websitesDataTable').DataTable().ajax.reload();
                //         },
                //         error: function() {
                //             toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                //         },
                //     });
                //     }
                //     })

                // });
            </script>
        @endsection
