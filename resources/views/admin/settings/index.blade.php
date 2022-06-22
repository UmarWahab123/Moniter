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
                                    <h4 class="header-title">Default Settings
                                    </h4>
                                    <table class="table d-none">
                                        <tr>
                                            <th>Email</th>
                                            <td>arslan@akhtarsitsolutions.com</td>
                                        </tr>
                                    </table>
                                    <form id="defaultSettingForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input required value="{{ @$setting->settings }}" type="email"
                                                class="form-control" name="email" id="exampleInputEmail1"
                                                aria-describedby="emailHelp" placeholder="Enter email">
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
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
        $(document).on('submit', '#defaultSettingForm', function(e) {
            @if (Auth::user()->email_verified_at == null)
                toastr.info('Info!', 'Please Verify your account first', {
                    "positionClass": "toast-bottom-right"
                });
                return;
            @endif
            e.preventDefault();
            var formData = $('#defaultSettingForm').serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                method: "post",
                url: "{{ url('admin/add-settings') }}",
                data: formData,
                success: function(data) {
                    if (data.success) {
                        toastr.success('Success!', 'Settings saved successfully', {
                            "positionClass": "toast-bottom-right"
                        });
                    } else {
                        toastr.error('Error!', 'Something went wrong', {
                            "positionClass": "toast-bottom-right"
                        });
                    }
                }
            })
        })
    </script>
@endsection
