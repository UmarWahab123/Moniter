<?php

namespace App\Http\Controllers\User;
use App\User;
use App\Monitor;
use App\Models\UserPermission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::user()) {
            $user_permissions = unserialize(Auth::user()->permissions);
            View::share(['user_permissions' => $user_permissions]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {     
        $monitors = Monitor::whereHas('getUserWebsites', function ($q) {
            $q->where('is_featured', 1)->where('user_id', Auth::user()->id);
        })->with('getSiteDetails', 'getSiteLogs')->get();
        return view('user.index', compact('monitors'));
    }
}
