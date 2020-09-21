<?php

namespace App\Http\Controllers\User;
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
use App\WebsiteLog;
class WebsiteController extends Controller
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
                $html_string =' <a  href='.url("admin/website-logs/$item->id").' value="'.$item->id.'"  class="btn btn-info btn-sm"  title="Details"><i class="fa fa-eye text-white"></i></a>';
                $html_string .=' <button  value="'.$item->id.'" data-emails="'.$item->getSiteDetails->emails.'" data-ssl="'.$item->certificate_check_enabled.'"  class="btn btn-primary btn-sm edit-site "  title="Edit"><i class="fa fa-pencil"></i></button>';
                $html_string.=' <button  value="'.$item->id.'"  class="btn btn-danger btn-sm delete-site"  title="Delete"><i class="fa fa-trash-o"></i></button>';
                                                     
                    
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
                if($item->uptime_last_check_date==null)
                return '--';
                return $item->uptime_last_check_date;
             })
             ->addColumn('certificate_expiry_date', function ($item) {
                if($item->certificate_expiration_date==null)
                return '--';
                return $item->certificate_expiration_date;
             })
             ->addColumn('certificate_check', function ($item) {
                if($item->certificate_check_enabled)
                {
                    return '<span class="badge badge-success ">ON</span>';
                }
                else
                {
                    return '<span class="badge badge-danger ">OFF</span>';
                }
                return $item->certificate_check_enabled;
             })
             ->addColumn('certificate_issuer', function ($item) {
                 if($item->certificate_issuer==null)
                 return '--';
                return $item->certificate_issuer;
             })
             
             ->addColumn('url', function ($item) {
                return $item->url;
             })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action','status','certificate_check'])
            ->make(true);
        }
        return view('User.websites.index',compact('websites'));
        
    }
    public function store(Request $request)
    {
        //dd($request->all());
        
        
        $validator = $request->validate([
            'url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'title' => 'required',
        ]);
        
            $mailData=$request->all();
            define('STDIN',fopen("php://stdin","r"));
            $output=Artisan::call("monitor:create ".$request->url);
            $websites=Monitor::get();
            $url=Url::fromString($request->url);
            $ssl=null;
            foreach($websites as $web)
            {
               // dd($web->url->getHost(),$url->getHost());
               $webUrl=Url::fromString($web->url);
                if($webUrl->getHost()==$url->getHost())
                {
                    $uweb=new UserWebsite();
                    $uweb->website_id=$web->id;
                    $uweb->user_id=Auth::user()->id;
                    $uweb->title=$request->title;
                    $uweb->emails=$request->emails;
                    if(isset($request->ssl))
                    {
                        $ssl=1;
                        $mailData['ssl']="True";
                    }
                    else
                    {
                        $ssl=0;
                        $mailData['ssl']="False";

                    }
                    $uweb->ssl=$ssl;
                    $uweb->save();
                    Monitor::where('id',$web->id)->update(['certificate_check_enabled'=>$ssl]);
                    $mails=$request->emails;
                    if($mails!=null)
                    {

                        $mails=explode(",",$request->emails);
                        foreach($mails as $mail)
                        {
                            Mail::to($mail)->send(new SiteStatusMail($mailData)); 
                        }
                    }
                    else
                    {
                        $setting=Setting::where('type','email')->first();
                        if($setting==null)
                        {
                            $default_mail=config('uptime-monitor.notifications.mail.to');
                            if($default_mail!=null)
                            {
                                Mail::to($default_mail[0])->send(new SiteStatusMail($mailData));
                            }
                        }
                        else
                        {
                            Mail::to($setting->settings)->send(new SiteStatusMail($mailData));
                        }
                       
                    }
                    return response()->json(['success'=>true]);
                }
            }
            return response()->json(['success'=>false]);
    }

    public function destroy(Request $request)
    {
        // $website=Monitor::find($request->id);
        // if($website!=null)
        // {
        //     define('STDIN',fopen("php://stdin","r"));
        //     $output=Artisan::call("monitor:delete ".$website->url);
        //     dd($output);
        //     return response()->json(['success'=>true]);
        // }
        $output=Monitor::where('id',$request->id)->delete();
        if($output>0)
        {
            UserWebsite::where('website_id',$request->id)->delete();
            WebsiteLog::where('website_id',$request->id)->delete();
            return response()->json(['success'=>true]);

        }
        
    }

    public function update(Request $request)
    {
        $monitor=Monitor::find($request->id);
        $ssl=0;
        if(isset($request->ssl))
        {
            $ssl=1;
        }
        $monitor->certificate_check_enabled=$ssl;
        if($monitor->save())
        {
            UserWebsite::where('website_id',$request->id)->update(['emails'=>$request->emails,'title'=>$request->title]);
            return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false]);
    }

    public function websiteLogs($id)
    {
        dd($id);
    }
}
