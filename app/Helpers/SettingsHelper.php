<?php

namespace App\Helpers;

use App\Setting;
use Auth;

class SettingsHelper
{
    public static function store($request)
    {
        $check = Setting::where('type', 'email')->where('user_id', Auth::user()->id)->first();
        if ($check == null) {
            $setting = new Setting();
            $setting->type = 'email';
            $setting->user_id = Auth::user()->id;
            $setting->settings = $request->email;
            $setting->save();
            return response()->json(['success' => true, 'msg' => 'Setting saved successfully']);
        } else {
            Setting::where('type', 'email')->where('user_id', Auth::user()->id)->update(['settings' => $request->email]);
            return response()->json(['success' => true, 'msg' => 'Setting updated successfully']);
        }
    }
}
