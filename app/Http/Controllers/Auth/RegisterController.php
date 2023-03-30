<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'same:password_confirmation'],
            'password_confirmation' => ['required', 'min:6'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => 1,
            'role_id' => 1,
            'package_id' => 1,
            'password' => Hash::make($data['password']),
        ]);
    }
    public function register_user(Request $request){
        $id = $request->id;
        $data = $request->all();
        $email_exist=User::where('email',$request->email)->first();
        if(!empty($email_exist)){
         return response()->json(['success' => false, 'msg' => 'Email is already exist ! Try a valid email']);
        }
        if($request->password != $request->password_confirmation){
            return response()->json(['success' => false, 'msg' => 'Password and Confirm password does not match']);
        }
        if(!empty($data['password'])){
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }
        $data['role_id'] = 2;
        $data['status'] = 1;
        $data['package_id'] = 1;
        $user = User::create($data);
        return response()->json(['success' => true, 'msg' => 'You Have Successufully Registerd !']);
 }

}
