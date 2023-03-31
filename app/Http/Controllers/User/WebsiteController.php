<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Monitor;
use App\WebsiteLog;
use Illuminate\Http\Request;
use App\Helpers\WebsiteHelper;
use App\Helpers\WebsiteLogHelper;
use App\Http\Controllers\Controller;
use App\Server;
use App\User;
use App\UserWebsite;
use Illuminate\Support\Facades\View;

class WebsiteController extends Controller
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
        $query = Monitor::with('getSiteDetails', 'UserWebsitePivot');
        $query = $query->whereHas('getUserWebsites', function ($q) {
            $q->where('user_id', Auth::user()->id);
        });
        $query = $query->orWhereHas('UserWebsitePivot', function ($q) {
            $q->where('user_id', Auth::user()->id);
        });
        $query = $query->get();
        $websites = $query;
        if ($request->ajax()) {
            return WebsiteHelper::WebsitesDatatable($query);
        }
        $ids = User::where('id', auth()->user()->parent_id)->orWhere('id', auth()->user()->id)->pluck('id')->toArray();

        $no_of_servers_allowed = @auth()->user()->package->no_of_websites;
        $user_servers_added = UserWebsite::whereIn('user_id', $ids)->count();
        $website_Permission_id = @auth()->user()->userpermissions->where('type','add-website')->first();
        $user_permission_to_add_website = @$website_Permission_id->permission_id;
        $servers = Server::select('id', 'name')->where('user_id', Auth::user()->parent_id)->get();
        return view('user.websites.index', compact('websites', 'servers','user_permission_to_add_website','no_of_servers_allowed','user_servers_added'));
    }
    public function store(Request $request)
    {
        return WebsiteHelper::store($request);
    }

    public function destroy(Request $request)
    {
        return WebsiteHelper::destroy($request);
    }
    public function edit(Request $request)
    {
        return WebsiteHelper::edit($request);
    }
    public function update(Request $request)
    {
        return WebsiteHelper::update($request);
    }

    public function websiteLogs(Request $request, $id)
    {
        return WebsiteLogHelper::websiteLogs($request, $id);
    }

    public function featureWebsite(Request $request)
    {
        return WebsiteHelper::featureWebsite($request);
    }

    public function getDownReason(Request $request)
    {
        $down_reason = WebsiteLog::where('id', $request->id)->value('down_reason');
        return response()->json(['success' => true, 'down_reason' => $down_reason]);
    }

    public function getDownReasonImage(Request $request)
    {
        $down_image_url = WebsiteLog::where('id', $request->id)->value('down_image_url');
        $html_string = '<img src="' . $down_image_url . '" alt="">';
        return response()->json(['success' => true, 'html_string' => $html_string]);
    }
}
