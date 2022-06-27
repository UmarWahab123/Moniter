<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function doLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',   // required and email format validation
            'password' => 'required', // required and number field validation

        ]); // create the validations
        if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        {
            return response()->json($validator->errors(), 422);
            // validation failed return with 422 status

        } else {
            //validations are passed try login using laravel auth attemp
            if (Auth::attempt(
                $request->only(["email", "password"]),
                $request->filled('remember')
            )) {
                if (Auth::user()->remember_token == null && Auth::user()->verification_code != $request->verification_code) {
                    return response()->json(['success' => false]);
                }
                $user = User::find(Auth::user()->id);
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
            } else {
                return response()->json([["Invalid credentials"]], 422);
            }
        }
    }
    public function createVerfication(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $checkbox = $request->checkbox;
        return view('auth.verifyLogin', compact('email', 'password', 'checkbox'));
    }
}
