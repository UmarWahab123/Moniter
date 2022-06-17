<?php

namespace App\Helpers;

use App\User;
use Auth;

class ProfileHelper
{

    public static function update($request)
    {
        if ($request->password != null) {
            $request->validate([
                'name' => 'string',
                'password' => '|string|min:6|confirmed',
            ]);
        } else {
            $request->validate([
                'name' => 'string',
            ]);
        }
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return response()->json(['success' => true]);
    }
}
