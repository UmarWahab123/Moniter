@extends('layouts.app')

@section('content')

            <div class="main-content-inner">
                <div class="row">
                    <!-- data table start -->

                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <span class="h4">Users</span>
                                <button class="btn btn-primary btn-sm float-right" id="addAdminBtn"> <i
                                        class="fa fa-plus"></i> Add Admin</button>
                                    <table id="usersDataTable" class=" table table-stripped text-center">
                                        <thead>
                                            <tr>

                                                <th>Name </th>
                                                <th>Role </th>
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
            <div class="modal fade" id="addAdminModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Admin User</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="addAdminForm">
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

                                <div class="form-check d-none">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>
                                <button type="submit" id="adminSubmitBtn"
                                    class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>
                            </form>
                        </div>
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
                    ajax: "{{ route('superAdmin.get-data') }}",
                    columns: [

                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'role',
                            name: 'role'
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
                $(document).on('click', '#addAdminBtn', function() {
                    @if (Auth::user()->email_verified_at == null)
                        toastr.info('Info!', 'Please Verify your account first', {
                            "positionClass": "toast-bottom-right"
                        });
                        return;
                    @endif
                    $('#addAdminModal').modal('show');

                });
                $('#addAdminForm').on('submit', function(e) {
                    e.preventDefault();
                    var form = $('#addAdminForm').serialize();
                    $.ajax({
                        url: '{{ url('superAdmin/add-user') }}',
                        method: 'post',
                        data: form,
                        beforeSend: function() {
                            $('#adminSubmitBtn').prop('disabled', true);
                            $('#adminSubmitBtn').html('Please wait...');
                        },
                        success: function(data) {
                            $('#addAdminModal').modal('hide');
                            $('#addAdminForm')[0].reset();
                            toastr.success('Success!', 'Admin added successfully', {
                                "positionClass": "toast-bottom-right"
                            });
                            $('#usersDataTable').DataTable().ajax.reload();
                            $('#adminSubmitBtn').prop('disabled', false);
                            $('#adminSubmitBtn').html('Submit');
                        },
                        error: function() {
                            toastr.error('Error!', 'Something went wrong', {
                                "positionClass": "toast-bottom-right"
                            });
                            $('#adminSubmitBtn').prop('disabled', false);
                            $('#adminSubmitBtn').html('Submit');
                        },
                    })
                });
                $(document).on('click', '.user-status', function(e) {
                    var id = $(this).val();
                    var status = $(this).data('status');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to Susupemd/Un-Suspend the selected User!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, do it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '{{ route('superAdmin.user-status') }}',
                                method: 'get',
                                data: {
                                    id: id,
                                    status: status
                                },
                                success: function(data) {
                                    toastr.success('Success!', 'Action Performed successfully', {
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
                $(document).on('click', '.btn_delete', function(e) {
                    var id = $(this).val();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to Delete the selected User. You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, do it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '{{ route("superAdmin.user-delete") }}',
                                method: 'post',
                                data: {
                                    id: id,
                                },
                                success: function(data) {
                                    toastr.success('Success!', 'User Deleted successfully', {
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
            </script>
        @endsection
