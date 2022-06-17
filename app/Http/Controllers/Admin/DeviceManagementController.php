<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\DeviceManagementHelper;

class DeviceManagementController extends Controller
{
    public function index(Request $request)
    {
        return DeviceManagementHelper::index($request);
    }

    public function deviceLogout(Request $request)
    {
        return DeviceManagementHelper::deviceLogout($request);
    }
}
