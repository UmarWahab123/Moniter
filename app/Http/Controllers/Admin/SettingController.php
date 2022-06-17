<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use Auth;
use App\Helpers\SettingsHelper;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        return view('admin.settings.index', compact('setting'));
    }
    public function store(Request $request)
    {
        return SettingsHelper::store($request);
    }
}
