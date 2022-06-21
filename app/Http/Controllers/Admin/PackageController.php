<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PackageHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Stripe\Stripe;
use \Stripe\StripeClient;

class PackageController extends Controller
{

      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function stripeApiKey()
    {
        if (\config('site.stripe_is_testing') == true) {

            $stripeTestingApiKey = \config('site.stripe_testing_api_test_key');
            $stripe =  Stripe::setApiKey($stripeTestingApiKey);
            $stripe = new StripeClient(
                $stripeTestingApiKey
            );
            return $stripe;
        } else {

            $stripeLiveApiKey = \config('site.stripe_live_api_test_key');
            $stripe =  Stripe::setApiKey($stripeLiveApiKey);
            $stripe = new StripeClient(
                $stripeLiveApiKey
            );
            return $stripe;
        }
    }
    public function productStripeId()
    {
        if (\config('site.stripe_is_testing') == true) {

            $stripe_testing_prodcut_id = \config('site.stripe_testing_prodcut_id');
            return $stripe_testing_prodcut_id;
        } else {
            $stripe_live_prodcut_id = \config('site.stripe_live_prodcut_id');
            return $stripe_live_prodcut_id;
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       
        return view('admin.packages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

         //

       //  dd($request->all());

         $stripeClient = $this->stripeApiKey();
        
         $stripeProduct = $this->productStripeId();
 
         $package = PackageHelper::storePackages($request->all(), $stripeClient, $stripeProduct);

         if ($package) {
             return response()->json([
                 'success' => true,
                 'message' => 'packages added successfully',
             ]);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

