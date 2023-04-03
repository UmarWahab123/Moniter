<?php

namespace App\Http\Controllers;

use App\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\ServersHelper;
use App\Helpers\ServerLogsHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\OperatingSystem;
use App\UserWebsite;
use App\User;

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
        $no_of_servers_allowed = @auth()->user()->package->no_of_servers;
        if(auth()->user()->role_id==1){
        $ids = User::where('id', auth()->user()->id)->orWhere('parent_id', auth()->user()->id)->pluck('id')->toArray();
        }else{
        $ids = User::where('id', auth()->user()->parent_id)->orWhere('parent_id', auth()->user()->parent_id)->pluck('id')->toArray();
        }
        $user_servers_added = Server::whereIn('user_id', $ids)->count();
        // dd($no_of_servers_allowed, $user_servers_added);
        $server_permission_id = @auth()->user()->userpermissions->where('type','add-server')->first();
        $user_permission_to_add_server = @$server_permission_id->permission_id;
        return view('servers.index', compact('no_of_servers_allowed', 'user_servers_added','user_permission_to_add_server'));
    }
    public function getOperatingSystem()
    {
        $operatingSystem = OperatingSystem::get();
        $option = '';
        $option .= '<option value=""  selected="">Choose OS</option>';  
        foreach($operatingSystem as $value){
        $option .= '<option value="'.$value->id.'">'.$value->name.'</option>';  
        }
        return response()->json(['success' => true, 'response' => $option]);
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

    public function bindedWebsites(Request $request)
    {
        return ServersHelper::bindedWebsites($request);
    }

    public function getBindedWebsites(Request $request)
    {
        return ServersHelper::getBindedWebsites($request);
    }

    public function saveBindedWebsites(Request $request)
    {
        return ServersHelper::saveBindedWebsites($request);
    }
    public function userAddedWebsite(Request $request)
    {
        $user_id = $request->user_id;
        $userWebsite = UserWebsite::where('user_id',$user_id)->get();
        $table_html = ServersHelper::userAddedWebsiteTable($userWebsite);
        return response()->json(['success' => true,'table_html' => $table_html]);
    }
    public function serverWebsiteDelete(Request $request)
    {
        return ServersHelper::serverWebsiteDelete($request);
    }
    public function websiteAssignStatusChange(Request $request)
    {
        return ServersHelper::websiteAssignStatusChange($request);
    }
    
}
