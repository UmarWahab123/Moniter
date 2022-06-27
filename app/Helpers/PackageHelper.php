<?php

namespace App\Helpers;

use App\Models\Packages\Package;
use App\Models\Packages\PackageFeature;
use App\Models\System\SystemFeature;
use Yajra\Datatables\Datatables;

class PackageHelper
{

    public static function index($request)
    {
        $data['systemFeature'] = SystemFeature::all();

        if ($request->ajax()) {
            return (new PackageHelper)->UserDatatable();
        }
        return view('admin.packages.index', $data);
    }
    private function UserDatatable()
    {
        $query = Package::all();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                if($item->is_disabled ==0){
                    $html_string = '
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-package"  title="Edit"><i class="fa fa-pencil"></i></button>
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm change-status-active"  title="ActiveStatus"><i class="fa fa-toggle-on" aria-hidden="true"></i></button>
                   '
                    
                    ;
                }else{
                    $html_string = '
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-package"  title="Edit"><i class="fa fa-pencil"></i></button>
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm change-status-inactive"  title="InActiveStatus"><i class="fa fa-toggle-off" aria-hidden="true"></i></button>
                    '
                    
                    ;
                }
              
                      
                return $html_string;
            })

           
            ->addColumn('status', function ($item) {
                if ($item->is_disabled == 0) {
                    return '<span class="badge badge-success ">Active</span>';
                } else {
                    return '<span class="badge badge-danger ">Suspended</span>';
                }
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
    public static function storePackages($request, $stripeClient, $stripeProduct)
    {


        $packages =  Package::create($request);

        foreach ($request['systemfeature'] as $pfk => $pfv) {


            PackageFeature::create([
                'max_allowed_count' => $pfv,
                'package_id' => $packages->id,
                'system_feature_id' => $pfk,
            ]);
        }

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

    public static function edit($request)
    {
        $package = Package::with(['packagefeatures' => function ($q) {
            $q->with('systemFeature');
        }])->where('id', $request->id)->get()->first();
      
        if ($package != null) {
    
            return response()->json(['success' => true, 'data' => $package]);
        }
        return response()->json(['success' => false]);
    }

    public static function update($request)
    {
       // dd($request->all());

        $package = Package::find($request->package_id);

        if ($package != null) {
            $package->name = $request->name;

            $array = [];
            foreach ($request['systemfeature'] as $pfk => $pfv) {

                $packageFeatures = PackageFeature::where('system_feature_id', $pfk)->where('package_id', $package->id)->update(
                    ['max_allowed_count' => $pfv]
                );
            }
            $array[] = $packageFeatures;

            $package->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public static function updateStatus($request)
    {
       // dd($request->all());
        $package = Package::find($request->id);
        
        if ($package != null) {

            $package->is_disabled = $request->status;

            $package->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
