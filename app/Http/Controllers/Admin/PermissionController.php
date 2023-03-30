<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\UserPermission;

class PermissionController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permission(Request $request){
        return PermissionHelper::permission($request);
    }
    public function addPermission(Request $request)
    {
        try {
            $permissions = ['Add Website', 'Add Server'];
            foreach ($permissions as $key => $permission) {
                Permission::updateOrCreate(
                    [
                        'name'  => $permission
                    ],
                    [
                        'name' => $permission,
                        'index' => ++$key
                    ]);
            }
            return response()->json(['success' => true, 'msg' => "All Permissions Added Successfully !!!"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
        
        // $permission = PermissionHelper::storePermission($request->all());
        // if ($permission) {
        //     $action = $permission[1];
        //     if($action == "Added"){
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'New Permission added successfully',
        //         ]);
        //     }else{
        //         return response()->json([
        //             'success' => true,
        //             'message' => 'Permission updated successfully',
        //         ]);
        //     }
           
        // }
    }
    public function editPermission(Request $request)
    {
        return PermissionHelper::editPermission($request);
    }
    public function permissionDelete(Request $request)
    {
        return PermissionHelper::permissionDelete($request);
    }
    public function permissionSettings(Request $request)
    {
        $id = $request->id;
        $userPermission = UserPermission::where('user_id',$id)->with('permissions')->get();
        $table_html = PermissionHelper::assignedPermissionsTable($userPermission);
        $ids = (clone $userPermission)->pluck('permission_id')->toArray();
        $permissions = Permission::whereNotIN('id',$ids)->get();
        $option = '';
        $option .= '<option value="" selected>Select Permissions</option>';  
        foreach($permissions as $value){
        $option .= '<option value="'.$value->id.'">'.$value->name.'</option>';  
        }
        return response()->json(['success' => true, 'response' => $option,'table_html'=>$table_html]);
    }
    public function storeAssignPermissions(Request $request)
    {
        $userPermission = PermissionHelper::storeAssignPermissions($request->all());
        if ($userPermission) {
            return response()->json([
                'success' => true,
            ]);
            }else{
            return response()->json([
                'success' => false,
            ]);
        }
    }
    public function userPermissionDelete(Request $request)
    {
        return PermissionHelper::userPermissionDelete($request);
    }
    public function getPermissions()
    {
        $permissions = Permission::get();
        $option = '';
        $option .= '<option value="" selected>Select Permissions</option>';  
        foreach($permissions as $value){
        $option .= '<option value="'.$value->id.'">'.$value->name.'</option>';  
        }
        return response()->json(['success' => true, 'response' => $option]);
    }
}
