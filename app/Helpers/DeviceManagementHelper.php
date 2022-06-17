<?php

namespace App\Helpers;

use App\UserToken;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Yajra\DataTables\Facades\DataTables;

class DeviceManagementHelper
{

    public static function index($request)
    {
        if ($request->ajax()) {
            return (new DeviceManagementHelper)->DMDatatable();
        }
        return view('admin.devices.index');
    }

    private function DMDatatable()
    {
        $query = UserToken::query();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '
                          <button  value="' . $item->id . '"  class="btn btn-outline-info btn-sm logout-btn"  title="Logout"><i class="fa fa-sign-out"></i></button>
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

    public static function deviceLogout($request)
    {
        $device_id = $request->device_id;
        $userToken = UserToken::find($device_id);
        if ($userToken) {
            try {
                JWTAuth::invalidate($userToken->jwt_token);
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
