<?php

namespace App\Helpers;

use App\Models\Packages\Package;
use App\Models\Packages\PackageFeature;
use App\Models\Packages\StripePayment;
use App\Models\System\SystemFeature;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;

class SubscriptionHelper
{

    public static function index()
    {

        $package = Package::with(['stripeSubscription' => function ($s) {
            $s->where('user_id', Auth::user()->id);
        }, 'packagefeatures.systemFeature'])->get();

        return view("user.Subscription.index", ["package" => $package]);
    }

    public static function createSubscription($request, $stripeClient, $stripeProduct)
    {

        $stripeClient;
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'success_url' => url("user/sucessSubscription"),
            'cancel_url' => url("user/cancelSubscription"),
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $request['priceId'],
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
        ]);


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
        $stripePayment = StripePayment::create([
            'user_id' => $user->id,
            'package_id' => $request->package_id,
            'subscription_id' => $stripeSubcription->id,
            'customer_id' => $customerId,
            'currency' => 'usd',
            'is_subscribed' => 0,
            'detail' => 'Payment for | Monthly subscription',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return ['clientSecret' => $clientSecret, 'url' => $session->url, 'packageId' => $request->package_id, 'price' => $price, 'priceId' => $priceId, 'CustomerId' => $customerId, 'SubscriptionId' => $stripeSubcription->id];
    }
    public static function sucessSubscription($request, $stripeClient, $stripeProduct)
    {

        $user = Auth::user();

        $stripePayment = StripePayment::where('user_id', $user->id)->where('is_subscribed', 0)->whereNotNull('subscription_id')->first();

        $stripePayment->is_subscribed = true;

        $stripePayment->save();

        //  $user->stripePayment = $stripePayment;
        // dd($user);
        return redirect('user/subscription/')->with('success', 'Subcription created Sucessfully');
        //return $user;
    }
    public function pauseSubscription($requestData, $stripeClient)
    {

        $user =  Auth::user();

        $expireDate = \Carbon\Carbon::createFromFormat('Y-m-d', $user->expire_date);

        $user->canceled_at = $expireDate;

        $stripePayment = StripePayment::where('user_id', $user->id)
            ->where('package_id', $requestData['package_id'])
            ->where('subscription_id', $requestData['sub_id'])
            ->where('is_subscribed', 1)->first();

        $stripeClient->subscriptions->update(
            $stripePayment->subscription_id,
            [

                'cancel_at' => $expireDate->timestamp,
            ]
        );

        $stripePayment->is_subscribed = 0;
        $stripePayment->save();

        $user->save();

        $user->stripePayment = $stripePayment;

        $user->load(['package', 'userPackagefeatures']);
        return $user;
    }
    public static function cancelSubscription()
    {

        $user = Auth::user();


        $stripePayment = StripePayment::where('user_id', $user->id)->where('is_subscribed', 0)->whereNotNull('subscription_id')->get();

        foreach ($stripePayment as $sp) {
            $sp->subscription_id = Null;
            $sp->package_id  = Null;
            $sp->user_id   = Null;

            $sp->save();
        }

      

        return redirect('user/subscription/');
        //return $user;
    }

    public function updatePackage()
    {
    }
}
