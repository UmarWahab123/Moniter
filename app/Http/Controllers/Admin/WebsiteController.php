<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WebsiteLog;
use App\Helpers\WebsiteHelper;
use App\Helpers\WebsiteLogHelper;

class WebsiteController extends Controller
{
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

    public function websiteLogs(Request $request, $website_id)
    {
        return WebsiteLogHelper::websiteLogs($request, $website_id);
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
