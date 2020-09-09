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
                            <span class="h4">Users</span>
                            <button class="btn btn-primary btn-sm float-right" id="addUserBtn"> <i class="fa fa-plus"></i> Add User</button>
                            <div class="table-responsive mt-3">
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

        <div class="modal fade" id="editUserModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm">
                            @csrf
                                <input type="text" name="id" class="form-control d-none" id="editID">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input type="text" name="name" class="form-control" id="editName" aria-describedby="" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" readonly name="email" class="form-control" id="editEmail" aria-describedby="emailHelp" placeholder="Enter Email">
                            </div>
                           
                            <div class="form-check d-none">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>
                            <button type="submit" id="userEditSubmitBtn" class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
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
                searching: true,
                ordering: true,
                "processing": true,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },

                scrollCollapse: true,
                ajax: "{{ route('users') }}",
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

            $(document).on('click','.edit-user', function() {
                $('#editUserModal').modal('show');
                $('#editID').val($(this).val());
                $('#editName').val($(this).parent().prev().prev().prev().prev().html());
                $('#editEmail').val($(this).parent().prev().prev().prev().html());
            });
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                var form = $('#editUserForm').serialize();
                $.ajax({
                    url: '{{ route('edit-user') }}', 
                    method: 'post',
                    data: form,
                    beforeSend: function() {
                        $('#userEditSubmitBtn').prop('disabled', true);
                        $('#userEditSubmitBtn').html('Please wait...');
                    },
                    success: function(data) {
                        $('#editUserModal').modal('hide');
                        $('#editUserForm')[0].reset();
                        toastr.success('Success!', 'User updated successfully' ,{"positionClass": "toast-bottom-right"});
                        $('#usersDataTable').DataTable().ajax.reload();
                        $('#userEditSubmitBtn').prop('disabled', false);
                        $('#userEditSubmitBtn').html('Update');
                    },
                    error: function() {
                        toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                        $('#userEditSubmitBtn').prop('disabled', false);
                        $('#userEditSubmitBtn').html('Update');
                    },
                })
            });
            $(document).on('click','#addUserBtn', function() {
                $('#addUserModal').modal('show');
            
            });
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                var form = $('#addUserForm').serialize();

                $.ajax({
                    url: '{{ url('admin/add-user') }}', 
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
            $(document).on('click','.user-status', function(e) {

                var id = $(this).val();
                var status = $(this).data('status');
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
                    url: '{{ route('user-status') }}', 
                    method: 'get',
                    data: {id:id,status:status},
                    success: function(data) {
                        toastr.success('Success!', 'User suspended successfully' ,{"positionClass": "toast-bottom-right"});
                        $('#usersDataTable').DataTable().ajax.reload();
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
