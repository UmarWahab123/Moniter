<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Monitor;
use App\Server;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name')->where('role_id', 1)->get();
        return view('superAdmin.dashboard', compact('users'));
    }

    public function getUsersTotalRecords(Request $request)
    {
        $total_servers = Server::where('user_id', $request->user_id)->count();
        $total_websites = Monitor::where('user_id', $request->user_id)->count();
        $total_users = User::where('parent_id', $request->user_id)->count();
        return response()->json(['success' => true, 'total_users' => $total_users, 'total_servers' => $total_servers, 'total_websites' => $total_websites]);
    }
}
