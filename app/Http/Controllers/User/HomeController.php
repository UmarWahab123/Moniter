<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Monitor;
use Illuminate\Support\Facades\Auth;

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
