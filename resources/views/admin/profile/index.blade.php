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
            <div class="col-lg-12 col-ml-12 mt-5">

                <div class="row">
                    <!-- basic form start -->

                    <div class="col-12 mt-5">

                        <div class="card col-6">
                            <div class="card-body">
                                <h4 class="header-title">Profile Settings
                                </h4>
                                <table class="table d-none"> 
                                    <tr>
                                        <th>Email</th>
                                        <td>arslan@akhtarsitsolutions.com</td>
                                    </tr>
                                </table>
                                <form id="profileSettingForm">
                                @csrf
                              
                                     <div class="form-group">
                                        <label for="exampleInputEmail1">Name</label>
                                        <input  required value="{{$profile->name}}" type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email</label>
                                        <input readonly required value="{{$profile->email}}" type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password</label>
                                        <input  required value="" type="password" class="form-control" name="password" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Confirm Password</label>
                                        <input  required value="" type="password" class="form-control" name="password_confirmation" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Confirm Password">
                                    </div>
                                    <button id="updateBtn" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main content area end -->
            <!-- footer area start-->

            <!-- footer area end-->
        </div>
</div>
</div>
@include('admin.assets.javascript')

<script>
  
    $(document).on('submit','#profileSettingForm',function(e){

        e.preventDefault();
        var formData=$('#profileSettingForm').serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            method:"post",
            url:"{{url('admin/profile')}}",
            data:formData,
            beforeSend: function() {
                $('#updateBtn').prop('disabled', true);
                $('#updateBtn').html('Please wait...');
            },
            success:function(data)
            {
                if(data.success)
                {
                    toastr.success('Success!', 'Profile updated successfully' ,{"positionClass": "toast-bottom-right"});
                     $('#updateBtn').prop('disabled', false);
                    $('#updateBtn').html('Update');
                }
                else
                {
                    toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                     $('#updateBtn').prop('disabled', false);
                    $('#updateBtn').html('Update');
                }
            },
              error: function(request, status, error) {
                    toastr.error('Error!', 'Something went wrong' ,{"positionClass": "toast-bottom-right"});
                    $('#updateBtn').prop('disabled', false);
                    $('#updateBtn').html('Update');
                    $('.form-control').removeClass('is-invalid');
                    $('.form-control').next().remove();
                    json = $.parseJSON(request.responseText);
                    $.each(json.errors, function(key, value){
                        $('input[name="'+key+'"]').after('<span class="invalid-feedback" role="alert"><strong>'+value+'</strong>');
                        $('input[name="'+key+'"]').addClass('is-invalid');
                    });
                },
        })
    })
</script>
@endsection
