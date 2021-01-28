<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
class ProfileController extends Controller
{
    public function index()
    {
        $profile=User::where('id',Auth::user()->id)->first();
        return view('admin.profile.index',compact('profile'));
    }
    public function update(Request $request)
    {
        if($request->password != null)
        {
            $request->validate([
                'name'=>'string',
                'password'=>'|string|min:6|confirmed',
            ]);
        }
        else
        {
            $request->validate([
                'name'=>'string',
            ]);
        }

        $user=User::find(Auth::user()->id);
        $user->name=$request->name;
        if($request->password != null)
        {
            $user->password=bcrypt($request->password);
        }
        $user->save();
        return response()->json(['success'=>true]);
    }
}
