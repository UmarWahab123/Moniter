@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
     <input type="hidden" class="no_of_users_added" value={{ $no_of_users_added }}>
    <input type="hidden" class="no_of_users_allowed" value={{ $no_of_users_allowed }}>
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <span class="h4">Users</span>
                    <button class="btn btn-primary btn-sm float-right" id="addUserBtn"> <i
                            class="fa fa-plus"></i> Add User</button>
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
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1"
                            aria-describedby="" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                            aria-describedby="emailHelp" placeholder="Enter Email">
                    </div>
                    <div class="row">
                    <!-- <input type="text" name="user_id" class="form-control d-none" id="user_id"> -->
                    <div class="col-md-12">
                    <label class="m-0">Assign Permissions<span class="text-danger">*</span></label>
                    <select name="permission_id[]" id="selected_permission_id" class="form-control mt-1" multiple>
                      
                    </select>
                    </div>
                    </div>
                    <div class="form-check d-none">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" id="userSubmitBtn"
                        class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>
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
                        <input type="text" name="name" class="form-control" id="editName"
                            aria-describedby="" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" readonly name="email" class="form-control" id="editEmail"
                            aria-describedby="emailHelp" placeholder="Enter Email">
                    </div>

                    <div class="form-check d-none">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" id="userEditSubmitBtn"
                        class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="managePermissionModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Manage Permissions</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">Already Assigned Permissions</h6>
                <div class="table-responsive assigned-permission-table"></div>

                <h6 class="mb-3 mt-4">Assign More Permissions</h6>
                <form id="assignPermissionForm">
                    @csrf
                    <div class="row">
                    <input type="text" name="user_id" class="form-control d-none" id="user_id">
                    <div class="col-md-12">
                    <label class="m-0">Assign Permissions<span class="text-danger">*</span></label>
                    <select name="permission_id" id="permission_id" class="form-control mt-1">
                        <option value="" selected="">Please Select Permissions</option>
                        <option value=""></option>
                    </select>
                    </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="assignPermisssion" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
  
    @endsection
    @section('scripts')
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var table = $('#usersDataTable').DataTable({
        // "bAutoWidth": false,
        serverSide: true,
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
    $(document).on('click', '.manage_permission', function(e) {
        var id = $(this).val();
        $.ajax({
            url: "{{ url('admin/permission/permission-setting') }}",
            method: 'get',
            data: {
                id: id
            },
            success: function(data) {
                $('#managePermissionModal').modal('show');
                $('#permission_id').html(data.response);
                $('#user_id').val(id);
                $('.assigned-permission-table').html(data.table_html);
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })


    });
     $('#assignPermissionForm').on('submit', function(e) {
        e.preventDefault();
        var form = $('#assignPermissionForm').serialize();
        $.ajax({
            url: '{{ url('admin/store-assign-permission') }}',
            method: 'post',
            data: form,
            beforeSend: function() {
                $('#assignPermisssion').prop('disabled', true);
                $('#assignPermisssion').html('Please wait...');
            },
            success: function(data) {
                
                if (data.success == true) {
                    $('#managePermissionModal').modal('hide');
                    $('#assignPermissionForm')[0].reset();
                    toastr.success('Success!', 'Permissions Assigned Successfully !' , {
                        "positionClass": "toast-bottom-right"
                    });
                    // $('#feature-table').DataTable().ajax.reload();
                    $('#assignPermisssion').prop('disabled', false);
                    $('#assignPermisssion').html('Submit');
                }else if(data.success == false){
                    toastr.error('Error!', 'Something went wrong', {
                        "positionClass": "toast-bottom-right"
                    });
                    $('#assignPermisssion').prop('disabled', false);
                    $('#assignPermisssion').html('Submit');
                }
            },
        })
    });
    $(document).on('click', '.assigned_permision_delete', function(e) {
        var id = $(this).val();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Delete the selected Permission !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url:"{{ url('admin/userpermission/delete') }}",
                    method: 'post',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        toastr.success('Success!', 'Permission Deleted successfully !', {
                            "positionClass": "toast-bottom-right"
                        });
                        $( ".manage_permission" ).trigger("click");
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
    $(document).on('click', '.edit-user', function() {
        @if (Auth::user()->email_verified_at == null)
            toastr.info('Info!', 'Please Verify your account first', {
                "positionClass": "toast-bottom-right"
            });
            return;
        @endif
        $('#editUserModal').modal('show');
        $('#editID').val($(this).val());
        $('#editName').val($(this).parent().prev().prev().prev().prev().text().trim());
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
                toastr.success('Success!', 'User updated successfully', {
                    "positionClass": "toast-bottom-right"
                });
                $('#usersDataTable').DataTable().ajax.reload();
                $('#userEditSubmitBtn').prop('disabled', false);
                $('#userEditSubmitBtn').html('Update');
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
                $('#userEditSubmitBtn').prop('disabled', false);
                $('#userEditSubmitBtn').html('Update');
            },
        })
    });
    $(document).on('click', '#addUserBtn', function() {
        @if (Auth::user()->email_verified_at == null)
            toastr.info('Info!', 'Please Verify your account first', {
                "positionClass": "toast-bottom-right"
            });
            return;
        @endif
         // check if user exceeding limit
        var no_of_users_allowed = parseInt($('.no_of_users_allowed').val());
        var no_of_users_added = parseInt($('.no_of_users_added').val());
        if(no_of_users_allowed <= no_of_users_added){
        toastr.info('Info!', 'Adding Users limit reached. Please upgrade your package', {
            "positionClass": "toast-bottom-right"
        });
        return;
        }
        $.ajax({
            url: "{{ url('admin/permission/get-permissions') }}",
            method: 'get',
            success: function(data) {
                $('#addUserModal').modal('show');
                $('#selected_permission_id').html(data.response);
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })

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
                if(data.success==true){
                toastr.success('Success!', 'User added successfully', {
                "positionClass": "toast-bottom-right"
                });
                $('.no_of_users_allowed').val(data.no_of_users_allowed);
                $('.no_of_users_added').val(data.no_of_users_added);
                $('#addUserModal').modal('hide');
                $('#addUserForm')[0].reset();
                $('#usersDataTable').DataTable().ajax.reload();
                $('#userSubmitBtn').prop('disabled', false);
                $('#userSubmitBtn').html('Submit');
                }
             
            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
                $('#userSubmitBtn').prop('disabled', false);
                $('#userSubmitBtn').html('Submit');
            },
        })
    });
    $(document).on('click', '.user-status', function(e) {
        @if (Auth::user()->email_verified_at == null)
            toastr.info('Info!', 'Please Verify your account first', {
                "positionClass": "toast-bottom-right"
            });
            return;
        @endif
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
                    data: {
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        toastr.success('Success!', 'User suspended successfully', {
                            "positionClass": "toast-bottom-right"
                        });
                        $('#usersDataTable').DataTable().ajax.reload();
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
    $(document).on('click','.btn-delete-admin-user',function(e){
        var id = $(this).val();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Delete the selected User. You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) =>{
            if(result.value){
                $.ajax({
                    url:'{{route("admin.user-delete")}}',
                    method: 'post',
                    data: {
                        id: id,
                    },
                    success: function(data) {
                        toastr.success('Success!', 'User Deleted successfully', {
                            "positionClass": "toast-bottom-right"
                        });
                        $('.no_of_users_allowed').val(data.no_of_users_allowed);
                        $('.no_of_users_added').val(data.no_of_users_added);
                        $('#usersDataTable').DataTable().ajax.reload();
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
    </script>
@endsection
