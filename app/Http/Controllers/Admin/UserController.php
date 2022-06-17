<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\UserHelper;

class UserController extends Controller
{
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
}
