<?php

namespace App\Http\Controllers\Admin;

use Auth;
use DateTime;
use App\Monitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $q->where('is_featured', 1);
        })->with('getSiteDetails', 'getSiteLogs',)->get();
        return view('admin.index', compact('monitors'));
    }
}
