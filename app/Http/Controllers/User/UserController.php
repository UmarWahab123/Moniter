<?php

namespace App\Http\Controllers\User;

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
    public function index(Request $request)
    {
        return UserHelper::index($request);
    }
    public function store(Request $request)
    {
        return UserHelper::store($request);
    }
    public function userStatus(Request $request)
    {
        return UserHelper::userStatus($request);
    }
    public function update(Request $request)
    {
        return UserHelper::update($request);
    }
    public function resendEmail(Request $request)
    {
        return UserHelper::resendEmail($request);
    }
    public function sendVerificationCodeEmail(Request $request)
    {
        return UserHelper::sendVerificationCodeEmail($request);
    }
    public function userPermissions($id)
    {
        return UserHelper::userPermissions($id);
    }
    public function saveUserPermissions(Request $request)
    {
        return UserHelper::saveUserPermissions($request);
    }
}
