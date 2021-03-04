<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserToken;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Yajra\DataTables\Facades\DataTables;

class DeviceManagementController extends Controller
{
    public function index(Request $request)
    {
        $query=UserToken::query();
        if($request->ajax())
        {

            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '
                          <button  value="'.$item->id.'"  class="btn btn-outline-info btn-sm logout-btn"  title="Logout"><i class="fa fa-sign-out"></i></button>                          
                     ';
                return $html_string;
            })
            ->addColumn('device_name', function ($item) {
                return $item->device_name;
             })
             ->addColumn('logged_in', function ($item) {
                return $item->created_at;
             })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.devices.index');
    }

    public function deviceLogout(Request $request)
    {
        $device_id=$request->device_id;
        $userToken=UserToken::find($device_id);
        if($userToken)
        {
            try {
                JWTAuth::invalidate($userToken->jwt_token);
                // UserToken::where('id',$device_id)->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'User logged out successfully'
                ]);
            } catch (JWTException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, the user cannot be logged out'
                ], 500);
            }
        }
       
    }

}
