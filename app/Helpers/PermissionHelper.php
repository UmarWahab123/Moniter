<?php

namespace App\Helpers;

use App\Models\Permission;
use App\Models\UserPermission;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    //Permission Start from here
    public static function permission($request)
    {
        if ($request->ajax()) {
            return (new PermissionHelper)->PermissionDatatable();
        }
        return view('admin.permissions.index');
    }
    private function PermissionDatatable()
    {
        $query = Permission::all();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '
                <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm edit-permission"  title="Edit"><i class="fa fa-pencil"></i></button>
                <button  value="' . $item->id . '" class="btn btn-sm btn-outline-danger btn_delete ml-1" title="Delete Permision"><i class="fa fa-trash"></i></button>
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
    public static function storePermission($request)
    {
        $id = $request['id'];
        $data = $request;
        $action = "Added";
        if ($id) {
            $action = "Updated";
            $permission = Permission::find($id)->update($data);
        } else {
            $permission = Permission::create($data);
        }
        $permission = [$permission, $action];
        return $permission; 
    }
    public static function editPermission($request)
    {
        $permission = Permission::where('id', $request->id)->first();
        if ($permission != null) {
            return response()->json(['success' => true, 'data' => $permission]);
        }
        return response()->json(['success' => false]);
    }
    public static function permissionDelete($request)
    {
        $systemFeature = Permission::find($request->id)->delete();
         return response()->json(['success' => true]);
    }
    public static function storeAssignPermissions($request)
    {
        $data = $request;
        $userPermission = UserPermission::create($data);
        return $userPermission;
       
    }
    public static function assignedPermissionsTable($data){
        $html = '
        <table id="assigned-permission-to-user" class="table text-center table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>S.No</th>
                    <th>Permission Name</th>
                    <th>Action</th>
                </tr>
            </thead><tbody>';
            foreach($data as $key => $permission){
                $html .= '<tr>
                    <td>'.($key+1).'</td>
                    <td>'.@$permission->permissions->name.'</td>
                    <td><button  value="' . @$permission->id . '" class="btn btn-sm btn-outline-danger assigned_permision_delete ml-1" title="Delete Assigned Permission"><i class="fa fa-trash"></i></button></td>
                </tr>';
            }
        $html .= '</tbody></table>';

        return $html;
    }
    public static function userPermissionDelete($request)
    {
         $userPermission = UserPermission::find($request->id)->delete();
         return response()->json(['success' => true]);
    }
}
