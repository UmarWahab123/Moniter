@extends('layouts.app')
@section('content')

<div class="page-container">
    <!-- sidebar menu area start -->
    @if(auth()->user()->userRole->role_id == 1)
        @include('admin.assets.sidebar')
    @else
        @include('user.assets.sidebar')
    @endif
    <!-- sidebar menu area end -->
    
    <!-- main content area start -->
    <div class="main-content">
        <!-- page title area start -->
        @if(auth()->user()->userRole->role_id == 1)
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
                            <button class="btn btn-primary btn-sm float-right mb-2" id="addServer"> <i class="fa fa-plus"></i> Add Server</button>
                            <div class="table-responsive">

                                <table id="servers_table" class="table table-stripped text-center">
                                    <thead>
                                        <tr>
                                            <th>Name </th>
                                            <th>IP Address</th>
                                            <th>OS</th>
                                            <th>Key</th>
                                            <th>Added By</th>
                                            <th>File</th>
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
            @if(auth()->user()->userRole->role_id == 1)
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
                                    <label class="m-0" >Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0">IP Address <span class="text-danger">*</span></label>
                                    <input type="text" name="ip_address" class="form-control" id="ip_address" placeholder="Enter IP Address">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label class="m-0">Operating System <span class="text-danger">*</span></label>
                                    <select name="operating_system" id="operating_system" class="form-control">
                                        <option value="" selected="">Choose OS</option>
                                        <option value="windows">Windows</option>
                                        <option value="linux">Linux</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" id="ServerSubmitBtn" class="btn btn-primary mt-4 pr-4 pl-4 pull-right">Submit</button>
                        </form>
                    </div >
                   
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="editWebsiteModal">
            <div class="modal-dialog" style="max-width:800px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Website</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="editWebsiteForm">
                            @csrf
                            <input type="hidden" name="id" id="editId">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="m-0 mt-2" >Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" id="editTitle" aria-describedby="emailHelp" placeholder="Enter Title">
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0 mt-2" >Email <span class="text-danger">*</span></label>
                                    <input type="text" name="emails" class="form-control" id="editEmails" aria-describedby="emailHelp" placeholder="Enter Title">
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0 mt-2">Developer Email </label>
                                    <input type="text" name="developer_email" class="form-control" id="edit_developer_email" placeholder="Enter Developer Email">
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0 mt-2">Owner Email </label>
                                    <input type="text" name="owner_email" class="form-control" id="edit_owner_email" placeholder="Enter Owner Email">
                                </div>

                                <div class="col-md-6 mt-2">
                                <div class="form-check ">
                                    <input name="ssl" type="checkbox" class="form-check-input" id="editSsl">
                                    <label class="form-check-label" for="editSsl">Ssl Check</label>
                                </div>
                                </div>
                                
                            </div>
                            <button type="submit" id="editServerSubmitBtn" class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                        </form>
                    </div >
                   
                </div>
            </div>
        </div>
        
        @if(auth()->user()->userRole->role_id == 1)
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
                    url:"{{ url('get-servers') }}",
                    {{-- beforeSend:function()
                    {
                    },
                    success:function()
                    {
                    } --}}
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'ip_address', name: 'ip_address' },
                    { data: 'os', name: 'os' },
                    { data: 'key', name: 'key' },
                    { data: 'added_by', name: 'added_by' },
                    { data: 'file', name: 'file' },
                ],

            });
            
            $(document).on('keyup', '.form-control', function(){
                $(this).removeClass('is-invalid');
                $(this).next().remove();
            });

            $(document).on('change', '.form-control', function(){
                $(this).removeClass('is-invalid');
                $(this).next().remove();
            });
            
            $('#addServer').on('click', function() {
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
                        if(data.success==true)
                        {
                            $('#addServerModal').modal('hide');
                            $('#addServerForm')[0].reset();
                            toastr.success('Success!', 'Server added successfully' ,{"positionClass": "toast-bottom-right"});
                            $('#servers_table').DataTable().ajax.reload();
                            $('#ServerSubmitBtn').prop('disabled', false);
                            $('#ServerSubmitBtn').html('Submit');
                        }
                        else if(data.success==false)
                        {
                            toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
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
                        $.each(json.errors, function(key, value){
                            $('input[name="'+key+'"]').after('<span class="invalid-feedback" role="alert"><strong>'+value+'</strong>');
                            $('input[name="'+key+'"]').addClass('is-invalid');
                            $('select[name="'+key+'"]').after('<span class="invalid-feedback" role="alert"><strong>'+value+'</strong>');
                            $('select[name="'+key+'"]').addClass('is-invalid');
                        });
                    },
                })
            });

            $(document).on('click','.delete-site', function(e) {

                var id = $(this).val();
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
                }).then((result) => {
                if (result.value) {
                   $.ajax({
                    url: '{{ url('admin/delete-website') }}', 
                    method: 'get',
                    data: {id:id},
                    success: function(data) {
                        toastr.success('Success!', 'Website deleted successfully' ,{"positionClass": "toast-bottom-right"});
                        $('#websitesDataTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                    },
                });
                }
                })
                
            });

            $(document).on('click','.edit-site', function(e) {
                var website_id=$(this).val();
                $.ajax({
                    url: '{{ url('admin/edit-website') }}', 
                    method: 'get',
                    data: {website_id:website_id},
                    success: function(data) {
                        $('#editWebsiteModal').modal('show');
                        $('#editTitle').val(data.data['title']);
                        $('#editEmails').val(data.data['emails']);
                        $('#edit_developer_email').val(data.data['developer_email']);
                        $('#edit_owner_email').val(data.data['owner_email']);
                        $('#editId').val(website_id);
                        if(data.data['ssl']==1)
                        {
                            $('#editSsl').prop('checked',true);
                        }
                        else
                        {
                            $('#editSsl').prop('checked',false);
                        }
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                    },
                })
               
               
            });

            $(document).on('click','#editServerSubmitBtn',function(e){
                e.preventDefault();
                var formData=$('#editWebsiteForm').serialize();
                $.ajax({
                    url: '{{ url('admin/edit-website') }}', 
                    method: 'post',
                    data: formData,
                    success: function(data) {
                        $('#editWebsiteModal').modal('hide');
                        toastr.success('Success!', 'Website updated successfully' ,{"positionClass": "toast-bottom-right"});
                        $('#websitesDataTable').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                    },
                })
            });

            $(document).ready(function(){
                reloadDatatable();                
            });

            function reloadDatatable()
            {
                setTimeout(function(){           
                    $('#websitesDataTable').DataTable().ajax.reload();
                    reloadDatatable();
                }, 65000);
            }

            $(document).on('click','.feature', function(e) {

                var id = $(this).val();
                var status=$(this).data('status');
                Swal.fire({
                title: 'Are you sure?',
                text: "You want to feature this website",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!'
                }).then((result) => {
                if (result.value) {
                   $.ajax({
                    url: '{{ url('admin/feature') }}', 
                    method: 'get',
                    data: {id:id,status:status},
                    success: function(data) {
                    if(data.limit==0)
                    {
                        toastr.success('Success!', 'Website featured successfully' ,{"positionClass": "toast-bottom-right"});
                    }
                    else if(data.limit==1)
                    {
                        toastr.info('Info!', 'Featured limit reached' ,{"positionClass": "toast-bottom-right"});
                    }
                    else if(data.limit==2)
                    {
                        toastr.success('Success!', 'Website unfeatured successfully' ,{"positionClass": "toast-bottom-right"});
                    }
                        
                        $('#websitesDataTable').DataTable().ajax.reload();
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