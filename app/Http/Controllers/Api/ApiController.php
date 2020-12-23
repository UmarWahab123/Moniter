<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $websites=$this->user->userWebsites->toArray();
        if (!$websites) {
            return response()->json([
                'success' => false,
                'message' => 'No website against this user',
            ], 400);
        }
     
        return $websites;
    }
}
