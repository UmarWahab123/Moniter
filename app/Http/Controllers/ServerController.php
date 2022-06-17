<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\ServersHelper;
use App\Helpers\ServerLogsHelper;

class ServerController extends Controller
{
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
