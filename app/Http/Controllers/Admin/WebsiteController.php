<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Aritsan;
use Auth;
use App\Monitor;
use Yajra\Datatables\Datatables;

class WebsiteController extends Controller
{
    public function index(Request $request)
    {
        $query=Monitor::get();
        $websites=$query;
        if($request->ajax())
        {
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string = '<div class="icons">'.'
                          <a href="javascript:void(0);" data-id="'.$item->id.'"  class="actionicon tickIcon edit-icon" title="Edit"><i class="fa fa-pencil"></i></a>
                          <a href="javascript:void(0);" class="actionicon deleteIcon delete-menu" data-id="'.$item->id.'" data-menu_name="'.$item->title.'" title="Delete"><i class="fa fa-ban"></i></a>
                      </div>';
                return $html_string;
            })
            ->addColumn('status', function ($item) {
                if($item->uptime_status=='up')
                $html= '<span class="badge badge-success col-3">Up</span>';
                else if($item->uptime_status=='down')
                $html='<span class="badge badge-danger col-3">Down</span>';
                else
                $html='<span class="badge badge-info">'.$item->uptime_status.'</span>';

                return $html;
                
            })
            ->addColumn('status_change_on', function ($item) {
               return $item->uptime_status_last_change_date;
            })
            ->addColumn('last_status_check', function ($item) {
                return $item->uptime_last_check_date;
             })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
        return view('admin.websites.index',compact('websites'));
        
    }
    public function store(Request $request)
    {
        define('STDIN',fopen("php://stdin","r"));
        $output=Artisan::call("monitor:create ".$request->url);
        return response()->json(['success'=>true]);
    }
}
