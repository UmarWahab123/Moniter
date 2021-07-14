<?php

namespace App\Http\Controllers;

use App\Server;
use App\User;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        return view('servers.index');
    }

    public function getServers(Request $request)
    {
        $query = Server::with('userInfo')->orderBy('id','DESC');

        if($request->ajax())
        {
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('title', function ($item) {
                return $item->name != null ? $item->name : 'N.A' ;
             })
            ->addColumn('ip_address', function ($item) {
                return $item->ip_address != null ? $item->ip_address : 'N.A' ;
            })
            ->addColumn('key', function ($item) {
                return $item->key != null ? $item->key : 'N.A' ;
            })
            ->addColumn('added_by', function ($item) {
                return $item->user_id != null ? ($item->userInfo != null ? $item->userInfo->name : 'N.A') : 'N.A' ;
            })
            ->addColumn('file', function ($item) {
                if($item->file_path)
                {
                    $html_string =' <a download href='.$item->file_path.' class="btn btn-outline-info btn-sm" title="Download" ><i class="fa fa-download"></i></a>';
                    return $html_string;
                }
                else
                {
                    return $item->file_path != null ? $item->file_path : 'N.A' ;
                }
            })
            ->addColumn('os', function ($item) {
                return $item->os != null ? strtoupper($item->os) : 'N.A' ;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['name','ip_address','key','added_by','file','os'])
            ->make(true);
        }
    }

    public function addServer(Request $request)
    {
        $validator = $request->validate([
            'name'             => 'required',
            'ip_address'       => 'required',
            'operating_system' => 'required',
        ]);

        $api_path = config('app.api_path');
        
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_key = substr(str_shuffle(str_repeat($pool, 5)), 0, 32);

        $create = new Server;
        $create->name       = $request->name;
        $create->ip_address = $request->ip_address;
        $create->os         = $request->operating_system;
        $create->user_id    = Auth::user()->id;
        $create->key        = $random_key;
        $create->save();

        $data = array();
        $url  = $api_path.'/add_server';

        $data['ip']      = $create->ip_address;
        $data['user_id'] = $create->user_id;
        $data['key']     = $create->key;

        $response = $this->curl_call($url,$data);

        if($response)
        {
            $create->file_path  = $response->file_path;
            $create->save();
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function curl_call($url,$data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
        ));

        $response = curl_exec($curl);

        // if ($response === false) {
        //     throw new \Exception(curl_error($curl), curl_errno($curl));
        // }
        curl_close($curl);
        return json_decode($response);
    }
}
