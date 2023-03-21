<?php

namespace App\Helpers;

use App\User;
use App\Models\UserDetail;
use Auth;
use Hash;

class ProfileHelper
{

    public static function update($request)
    {
        $request->validate([
            'firt_name' => 'string',
            'last_name' => 'string',
        ]);
        $id = $request->id;
        $data = $request->all();
        $userDetail = UserDetail::find($id)->update($data);
        return response()->json(['success' => true]);
    }
    public static function changePassword($request)
    {
        //to check whether new password and confirmed password are same or not
        if($request->password != $request->password_confirmation){
            return response()->json(['success' => false, 'msg' => 'New password and Confirm password does not match']);
        }
        
        //to check old password is correct or not
        if (!Hash::check($request->old_password, auth()->user()->password))
        {
            return response()->json(['success' => false, 'msg' => 'Old password is incorrect']);
        }

        //update user password
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['success' => true, 'msg' => 'Password updated successfully !!!']);
    }
}
