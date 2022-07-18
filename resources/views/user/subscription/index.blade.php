@extends('layouts.app')

@section('content')
<style>
    .price_pkg {
        color: white;
        font-weight: 600;
    }

    .pkg_name {
        color: white;
        font-weight: 600;
    }

    .subscription-heading {
        font-size: 28px;
        font-weight: 600;
        line-height: 43px;
        font-style: normal;
    }

    #loading img {
        margin-bottom: -105px;
        margin-top: -80px;
    }
</style>
<!-- preloader area end -->
<!-- page container area start -->
<div class="page-container">
    <!-- sidebar menu area start -->
    @include('user.assets.sidebar')
    <!-- sidebar menu area end -->
    <!-- main content area start -->
    <div class="main-content">
        <!-- header area start -->
        {{-- @include('admin.assets.header') --}}

        <!-- header area end -->
        <!-- page title area start -->
        @include('user.assets.title_area')

        <!-- page title area end -->

        <div class="col-sm-10 mt-4">
            <div class="row">
                <div class="col text-center"><span class="subscription-heading">Subscription Plans</span><br><span class="text-muted">We have extremely transparent plans</span></div>
            </div>

            <div id="packages-container-div" class="py-4 mt-2" style="display: flex;flex-wrap: wrap;overflow-y: auto;">
                @foreach ($package as $pk)

                @if (isset($pk['stripeSubscription']) && $pk['stripeSubscription']->is_subscribed ==1)
                <div id="plan-item" class="mb-3" style="margin: 10px;width:40%;background: rgb(226, 241, 244);border: 3px solid rgb(9, 148, 248);">
                    <div id="plan-card" class="card text-center">
                        <form id="" class="form-control createSubscriptionForm" style="background: rgb(226, 241, 244);">
                            @csrf
                            <input type="hidden" name="price" class="form-control" value="{{$pk->price }}" aria-describedby="" placeholder="">
                            <input type="hidden" name="package_id" class="form-control" value="{{$pk->id }}" aria-describedby="" placeholder="">

                            <div class="card-header p-4" style=" background: rgb(130 81 247)">
                                <div><span class="price_pkg">$ {{$pk->price }} </span><span class="month_text"></span><br><span class="pkg_name">{{$pk->name}} <small>(Monthly)</small></span></div>
                            </div>
                            <input type="hidden" name="priceId" class="form-control" id="priceId" value="{{$pk->price_id }}" aria-describedby="" placeholder="">


                            <div class="card-body p-4">
                                <div class="mt-2" style="display: flex; flex-direction: column; justify-content: center;">
                                    @foreach ($pk['packagefeatures'] as $pf)
                                    <div>


                                        <div style="display: flex; justify-content: center;">
                                            <div class="col-8"><span class="pkg_detail"> {{$pf->systemFeature->name}}</span></div>
                                            <div class="col-4 px-0"><span class="pkg_detail_clr">{{$pf->max_allowed_count}}</span></div>

                                        </div>
                                    </div><br>

                                    @endforeach
                                    <br>
                                    <div class="row">
                                        <div class="col">
                                            <button class="btn btn-primary btn-sm mb-2 addSubcriptionBtn" name="{{$pk->id}}" id="">Pause</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                @else
                <div id="plan-item" class="mb-3" style="margin: 10px;width:40%;">
                    <div id="plan-card" class="card text-center">
                        <form id="" class="form-control createSubscriptionForm">
                            @csrf
                            <input type="hidden" name="price" class="form-control" value="{{$pk->price }}" aria-describedby="" placeholder="">
                            <input type="hidden" name="package_id" class="form-control" value="{{$pk->id }}" aria-describedby="" placeholder="">

                            <div class="card-header p-4" style=" background: rgb(130 81 247)">
                                <div><span class="price_pkg">$ {{$pk->price }} </span><span class="month_text"></span><br><span class="pkg_name">{{$pk->name}} <small>(Monthly)</small></span></div>
                            </div>
                            <input type="hidden" name="priceId" class="form-control" id="priceId" value="{{$pk->price_id }}" aria-describedby="" placeholder="">


                            <div class="card-body p-4">
                                <div class="mt-2" style="display: flex; flex-direction: column; justify-content: center;">
                                    @foreach ($pk['packagefeatures'] as $pf)
                                    <div>


                                        <div style="display: flex; justify-content: center;">
                                            <div class="col-8"><span class="pkg_detail"> {{$pf->systemFeature->name}}</span></div>
                                            <div class="col-4 px-0"><span class="pkg_detail_clr">{{$pf->max_allowed_count}}</span></div>

                                        </div>
                                    </div><br>

                                    @endforeach
                                    <div class="row">
                                        <div class="col">
                                            <div class=" mb-2" name="" id="loading">

                                            </div>

                                        </div>
                                    </div>
                                    <br>
                                    @if (isset($pk['stripeSubscription']))
                                    <div class="row">
                                        <div class="col">
                                            <button class="btn btn-primary btn-sm mb-2 addSubcriptionBtn" name="{{$pk->id}}" id="">Create Subscription</button>

                                        </div>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col">
                                            <button class="btn btn-primary btn-sm mb-2 addSubcriptionBtn" name="{{$pk->id}}" id="">Update Subscription</button>

                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                @endif
                @endforeach

            </div>
            <div tabindex="-1" id="exampleModal" aria-hidden="true" class="modal fade" aria-labelledby="exampleModalLabel">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="row">
                            <div class="col"><i class="fas fa-times float-right pr-3 pt-2" style="cursor: pointer; font-size: 20px;"></i></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center row modal-body">
                            <div style="height: 642px; display: flex; align-items: center; justify-content: center;"><span class="css-kud8pv"><span class="css-7w8dd7"></span><span class="css-jgnan4"></span><span class="css-1rcro1c"></span><span class="css-6pbwp4"></span><span class="css-ardiy6"></span><span class="css-1h412f3"></span><span class="css-3yll0v"></span><span class="css-19mck66"></span></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content area end -->
