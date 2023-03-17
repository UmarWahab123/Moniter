<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperatingSystem;
use App\Helpers\OperatingSystemHelper;

class OperatingSystemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function operatingSystem(Request $request){
        return OperatingSystemHelper::operatingSystem($request);
    }
    public function addOperatingSystem(Request $request)
    {
        $operatingSystem = OperatingSystemHelper::store($request->all());
        if ($operatingSystem) {
            $action = $operatingSystem[1];
            if($action == "Added"){
                return response()->json([
                    'success' => true,
                    'message' => 'New Operating System added successfully',
                ]);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Operating System updated successfully',
                ]);
            }
           
        }
    }
    public function EditOperatingSystem(Request $request)
    {
        return OperatingSystemHelper::EditOperatingSystem($request);
    }
    public function deleteOperatingSystem(Request $request)
    {
        return OperatingSystemHelper::delete($request);
    }
}