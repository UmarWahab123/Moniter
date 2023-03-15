<?php

namespace App\Helpers;

use App\Models\Packages\Package;
use App\Models\Packages\PackageFeature;
use App\Models\System\SystemFeature;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class PackageHelper
{

    public static function index($request)
    {
        $data['systemFeature'] = SystemFeature::all();

        if ($request->ajax()) {
            return (new PackageHelper)->UserDatatable();
        }
         return view('superAdmin.packages.index', $data);
    }
    private function UserDatatable()
    {
        $query = Package::all();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                if($item->status == 1){
                    $html_string = '
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-package"  title="Edit"><i class="fa fa-pencil"></i></button>
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm change-status" data-status="0" title="ActiveStatus"><i class="fa fa-toggle-on" aria-hidden="true"></i></button>
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm btn-rounded assign-feature" title="AssignFeature">Assign Feature</button>

                   '
                    
                    ;
                }else{
                    $html_string = '
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-package" title="Edit"><i class="fa fa-pencil"></i></button>
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm change-status" data-status="1" title="InActiveStatus"><i class="fa fa-toggle-off" aria-hidden="true"></i></button>
                    <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm btn-rounded assign-feature" title="AssignFeature">Assign Feature</button>
                    '
                    
                    ;
                }
              
                      
                return $html_string;
            })

           
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
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
    public static function storePackages($request)
    {
        $id = $request['id'];
        $data = $request;
        $action = "Added";
        if ($id) {
            $action = "Updated";
            $packages = Package::find($id)->update($data);
        } else {
            $packages = Package::create($data);
        }
        $packages = [$packages, $action];
        return $packages; 
    }
    public static function edit($request)
    {
        $package = Package::where('id', $request->id)->first();
        if ($package != null) {
            return response()->json(['success' => true, 'data' => $package]);
        }
        return response()->json(['success' => false]);
    }
    public static function updateStatus($request)
    {
        $package = Package::find($request->id);
        
        if ($package != null) {

            $package->status = $request->status;

            $package->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public static function storeAssignFeature($request)
    {
        $data = $request;
        $packageFeature = PackageFeature::create($data);
        return $packageFeature; 
    }
    public static function systemFeatures($request)
    {
        if ($request->ajax()) {
            return (new PackageHelper)->FeatureDatatable();
        }
        return view('superAdmin.features.index');
    }
    private function FeatureDatatable()
    {
        $query = SystemFeature::all();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '
                <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-system-feature"  title="Edit"><i class="fa fa-pencil"></i></button>
                <button  value="' . $item->id . '" class="btn btn-sm btn-outline-danger btn_delete ml-1" value="25" title="Delete Feature"><i class="fa fa-trash"></i></button>
                '
                ;
             return $html_string;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public static function storeSystemFeature($request)
    {
        $id = $request['id'];
        $data = $request;
        $action = "Added";
        if ($id) {
            $action = "Updated";
            $systemFeature = SystemFeature::find($id)->update($data);
        } else {
            $systemFeature = SystemFeature::create($data);
        }
        $systemFeature = [$systemFeature, $action];
        return $systemFeature; 
    }
    public static function systemFeatureEdit($request)
    {
        $systemFeature = SystemFeature::where('id', $request->id)->first();
        if ($systemFeature != null) {
            return response()->json(['success' => true, 'data' => $systemFeature]);
        }
        return response()->json(['success' => false]);
    }
    public static function systemFeatureDelete($request)
    {
        $systemFeature = SystemFeature::find($request->id)->delete();
         return response()->json(['success' => true]);
    }
    public static function assignFeatureDelete($request)
    {
         $systemFeature = PackageFeature::find($request->id)->delete();
         return response()->json(['success' => true]);
    }


    public static function assignedFeaturesTable($data){
        $html = '
        <table id="assigned-features-to-package" class="table text-center table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>S.No</th>
                    <th>Feature</th>
                    <th>Total Count</th>
                    <th>Action</th>
                </tr>
            </thead><tbody>';
            foreach($data as $key => $package){
                $html .= '<tr>
                    <td>'.($key+1).'</td>
                    <td>'.@$package->systemFeature->name.'</td>
                    <td>'.@$package->max_allowed_count.'</td>
                    <td><button  value="' . @$package->id . '" class="btn btn-sm btn-outline-danger assigned_feature_delete ml-1" value="25" title="Delete Assigned Feature"><i class="fa fa-trash"></i></button></td>
                </tr>';
            }
        $html .= '</tbody></table>';

        return $html;
    }


}
