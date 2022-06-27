<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Setting;
use Illuminate\Http\Request;
use App\Helpers\SettingsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class SettingController extends Controller
{
    public function __construct()
    {
        if (Auth::user()) {
            $user_permissions = unserialize(Auth::user()->permissions);
            View::share(['user_permissions' => $user_permissions]);
        }
    }
    public function index()
    {
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        return view('user.settings.index', compact('setting'));
    }
    public function store(Request $request)
    {
        return SettingsHelper::store($request);
    }
}
