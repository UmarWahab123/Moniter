<?php

namespace App\Helpers;

use Yajra\Datatables\Datatables;
use App\WebsiteLog;
use App\Monitor;
use Auth; 
class WebsiteLogHelper
{
    public static function websiteLogs($request, $id)
    {
        $website_id = $id;
        $website = Monitor::with('getSiteDetails', 'getSiteLogs')->where('id', $website_id)->first();
        if ($request->ajax()) {
            return (new WebsiteLogHelper)->WebsitesLogDatatable($website_id);
        }
        if(Auth::user()->role_id==1){
            return view('admin.websites.website-details', compact('website_id', 'website'));
        }else{
            return view('user.websites.website-details', compact('website_id', 'website'));

        }
    }

    private function WebsitesLogDatatable($website_id)
    {
        $query = WebsiteLog::where('website_id', $website_id)->orderBy('created_at', 'desc');
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = ' <a  href=' . url("admin/website-logs/$item->id") . ' value="' . $item->id . '"  class="btn btn-info btn-sm"  title="Details"><i class="fa fa-eye text-white"></i></a>';
                $html_string .= ' <button  value="' . $item->id . '"  class="btn btn-danger btn-sm delete-site"  title="Delete"><i class="fa fa-trash-o"></i></button>';
                return $html_string;
            })
            ->addColumn('down_time', function ($item) {
                return $item->down_time != null ? $item->down_time : '--';
            })
            ->addColumn('up_time', function ($item) {
                return $item->up_time != null ? $item->up_time : '--';
            })
            ->addColumn('down_reason', function ($item) {
                if ($item->down_reason != null) {
                    $html_string = '<a class="down-reason" data-id="' . $item->id . '" href="javascript:void(0)">' . strstr($item->down_reason, ":", true) . '</a>';
                } else {
                    $html_string = '--';
                }
                return $html_string;
            })
            ->addColumn('down_reason_image', function ($item) {
                if ($item->down_image_url != null) {
                    $html_string = '<button data-id="' . $item->id . '" class="view-image btn btn-primary btn-sm"><i class="fa fa-eye"></i></button>';
                } else {
                    $html_string = '--';
                }
                return $html_string;
            })
            ->rawColumns(['down_reason', 'down_reason_image'])
            ->make(true);
    }
}
