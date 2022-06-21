<?php

namespace App\Helpers;

use App\Models\Packages\Package;
use App\Models\Packages\PackageFeature;
use App\Models\System\SystemFeature;

class PackageHelper
{

    public static function storePackages($request, $stripeClient, $stripeProduct)
    {

        $packages =  Package::create($request);

        // $systemFeature = SystemFeature::all();

        // foreach ($systemFeature as $sf) {

        //     PackageFeature::create([
        //         'package_id' => $packages->id,
        //         'system_feature_id' => $sf['id'],
        //         'max_allowed_count' => $request['max_allowed_count'],
        //     ]);
        // }
        
        $stripePrice =  $stripeClient->plans->create([
            'amount' => bcmul($request['price'], 100),
            'currency' => 'usd',
            'interval' => 'month',
            'product' => $stripeProduct
        ]);

        $packages->price_id = $stripePrice->id;

        $packages->save();

        return $packages;
    }
}
