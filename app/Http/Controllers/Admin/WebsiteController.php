<?php

namespace App\Http\Controllers\Admin;

use App\WebsiteLog;
use Illuminate\Http\Request;
use App\Helpers\WebsiteHelper;
use App\Helpers\WebsiteLogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        return WebsiteHelper::index($request);
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

    public function assignWebsiteToSubUser(Request $request)
    {
        return WebsiteHelper::assignWebsiteToSubUser($request);
    }

    public function showAssignedUser(Request $request)
    {
        return WebsiteHelper::showAssignedUser($request);
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
