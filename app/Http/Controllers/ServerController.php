<?php

namespace App\Http\Controllers;

use App\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\ServersHelper;
use App\Helpers\ServerLogsHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ServerController extends Controller
{
    public function __construct()
    {
        if (Auth::user()) {
            $user_permissions = unserialize(Auth::user()->permissions);
            View::share(['user_permissions' => $user_permissions]);
        }
    }
    public function index()
    {
        return view('servers.index');
    }

    public function dashboard()
    {
        return ServersHelper::dashboard();
    }

    public function getServers(Request $request)
    {
        return ServersHelper::getServers($request);
    }

    public function addServer(Request $request)
    {
        return ServersHelper::addServer($request);
    }

    public function serverLogs($id)
    {
        return ServerLogsHelper::serverLogs($id);
    }

    public function serverLogsInDetails(Request $request)
    {
        return ServerLogsHelper::serverLogsInDetails($request);
    }

    public function getLatestServerLogs(Request $request)
    {
        return ServerLogsHelper::getLatestServerLogs($request);
    }

    public function destroy(Request $request)
    {
        return ServersHelper::destroy($request);
    }

    public function edit(Request $request)
    {
        return ServersHelper::edit($request);
    }

    public function update(Request $request)
    {
        return ServersHelper::update($request);
    }
}
