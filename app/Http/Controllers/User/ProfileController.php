<?php

namespace App\Http\Controllers\User;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ProfileHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
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
        $profile = User::where('id', Auth::user()->id)->first();
        return view('user.profile.index', compact('profile'));
    }
    public function update(Request $request)
    {
        return ProfileHelper::update($request);
    }
}
