@extends('layouts.app')

@section('content')
    <div class="login-area login-s2">
        <div class="container">
            <div class="row">
                <div class="col">
                    @if (session('email-verified'))
                        <div class="alert alert-success w-100">
                            {{ session('email-verified') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="login-box ptb--100">
                {{-- <form method="POST" action="{{ route('login') }}"> --}}
                <form id="Form_login" action="{{ route('custom_login') }}" method="post">
                    @csrf
                    <div class="login-form-head">
                        <h4>Sign In</h4>
                        <p>Hello there, Sign in and start managing your Admin Template</p>
                    </div>
                    <div class="login-form-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input name="email" type="email" id="login_email"
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
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
                    if (data.deleted_user) {
                        toastr.info('Info!',
                        'Your Account has been deleted by Administrator. Please contact Administrator', {
                            "positionClass": "toast-bottom-right"
                        });
                    }
                    else if (data.suspended_user) {
                        toastr.info('Info!',
                        'Your Account has been Suspended by Administrator. Please contact Administrator', {
                            "positionClass": "toast-bottom-right"
                        });
                    }
                    else if (data.email_verified == false) {
                        toastr.info('Info!',
                        'Email Not Verified. Please Verify your email first', {
                            "positionClass": "toast-bottom-right"
                        });
                    }
                    else if(data.direct_login){
                        $('#Form_login').submit();
                    }
                    else if (data.success) {
                        let email = $('#login_email').val();
                        let password = $('#login_passeord').val();
                        let checkbox = $('#remember').attr('checked');
                        checkbox = checkbox == undefined ? '' : 'checked';
                        location.href = '{{ route("login.custom-verify") }}?email='+email+'&password='+encodeURIComponent(password)+'&checkbox='+checkbox;
                    } else {
                        toastr.info('Info!',
                        'Invalid Email/Password', {
                            "positionClass": "toast-bottom-right"
                        });
                    }
                    $('#loader_modal').modal('hide');
                }
            })
        });
    </script>
@endsection
