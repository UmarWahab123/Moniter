@extends('layouts.app')

@section('content')
    <div class="login-area login-s2">
        <div class="container">
            <div class="login-box ptb--100">
                {{-- <form method="POST" action="{{ route('login') }}"> --}}
                <form id="Form_login">
                    @csrf
                    <div class="login-form-head">
                        <h4>Sign In</h4>
                        <p>Hello there, Sign in and start managing your Admin Template</p>
                    </div>
                    <div class="login-form-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input name="email" type="email" id="login_emal"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input name="password" type="password" id="login_passeord"
                                class="form-control @error('password') is-invalid @enderror">
                            <div class="text-danger"></div>
                        </div>
                        <div class="row mb-4 rmber-area">

                            <div class="col-6">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                        <div class="submit-btn-area">
                            <button type="button" id="btn_custom_login">Submit <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="form-footer text-center mt-5">
                            <p class="text-muted">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Modal_Verification_Code">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verification Code</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="submit-btn-area">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <input type="text" name="verification_code" id="verification_code" class="form-control"
                                    placeholder=" Enter Verification Code send to your email">
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <button id="form_submit" type="button">Verify And Login</button>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="loader_modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 style="text-align:center;">Please wait</h3>
                    <p style="text-align:center;"><img src="{{ asset('public/images/Spin-1s-200px.gif') }}" height="100px"
                            width="100px"></p>
                </div>
            </div>
        </div>
    </div>
    @include('admin.assets.javascript')
    <script type="text/javascript">
        $(document).on('click', '#btn_custom_login', function() {
            let formData = $('#Form_login').serialize();
            $.ajax({
                url: "{{ route('emails.send-verification_code') }}",
                method: 'post',
                data: formData,
                beforeSend: function() {
                    $('#loader_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#loader_modal").modal('show');
                },
                success: function(data) {
                    if (data.success) {
                        $('#loader_modal').modal('hide');
                        $('#Modal_Verification_Code').modal('show');
                    }
                }
            })
        });
        $(document).on('click', '#form_submit', function() {
            if ($('#verification_code').val() == '') {
                toastr.info('Info!',
                    'Please ENter Verification Code', {
                        "positionClass": "toast-bottom-right"
                    });
            }
            let formData = $('#Form_login').serializeArray();
            formData.push({
                name: "verification_code",
                value: $('#verification_code').val(),
            });
            $.ajax({
                url: "{{ route('login') }}",
                method: 'post',
                data: formData,
                success: function(data) {
                    if (!data.success) {
                        toastr.error('Error!',
                            'Email not send, please check your internet connection', {
                                "positionClass": "toast-bottom-right"
                            });
                    }
                }
            })
        });
    </script>
@endsection
