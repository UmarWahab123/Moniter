<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
class SettingController extends Controller
{
    public function index()
    {
        $setting=Setting::first();
        return view('admin.settings.index',compact('setting'));
    }
    public function store(Request $request)
    {
        $check=Setting::where('type','email')->first();
        if($check==null)
        {
            $setting = new Setting();
            $setting->type='email';
            $setting->settings=$request->email;
            $setting->save();
            return response()->json(['success'=>true,'msg'=>'Setting saved successfully']);
        }
        else
        {
            Setting::where('type','email')->update(['settings'=>$request->email]);
            return response()->json(['success'=>true,'msg'=>'Setting updated successfully']);
        }
    }
}
