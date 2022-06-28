<?php

namespace App\Helpers;

use App\Models\Packages\Package;
use App\Models\Packages\PackageFeature;
use App\Models\Packages\StripePayment;
use App\Models\System\SystemFeature;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class SubscriptionHelper
{

    public static function index()
    {

        $package = Package::with(['packagefeatures' => function ($q) {
            $q->with('systemFeature');
        }])->get();

        return view("user.Subscription.index", ["package" => $package]);
    }

    public static function createSubscription($request, $stripeClient, $stripeProduct)
    {
       // dd($request->all());
        $user =  Auth::user();

        $package = Package::findorfail($request->package_id);
        
        $dollars = str_replace('$', '', $package['price']);

        $priceId = $package['price_id'];

        $price = bcmul($dollars, 100);
        $stripePayment = StripePayment::where('user_id', $user->id)->whereNotNull('customer_id')->first();

        if ($stripePayment) {
            $customerId = $stripePayment->customer_id;
        } else {

            $createCustomer = $stripeClient->customers->create([
                'name' => $user['name'],
                'email' => $user['email'],
            ]);

            $customerId = $createCustomer->id;

            $stripeClient->customers->createSource(
                $customerId,
                ['source' => 'tok_visa']
            );

            StripePayment::create([
                'user_id' => $user->id,
                'customer_id' => $customerId,
                'currency' => 'usd',
                'detail' => 'Payment for | Monthly subscription',
            ]);
        }

        $stripeSubcription =  $stripeClient->subscriptions->create([
            'customer' => $customerId,

            'items' => [
                ['price' =>  $priceId],
            ],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        $clientSecret = $stripeSubcription->latest_invoice->payment_intent->client_secret;

        return ['clientSecret' => $clientSecret, 'packageId' => $request->package_id, 'price' => $price, 'priceId' => $priceId, 'CustomerId' => $customerId, 'SubscriptionId' => $stripeSubcription->id];
    }
    public static function sucessSubscription($request, $stripeClient, $stripeProduct)
    {

        $user = Auth::user();

        $price = number_format($request['price'] / 100, 2, '.', ' ');

        $subscribeId = $request['subscriptionId'];

        $stripePayment = StripePayment::where('user_id', $user->id)->where('package_id', $request['package_id'])
            ->where('is_subscribed', 1)
            ->whereNotNull('subscription_id')->first();

        if ($stripePayment) {


            $stripePayment->subscription_id = $subscribeId;
        } else {

            $stripePayment = StripePayment::create([
                'user_id' => $user->id,
                'package_id' => $request['package_id'],
                'subscription_id' => $subscribeId,
                'customer_id' => $request['customer_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $stripePayment->is_subscribed = true;

        $stripePayment->save();

        $user->stripePayment = $stripePayment;
        dd($user);
        return redirect()->back()->with('success', 'Subcription created Sucessfully');
        //return $user;
    }
}
