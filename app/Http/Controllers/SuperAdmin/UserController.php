<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function __construct()
    {
        if (Auth::user()) {
            $user_permissions = unserialize(Auth::user()->permissions);
            View::share(['user_permissions' => $user_permissions]);
        }
    }
    public function store(Request $request)
    {
        return UserHelper::store($request);
    }
    public function index()
    {
        return view('superAdmin.users.index');
    }
    public function getData(Request $request)
    {
        return UserHelper::UserDatatable($request);
    }
    public function userStatus(Request $request)
    {
        return UserHelper::userStatus($request);
    }
    public function delete(Request $request)
    {
        return UserHelper::delete($request);
    }
}
