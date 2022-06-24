@extends('layouts.app')

@section('content')
    <div class="login-area login-s2">
        <div class="container">
            <div class="login-box ptb--100">
                <form id="Form_login" action="{{ route('custom_login') }}" method="post">
                    @csrf
                    <div class="login-form-head">
                        <h4>Verification Code</h4>
                        <p>Hello there, Verification Code is send to your email address.</p>
                    </div>
                    <div class="login-form-body">
                        <input name="email" type="hidden" id="login_email" value="{{ $email }}">
                        <input name="password" type="hidden" id="login_passeord" value="{{ $password }}">
                        <label class="d-none">
                            <input type="checkbox" name="remember" checked="{{ $checkbox }}">
                            {{ __('Remember Me') }}
                        </label>
                        <div class="form-group">
                            <label for="verification_code">Email Verification Code</label>
                            <input name="verification_code" type="text" id="verification_code"
                                class="form-control" placeholder="Enter verification code">
                        </div>
                        <div class="submit-btn-area">
                            <button type="submit" id="btn_custom_login">Verify</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.assets.javascript')
    <script>
        $(document).ready(function() {
            let url = location.href.split('?');

            window.history.replaceState(null, null, url[0]);
        });
    </script>
@endsection
