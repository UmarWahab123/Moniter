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
use App\WebsiteLog;
use App\Monitor;
use App\Setting;
use Config;
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
                if($item->getSiteDetails!=null)
                {
                    $html_string=null;
                    if($item->getSiteDetails->is_featured==1)
                    {
                        $html_string =' <button   value="'.$item->id.'" data-status="0" class="btn btn-outline-secondary btn-sm feature"  title="Click to unfetaure"><i class="fa fa-star "></i></button>';
                    }
                    else
                    {
                        $html_string =' <button   value="'.$item->id.'" data-status="1" class="btn btn-outline-success btn-sm feature"  title="Click to fetaure"><i class="fa fa-star "></i></button>';
                    }
                }
                $html_string .=' <button  value="'.$item->id.'" data-developer_email="'.$item->getSiteDetails->developer_email.'" data-emails="'.$item->getSiteDetails->emails.'" data-emails="'.$item->getSiteDetails->emails.'" data-ssl="'.$item->certificate_check_enabled.'"  class="btn  btn-outline-primary btn-sm edit-site "  title="Edit"><i class="fa fa-pencil"></i></button>';
                $html_string .=' <a  href='.url("admin/website-logs/$item->id").' value="'.$item->id.'"  class="btn btn-outline-info btn-sm"  title="Details"><i class="fa fa-eye "></i></a>';
                $html_string.=' <button  value="'.$item->id.'"  class="btn btn-outline-danger btn-sm delete-site"  title="Delete"><i class="fa fa-trash-o"></i></button>';
                                                     
                    
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
                if($item->certificate_check_enabled==1)
                {
                    return '<span class="badge badge-success ">ON</span>';
                }
                else
                {
                    return '<span class="badge badge-danger ">OFF</span>';
                }
             })
             ->addColumn('certificate_issuer', function ($item) {
                 if($item->certificate_issuer==null)
                 return '--';
                return $item->certificate_issuer;
             })
             
             ->addColumn('url', function ($item) {
                return $item->url;
             })
             ->addColumn('domain_creation_date', function ($item) {
                return( $item->domain_creation_date != null)?$item->domain_creation_date:'--';
             })
             ->addColumn('domain_updated_date', function ($item) {
                return ($item->domain_updated_date != null)?$item->domain_updated_date:'--';
             })
             ->addColumn('domain_expiry_date', function ($item) {
                return ($item->domain_expiry_date != null)?$item->domain_expiry_date:'--';
             })
             ->addColumn('url', function ($item) {
                return $item->url;
             })

             ->addColumn('reason', function ($item) {
                return ($item->uptime_check_failure_reason!=null)?$item->uptime_check_failure_reason:'--';
             })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action','status','certificate_check'])
            ->make(true);
        }
        return view('admin.websites.index',compact('websites'));
        
    }
    public function store(Request $request)
    {
        //dd($request->all());
        
        if($request->emails != null || $request->owner_email != null || $request->developer_email != null)
        {
            $validator = $request->validate([
                // 'url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                'url' => 'required|url',
                'title' => 'required',
                'emails' => 'email',
            ]);
        }
        else
        {
            $validator = $request->validate([
                // 'url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                'url' => 'required|url',
                'title' => 'required',
            ]);
        }

        
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
                    $uweb->developer_email=$request->developer_email;
                    $uweb->owner_email=$request->owner_email;
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
    public function edit(Request $request)
    {
        $monitor=Monitor::find($request->website_id);
        if($monitor != null)
        {
            $data['title']=$monitor->getSiteDetails->title;
            $data['emails']=$monitor->getSiteDetails->emails;
            $data['developer_email']=$monitor->getSiteDetails->developer_email;
            $data['owner_email']=$monitor->getSiteDetails->owner_email;
            $data['ssl']=$monitor->getSiteDetails->ssl;
            return response()->json(['success'=>true,'data'=>$data]);
        }
        return response()->json(['success'=>false]);

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
            UserWebsite::where('website_id',$request->id)->update(['emails'=>$request->emails,'title'=>$request->title,'developer_email'=>$request->developer_email,'owner_email'=>$request->owner_email]);
            return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false]);
    }

    public function websiteLogs(Request $request,$website_id)
    {
        $website=Monitor::where('id',$website_id)->first();
        if($request->ajax())
        {
            $query=WebsiteLog::where('website_id',$website_id)->orderBy('created_at','desc');
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $html_string =' <a  href='.url("admin/website-logs/$item->id").' value="'.$item->id.'"  class="btn btn-info btn-sm"  title="Details"><i class="fa fa-eye text-white"></i></a>';
                $html_string.=' <button  value="'.$item->id.'"  class="btn btn-danger btn-sm delete-site"  title="Delete"><i class="fa fa-trash-o"></i></button>';
                                                     
                    
                return $html_string;
            })
            ->addColumn('down_time',function($item){
                if($item->down_time!=null)
                return $item->down_time;
                return '--';
            })
            ->addColumn('up_time',function($item){
                if($item->up_time!=null)
                return $item->up_time;
                return '--';
            })
            ->addColumn('down_reason',function($item){
                if($item->down_reason!=null)
                {
                    $html_string = '<a class="down-reason" data-id="'.$item->id.'" href="javascript:void(0)">'.strstr($item->down_reason,":",true).'</a>';
                }
                else
                {
                    $html_string = '--';
                }
                return $html_string;
            })
            ->rawColumns(['down_reason'])
            ->make(true);
        }
        return view('admin.websites.website-details',compact('website_id','website'));
    }

    public function featureWebsite(Request $request)
    {
        $count=UserWebsite::where('user_id',Auth::user()->id)->where('is_featured',1)->count();
        if($request->status==1)
        {
            if($count < 10)
            {

                UserWebsite::where('id',$request->id)->update(['is_featured'=>$request->status]);
                return response()->json(['success'=>true,'limit'=>0]);
            }
            else
            {
                return response()->json(['success'=>true,'limit'=>1]);
            }
        }
        else
        {
            UserWebsite::where('id',$request->id)->update(['is_featured'=>$request->status]);
            return response()->json(['success'=>true,'limit'=>2]);
        }
        
    }

    public function getDownReason(Request $request)
    {   
        $down_reason=WebsiteLog::where('id',$request->id)->value('down_reason');
        return response()->json(['success' => true, 'down_reason' => $down_reason]);
    }
}
