<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Monitor;
use JWTAuth;
class ApiController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function getMonitor(Request $request)
    {
        $website_ids=$this->user->userWebsites->pluck('website_id')->toArray();
        $websites=Monitor::whereIn('id',$website_ids)->get()->toArray();
        if (!$websites) {
            return response()->json([
                'success' => false,
                'message' => 'No website against this user',
            ], 400);
        }
     
        return $websites;
    }
}
