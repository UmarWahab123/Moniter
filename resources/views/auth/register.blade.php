@extends('layouts.app')

@section('content')
    <!-- login area start -->
    <div class="login-area login-s2">
        <div class="container">
            <div class="login-box ptb--100">
                <!-- <form method="POST" action="{{ route('register') }}"> -->
                <form method="POST" class="register-user">
                    @csrf

                    <div class="login-form-head">
                        <h4>Sign up</h4>
                        <p>Hello there, Sign up and Join with Us</p>
                    </div>
                    <div class="login-form-body">
                        <div class="form-group">
                            <label for="exampleInputName1">Full Name</label>
                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                id="exampleInputName1">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input class="form-control @error('email') is-invalid @enderror" name="email" type="email"
                                id="exampleInputEmail1">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">Confirm Password</label>
                            <input name="password_confirmation" type="password" id="exampleInputPassword2"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="submit-btn-area">
                            <button id="form_submit" type="submit">Submit <i class="ti-arrow-right"></i></button>
                        </div>
                        <div class="form-footer text-center mt-5">
                            <p class="text-muted">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript"></script>
    @endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $(document).on('submit', '.register-user', function(e){
          e.preventDefault();
          var formdata = $('.register-user').serialize();
          $.ajax({
            method: 'post',
            dataType: 'json',
            url: "{{route('register-user')}}",
            data: formdata,
            success: function(data) {
            if(data.success == true){
                    toastr.success('Success!', data.msg, {
                        "positionClass": "toast-bottom-right"
                    });
                    window.location.href = '{{url('/login')}}';
                }else{
                    toastr.error('Error!', data.msg, {
                        "positionClass": "toast-bottom-right"
                    });
                }
            },
        })
        });

      
    });
</script>
@endsection
