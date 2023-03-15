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
        $package = Package::with(['packagefeatures.systemFeature'])->get();
        return view("admin.subscription.index", ["package" => $package]);
    }
    public static function createSubscription($request)
    {
        $user = User::find(Auth::user()->id);
        if ($user != null) {
            $package_id = $user->package_id;
            if($package_id != null){
            $action = "upgrade";
            $user->package_id = $request->package_id;
            $user->save();
            return response()->json(['success' => true,'action'=>$action]);
            }else{
            $action = "Subscribed";
                $user->package_id = $request->package_id;
                $user->save();
            return response()->json(['success' => true,'action'=>$action]);
            }
        }
        return response()->json(['success' => false]);
    }
    public static function sucessSubscription($request, $stripeClient, $stripeProduct)
    {

        $user = Auth::user();

        $stripePayment = StripePayment::where('user_id', $user->id)->where('is_subscribed', 0)->whereNotNull('subscription_id')->first();

        $stripePayment->is_subscribed = true;

        $stripePayment->save();

        //  $user->stripePayment = $stripePayment;
        // dd($user);
        return redirect('admin/subscription/')->with('success', 'Subcription created Sucessfully');
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

      

        return redirect('admin/subscription/');
        //return $user;
    }

    public function updatePackage()
    {
    }
}
