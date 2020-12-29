<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Monitor;
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
        foreach($websites as $website)
        $data[]=array(
            "title"=>($website->getSiteDetails!=null)?$website->getSiteDetails->title:'N/A',
            "url"=>$website->url,
            "status"=>$website->uptime_status,
            "last_status_change"=>$website->uptime_status_last_change_date,
            "last_checked"=>$website->uptime_last_check_date,
            "ssl_check"=>($website->certificate_check_enabled==1)?'On':'Off',
            "certificate_expiry_date"=>$website->certificate_expiration_date,
            "certificate_issuer"=>$website->certificate_issuer,
            "created_at"=>$website->created_at,
            "updated_at"=>$website->updated_at,
        );
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
        $website_ids=$this->user->userWebsites->where('is_featuried',1)->pluck('website_id')->toArray();
        $websites=Monitor::whereIn('id',$website_ids)->get();
        foreach($websites as $website)
        {
            if($website->getSiteLogs!=null)
            {
                $logs=$website->getSiteLogs->first();
            }
    
            $data[]=array(
                "title"=>($website->getSiteDetails!=null)?$website->getSiteDetails->title:'N/A',
                "url"=>$website->url,
                "status"=>$website->uptime_status,
                "certificate_expiry_date"=>$website->certificate_expiration_date,
                "last_up"=>($logs!=null)?date('Y-m-d',strtotime($logs->up_time)):'--',
                "last_down"=>($logs!=null)?date('Y-m-d',strtotime($logs->down_time)):'--',
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
}
