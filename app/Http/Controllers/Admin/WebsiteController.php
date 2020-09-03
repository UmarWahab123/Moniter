<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artisan;
use Auth;
use Spatie\Url\Url;
use App\UserWebsite;
use Illuminate\Support\Facades\Mail;
use App\Mail\SiteStatusMail;
use Yajra\Datatables\Datatables;
//use Spatie\UptimeMonitor\Models\Monitor;
use App\Monitor;

class WebSiteController extends Controller
{
    public function index(Request $request)
    {

        $query=Monitor::whereHas('getUserWebsites',function($q){
            $q->where('user_id',Auth::user()->id);
        })->get();
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
            ->addColumn('title', function ($item) {
                if($item->getSiteDetails!=null)
                return $item->getSiteDetails->title;
                else
                return '--';
             })
            ->addColumn('status', function ($item) {
                if($item->uptime_status=='up')
                $html= '<span class="badge badge-success ">Up</span>';
                else if($item->uptime_status=='down')
                $html='<span class="badge badge-danger ">Down</span>';
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
             ->addColumn('url', function ($item) {
                return $item->url;
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
        //dd($request->all());
        
        $validator = $request->validate([
            'url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'title' => 'required',
            'emails' => 'required',
        ]);
            $mailData=$request->all();
            define('STDIN',fopen("php://stdin","r"));
            $output=Artisan::call("monitor:create ".$request->url);
            $websites=Monitor::get();
            $url=Url::fromString($request->url);
            
            foreach($websites as $web)
            {
               // dd($web->url->getHost(),$url->getHost());
               $webUrl=Url::fromString($web->url);
                if($webUrl->getHost()==$url->getHost())
                {
                    $uweb=new UserWebsite();
                    $uweb->website_id=$web->id;
                    $uweb->user_id=Auth::user()->id;
                    $uweb->title='ABC';
                    $uweb->emails=$request->emails;
                    if(isset($request->ssl))
                    $uweb->ssl=1;
                    else
                    $uweb->ssl=0;
                    $uweb->save();
                    $mails=explode(",",$request->emails);
                    Mail::to($mails[0])->send(new SiteStatusMail($mailData)); 
                    return response()->json(['success'=>true]);
                }
            }
            return response()->json(['success'=>false]);

    }
}
