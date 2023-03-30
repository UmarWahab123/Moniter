<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>



    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/slicknav.min.css') }}">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css"
        media="all" />
    <!-- Start datatable css -->
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.css"/> --}}

    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <!-- others css -->
    <link rel="stylesheet" href="{{ asset('public/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <!-- modernizr css -->
    <script src="{{ asset('public/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <style>
        #myProgress {
            width: 100%;
            background-color: #ddd;
        }

        #myBar {
            width: 1%;
            height: 30px;
            background-color: #4CAF50;
        }

        .custom-table tr th,
        .custom-table tr td {
            border-bottom: 0px !important;
            border-top: 0px !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <main class="py-4">

            @if (Auth::user() && Auth::user()->email_verified_at == null)
                <div class="row mb-4">
                    <div class="alert alert-primary fixed-top col-md-12" role="alert"
                        style="font-size: 20px; min-height: 18px; text-align: center">
                        <b> User must verify the email before performing any actions. <a
                                href="javascript:void(0);" id="resendEmail">Click Here</a> to send verification email.</b>
                    </div>
                </div>
            @endif
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

            <!-- sidebar menu area start -->
            @if (@auth()->user()->role_id == 1)
                @include('admin.assets.sidebar')
            @elseif(@auth()->user()->role_id == 3)
                @include('superAdmin.assets.sidebar')
            @elseif(@auth()->user()->role_id == 2)
                @include('user.assets.sidebar')
            @endif
            <!-- sidebar menu area end -->
            <div class="page-container">
            <!-- main content area start -->
            <div class="main-content">
            <!-- page title area start -->
            @if (@auth()->user()->role_id == 1)
                @include('admin.assets.title_area')
            @elseif (@auth()->user()->role_id == 3)
            @include('superAdmin.assets.title_area')
            @elseif(@auth()->user()->role_id == 2)
                @include('user.assets.title_area')
            @endif
            
            @yield('content')

            </div>
            </div>
            @if (@auth()->user()->role_id == 1)
                @include('admin.assets.footer')
            @elseif(@auth()->user()->role_id == 2)
                @include('user.assets.footer')
            @elseif(@auth()->user()->role_id == 3)
            @include('superAdmin.assets.footer')
            @endif

            @if (@auth()->user()->role_id == 1)
                @include('admin.assets.javascript')
            @elseif(@auth()->user()->role_id == 2)
                @include('user.assets.javascript')
            @else
                @include('superAdmin.assets.javascript')
            @endif
            @yield('scripts')
            <script>
                $(document).on('click', '#resendEmail', function() {
                    $.ajax({
                        beforeSend: function() {
                            $('#loader_modal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $("#loader_modal").modal('show');
                        },
                        url: "{{ route('emails.resendEmail') }}",
                        success: function(data) {
                            $('#loader_modal').modal('hide');
                            toastr.success('Success!', 'Email Resend successfully', {
                                "positionClass": "toast-bottom-right"
                            });
                        },
                        error: function(data) {
                            $('#loader_modal').modal('hide');
                            toastr.error('Error!', 'Email not send, please check your internet connection', {
                                "positionClass": "toast-bottom-right"
                            });
                        }
                    });
                });
                $(document).ready(function(){
                    var activeurl = window.location;
                $('a[href="'+activeurl+'"]').parents('li').addClass('active');

                });
            </script>
        </main>
    </div>

</body>

</html>