<!-- footer area start-->
@include('user.assets.footer')

<!-- footer area end-->

<div class="modal fade" id="subscriptionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Method</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{ url('user/sucessSubscription') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('pk_test_51K9wCBCS6ywz511Ria36w4YXMry4tekv8nMYKPijWo3i5VrKn7CeYZRzQOrhkSdEQsWIeRNo8iEsI90K0enL0oi200GweX3Jy6') }}" id="payment-form">
                    @csrf
                    <input type="hidden" name="subscriptionId" class="form-control" id="subscriptionId" value="" aria-describedby="" placeholder="">
                    <input type="hidden" name="priceId" class="form-control" id="priceId" value="" aria-describedby="" placeholder="">
                    <input type="hidden" name="price" class="form-control" id="price" value="" aria-describedby="" placeholder="">
                    <input type="hidden" name="packageId" class="form-control" id="packageId" value="" aria-describedby="" placeholder="">



                    <div class='form-row row'>
                        <div class='col-xs-12 form-group card required'>
                            <label class='control-label'>Card Number</label> <input autocomplete='off' class='form-control card-number' size='100' type='text'>
                        </div>
                    </div>

                    <div class='form-row row'>
                        <div class='col-xs-12  form-group cvc required'>
                            <label class='control-label'>CVC</label> <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='15' type='text'>
                        </div>
                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                            <label class='control-label'>Expiration Month</label> <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
                        </div>
                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                            <label class='control-label'>Expiration Year</label> <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
                        </div>
                    </div>

                    <div class='form-row row'>
                        <div class='col-md-12 error form-group hide'>
                            <div class='alert-danger alert'>Please correct the errors and try
                                again.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12  col-md-12">
                            <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@include('user.assets.javascript')
<!-- Start datatable js -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
    $(document).on('click', '.addSubcriptionBtn', function(e) {

        $('#loading').html('<img src="http://rpg.drivethrustuff.com/shared_images/ajax-loader.gif"/>');
        $('.addSubcriptionBtn').hide();

        var form = $('.createSubscriptionForm').serialize();
        e.preventDefault();
        e.stopImmediatePropagation();
        $.ajax({

            url: "{{ url('user/createSubscription') }}",
            method: 'post',
            data: form,
            success: function(data) {
                //  alert(data.url);
                $('#loading').hide();

                $('#subscriptionId').val(data.SubscriptionId),
                    $('#priceId').val(data.priceId),
                    $('#price').val(data.price),
                    $('#packageId').val(data.packageId)
                window.location = data.url;
                // $('#subscriptionModal').modal('show');

            },
            error: function() {
                toastr.error('Error!', 'Something went wrong', {
                    "positionClass": "toast-bottom-right"
                });
            },
        })


    });

    $(function() {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');

            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });

            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey('pk_test_51K9wCBCS6ywz511Ria36w4YXMry4tekv8nMYKPijWo3i5VrKn7CeYZRzQOrhkSdEQsWIeRNo8iEsI90K0enL0oi200GweX3Jy6');
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('hide')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

    });
</script>
@endsection