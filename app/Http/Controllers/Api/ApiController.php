<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Monitor;
use App\WebsiteLog;
use Carbon\Carbon;
use DateTime;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ApiController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        // $this->user = JWTAuth::parseToken()->authenticate();
        try 
        {
            if (! $this->user = JWTAuth::parseToken()->authenticate())
            {
                return response()->json([
                    'error' => true,
                    'code'  => 10,
                    'data'  => [
                        'message'   => 'User not found by given token'
                    ]
                ]);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'error' => true,
                'code'  => 11,
                'data'  => [
                    'message'   => 'Expired Token'
                ]
            ]);

        } catch (TokenInvalidException $e) {
            return response()->json([
                'error' => true,
                'code'  => 12,
                'data'  => [
                    'message'   => 'Invalid Token'
                ]
            ]);

        } catch (JWTException $e) {

            return response()->json([
                'error' => true,
                'code'  => 13,
                'data'  => [
                    'message'   => 'Token absent'
                ]
            ]);

        }

    }
    public function getMonitor(Request $request)
    {
        $data=null;
        $website_ids=$this->user->userWebsites->pluck('website_id')->toArray();
        $websites=Monitor::whereIn('id',$website_ids)->get();
        $dateObj=new DateTime();
        $lastmonth=$dateObj->modify('-30 day')->format('Y-m-d');
        $now_time=Carbon::now();
        $month_mintues=$now_time->diffInMinutes($lastmonth);
        // $month_mintues=43800;
        foreach($websites as $website)
        {
            $percentage=null;
            if($website->getSiteLogs!=null)
            {
                $total_time=null;
                $logs=$website->getSiteLogs->where('created_at','>',$lastmonth);
                foreach($logs  as $log)
                {


                    $down_time=Carbon::parse($log->down_time);
                    $up_time=Carbon::parse($log->up_time);
                    $time_difference=null;
                    if($up_time!=null)
                    {
                        $time_difference=$up_time->diffInMinutes($down_time);
                    }
                    else
                    {
                        $time_difference=$now_time->diffInMinutes($down_time);
                    }
                    $total_time+=$time_difference;
                }
                $percentage=100-number_format(($total_time/$month_mintues)*100,2);
                // dd($percentage,$total_time);
            }
            $data[]=array(
                "id"=>$website->id,
                "title"=>($website->getSiteDetails!=null)?$website->getSiteDetails->title:'--',
                "url"=>$website->url,
                "status"=>$website->uptime_status,
                "last_status_change"=>$website->uptime_status_last_change_date,
                "last_checked"=>$website->uptime_last_check_date,
                "ssl_check"=>($website->certificate_check_enabled==1)?'On':'Off',
                "certificate_expiry_date"=>$website->certificate_expiration_date,
                "certificate_issuer"=>$website->certificate_issuer,
                "monthly_percentage"=>$percentage,
                "created_at"=>$website->created_at->format('Y-m-d H:i:s'),
                "updated_at"=>$website->updated_at->format('Y-m-d H:i:s'),
            );
        }
       
        if (!$data) {
            return response()->json([
                'success' => true,
                'message' => 'No website against this user',
            ], 400);
        }
     
        return $data;
    }

    public function getFeaturedMonitor(Request $request)
    {
        $data=null;
        $website_ids=$this->user->userWebsites->where('is_featured',1)->pluck('website_id')->toArray();
        $websites=Monitor::whereIn('id',$website_ids)->get();
        $dateObj=new DateTime();
        $lastmonth=$dateObj->modify('-30 day')->format('Y-m-d');
        $now_time=Carbon::now();
        $month_mintues=$now_time->diffInMinutes($lastmonth);
        foreach($websites as $website)
        {
            $percentage=null;
            $total_time=null;
            if($website->getSiteLogs!=null)
            {
                $details=$website->getSiteLogs->first();
                $logs=$website->getSiteLogs->where('created_at','>',$lastmonth);
                foreach($logs  as $log)
                {


                    $down_time=Carbon::parse($log->down_time);
                    $up_time=Carbon::parse($log->up_time);
                    $time_difference=null;
                    if($up_time!=null)
                    {
                        $time_difference=$up_time->diffInMinutes($down_time);
                    }
                    else
                    {
                        $time_difference=$now_time->diffInMinutes($down_time);
                    }
                    $total_time+=$time_difference;
                }
                $percentage=100-number_format(($total_time/$month_mintues)*100,2);
            }
            // dd($last_month_logs);
            $data[]=array(
                "id"=>$website->id,
                "title"=>($website->getSiteDetails!=null)?$website->getSiteDetails->title:'N/A',
                "url"=>$website->url,
                "status"=>$website->uptime_status,
                "certificate_expiry_date"=>$website->certificate_expiration_date,
                "last_up"=>($details!=null)?date('Y-m-d',strtotime($details->up_time)):'--',
                "last_down"=>($details!=null)?date('Y-m-d',strtotime($details->down_time)):'--',
                "monthly_percentage"=>$percentage,
            );
        }
       
        if (!$data) {
            return response()->json([
                'success' => true,
                'message' => 'No website is featured',
            ], 400);
        }
     
        return $data;
    }
    
    public function getMonitorDetail(Request $request)
    {
        $website=WebsiteLog::where('website_id',$request->website_id)->get()->toArray();
        if($website!=null)
        {

            return response()->json(['website_logs'=>$website,'success'=>true]);
        }
        else
        {
            return response()->json(['website_logs'=>$website,'success'=>false]);
        }
       
    }
}
