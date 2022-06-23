<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function authenticated($request, $user)
    {
        if ($user->verification_code != $request->verification_code) {
            return response()->json(['success' => false]);
        }
        $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();
        if (Auth::user()->role_id == 1 && Auth::user()->status == 1) {
            return redirect('/admin/home');
        } elseif (Auth::user()->role_id == 2 && Auth::user()->status == 1) {
            return redirect('/user/home');
        } else {
            Auth::logout();
            return redirect('/login');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
