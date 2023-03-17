@extends('layouts.app')

@section('content')
<div class="main-content-inner">
    <div class="col-lg-12 col-ml-12 mt-5">

        <div class="row">
            <!-- basic form start -->
                <div class="col-md-6">
                <div class="card">

                    <div class="card-body">
                        <h4 class="header-title">Profile Basic Info Settings
                        </h4>
                        <form id="profileSettingForm">
                            @csrf
                            <input value="{{ $profile->id }}" type="hidden"
                                    class="form-control" name="id" id="user_detai_id">
                            <div class="form-group">
                                <label for="exampleInputEmail1">First Name</label>
                                <input required value="{{ $profile->first_name }}" type="text"
                                    class="form-control" name="first_name" id="first_name"
                                    aria-describedby="emailHelp" placeholder="Enter First Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Last Name</label>
                                <input required value="{{ $profile->last_name }}" type="text"
                                    class="form-control" name="last_name" id="last_name"
                                    aria-describedby="emailHelp" placeholder="Enter Last Name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Primary Email</label>
                                <input required value="{{ $profile->primary_notification_email }}" type="email"
                                    class="form-control" name="primary_notification_email" id="primary_notification_email"
                                    aria-describedby="emailHelp" placeholder="Enter Primary Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Secondary Email</label>
                                <input required value="{{ $profile->secondary_notification_email }}" type="email"
                                    class="form-control" name="secondary_notification_email" id="secondary_notification_email"
                                    aria-describedby="emailHelp" placeholder="Enter Secondary Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Devloper Email</label>
                                <input required value="{{ $profile->developer_email }}" type="email"
                                    class="form-control" name="developer_email" id="developer_email"
                                    aria-describedby="emailHelp" placeholder="Enter Developer Email">
                            </div>
                            <button id="updateBtn" type="submit"
                                class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                        </form>
                    </div>
                </div>
              </div>
                <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Change Password Settings
                        </h4>
                        <form id="changPasswordForm">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Old Password</label>
                                <input value="" type="password" class="form-control" name="old_password"
                                    id="old_password" aria-describedby="emailHelp" placeholder="Enter Old Password">
                            <span id="password_match_message" class="d-none"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">New Password</label>
                                <input value="" type="password" class="form-control" name="password"
                                    id="password" aria-describedby="emailHelp" placeholder="Enter New Password">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Confirm Password</label>
                                <input value="" type="password" class="form-control"
                                    name="password_confirmation" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Confirm Password">
                                    <span id="password_confirm_message" class="d-none"></span>
                            </div>
                            <button id="updatePasswordBtn" type="submit"
                                class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                        </form>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).on('submit', '#profileSettingForm', function(e) {
            @if (Auth::user()->email_verified_at == null)
                toastr.info('Info!', 'Please Verify your account first', {
                    "positionClass": "toast-bottom-right"
                });
                return;
            @endif
            e.preventDefault();
            var formData = $('#profileSettingForm').serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                method: "post",
                url: "{{ url('admin/profile') }}",
                data: formData,
                beforeSend: function() {
                    $('#updateBtn').prop('disabled', true);
                    $('#updateBtn').html('Please wait...');
                },
                success: function(data) {
                    if (data.success) {
                        toastr.success('Success!', 'Profile updated successfully', {
                            "positionClass": "toast-bottom-right"
                        });
                        $('#updateBtn').prop('disabled', false);
                        $('#updateBtn').html('Update');
                    } else {
                        toastr.error('Error!', 'Something went wrong', {
                            "positionClass": "toast-bottom-right"
                        });
                        $('#updateBtn').prop('disabled', false);
                        $('#updateBtn').html('Update');
                    }
                },
                error: function(request, status, error) {
                    toastr.error('Error!', 'Something went wrong', {
                        "positionClass": "toast-bottom-right"
                    });
                    $('#updateBtn').prop('disabled', false);
                    $('#updateBtn').html('Update');
                    $('.form-control').removeClass('is-invalid');
                    $('.form-control').next().remove();
                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function(key, value) {
                        $('input[name="' + key + '"]').after(
                            '<span class="invalid-feedback" role="alert"><strong>' + value +
                            '</strong>');
                        $('input[name="' + key + '"]').addClass('is-invalid');
                    });
                },
            })
        })

        $(document).on('click', '#updatePasswordBtn', function(e) {
            @if (Auth::user()->email_verified_at == null)
                toastr.info('Info!', 'Please Verify your account first', {
                    "positionClass": "toast-bottom-right"
                });
                return;
            @endif
            e.preventDefault();
            var formData = $('#changPasswordForm').serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                method: "post",
                url: "{{ url('admin/change-password') }}",
                data: formData,
                success: function(data) {
                    if(data.success){
                        toastr.success('Success!', data.msg, {
                            "positionClass": "toast-bottom-right"
                        });
                    }else{
                        toastr.error('Error!', data.msg, {
                            "positionClass": "toast-bottom-right"
                        });
                    }
                },
            });
        });
    </script>
@endsection
