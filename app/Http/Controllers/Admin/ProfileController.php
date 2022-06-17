<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Helpers\ProfileHelper;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = User::where('id', Auth::user()->id)->first();
        return view('admin.profile.index', compact('profile'));
    }

    public function update(Request $request)
    {
        return ProfileHelper::update($request);
    }
}
