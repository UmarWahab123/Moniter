<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Helpers\DeviceManagementHelper;

class DeviceManagementController extends Controller
{
    public function __construct()
    {
        if (Auth::user()) {
            $user_permissions = unserialize(Auth::user()->permissions);
            View::share(['user_permissions' => $user_permissions]);
        }
    }
    public function index(Request $request)
    {
        return DeviceManagementHelper::index($request);
    }

    public function deviceLogout(Request $request)
    {
        return DeviceManagementHelper::deviceLogout($request);
    }
}
