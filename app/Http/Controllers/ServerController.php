<?php

namespace App\Http\Controllers;

use App\Server;
use App\ServerDetail;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class ServerController extends Controller
{
    public function index()
    {
        return view('servers.index');
    }

    public function dashboard()
    {
        $servers = Server::with(['serverLogs' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->get();
        $date = Carbon::now();
        // $data = unserialize(ServerDetail::first()->server_monitoring_data);
        // dd($data['disk_usage']);
        return view('servers.dashboard', compact('servers', 'date'));
    }

    public function getServers(Request $request)
    {
        $query = Server::with('userInfo','serverLogs')->orderBy('id','DESC');

        if($request->ajax())
        {
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('id', function ($item) {
                return $item->id != null ? $item->id : 'N.A' ;
             })
            ->addColumn('title', function ($item) {
                return $item->name != null ? $item->name : 'N.A' ;
             })
            ->addColumn('ip_address', function ($item) {
                return $item->ip_address != null ? $item->ip_address : 'N.A' ;
            })
            // ->addColumn('key', function ($item) {
            //     return $item->key != null ? $item->key : 'N.A' ;
            // })
            ->addColumn('added_by', function ($item) {
                return $item->user_id != null ? ($item->userInfo != null ? $item->userInfo->name : 'N.A') : 'N.A' ;
            })
            ->addColumn('file', function ($item) {
                if($item->file_path)
                {
                    $html_string =' <a target="_blank" download href='.$item->file_path.' class="btn btn-outline-info btn-sm" title="Download" ><i class="fa fa-download"></i></a>';
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
            ->addColumn('server_logs', function ($item) {
                if($item->serverLogs->count() > 0)
                {
                    $html_string =' <a target="_blank" href='.url("server-logs/$item->id").' class="btn btn-outline-primary btn-sm" title="Server Logs" ><i class="fa fa-eye"></i></a>';
                    return $html_string;
                }
                else
                {
                    return "N.A";
                }
            })
             ->addColumn('action', function ($item) {
                $html_string =' <button  value="'.$item->id.'"class="btn  btn-outline-primary btn-sm btn-edit "  title="Edit"><i class="fa fa-pencil"></i></button>';
                $html_string.=' <button  value="'.$item->id.'"  class="btn btn-outline-danger btn-sm btn-delete"  title="Delete"><i class="fa fa-trash-o"></i></button>';
                                                     
                    
                return $html_string;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['name','ip_address','added_by','file','os','server_logs', 'action'])
            ->make(true);
        }
    }

    public function addServer(Request $request)
    {
        $validator = $request->validate([
            'name'             => 'required',
            'ip_address'       => 'required|unique:servers',
            'operating_system' => 'required',
        ]);

        $api_path     = config('app.api_path');
        $python_token = config('app.python_token');
        
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

        $data['ip_address']    = $create->ip_address;
        $data['user_id']       = $create->user_id;
        $data['server_id']     = $create->id;
        $data['access_token']  = $python_token;

        // token must be send with server add

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

    public function serverLogs($id)
    {
        $server = Server::find($id);
        return view('servers.server_details', compact('server','id'));

        $getInfo = ServerDetail::where('server_id', $id)->orderBy('id', 'desc')->first();
        if($getInfo)
        {
            $unserialize = unserialize($getInfo->server_monitoring_data);
            dd($unserialize);
        }
        else
        {
            dd('out of logic');
        }
    }

    public function serverLogsInDetails(Request $request)
    {
        $type  = $request->for_type;
        $dynamic_tab_name = '';
        if($type == "os_release" || $type == "total_user")
        {
            $query = ServerDetail::where('server_id', $request->server_id)->orderBy('id', 'DESC')->take(1)->get();
        }
        else
        {
            $query = ServerDetail::where('server_id', $request->server_id)->orderBy('id', 'DESC');
        }

        if($request->for_type == 'cpu_usage')
        {
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('user', function ($item) use($type, $dynamic_tab_name) {
                $column = "user";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
             })
            ->addColumn('nice', function ($item) use($type, $dynamic_tab_name) {
                $column = "nice";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('system', function ($item) use($type, $dynamic_tab_name) {
                $column = "system";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('iowait', function ($item) use($type, $dynamic_tab_name) {
                $column = "iowait";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('steal', function ($item) use($type, $dynamic_tab_name) {
                $column = "steal";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('idle', function ($item) use($type, $dynamic_tab_name) {
                $column = "idle";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['user','nice','system','iowait','steal','idle'])
            ->make(true);
        }

        if($request->for_type == 'disk_usage')
        {
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('size', function ($item) use($type, $dynamic_tab_name) {
                $column = "size";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
             })
            ->addColumn('used', function ($item) use($type, $dynamic_tab_name) {
                $column = "used";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('available', function ($item) use($type, $dynamic_tab_name) {
                $column = "available";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('used_percentage', function ($item) use($type, $dynamic_tab_name) {
                $column = "used_percentage";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['size','used','available','used_percentage'])
            ->make(true);
        }

        if($request->for_type == 'ram_usage')
        {
            $dynamic_tab_name = $request->for_type_ram;

            if($dynamic_tab_name == 'memory')
            {
                return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('total', function ($item) use($type, $dynamic_tab_name) {
                    $column = "total";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                 })
                ->addColumn('used', function ($item) use($type, $dynamic_tab_name) {
                    $column = "used";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                })
                ->addColumn('free', function ($item) use($type, $dynamic_tab_name) {
                    $column = "free";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                })
                ->addColumn('shared', function ($item) use($type, $dynamic_tab_name) {
                    $column = "shared";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                })
                ->addColumn('buff_cache', function ($item) use($type, $dynamic_tab_name) {
                    $column = "buff/cache";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                })
                ->addColumn('available', function ($item) use($type, $dynamic_tab_name) {
                    $column = "available";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                })
                ->setRowId(function ($item) {
                    return $item->id;
                })
                ->rawColumns(['total','used','free','shared','buff_cache','available'])
                ->make(true);
            }
            else
            {
                return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('total', function ($item) use($type, $dynamic_tab_name) {
                    $column = "total";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                 })
                ->addColumn('used', function ($item) use($type, $dynamic_tab_name) {
                    $column = "used";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                })
                ->addColumn('free', function ($item) use($type, $dynamic_tab_name) {
                    $column = "free";
                    $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                    return $data;
                })
                ->setRowId(function ($item) {
                    return $item->id;
                })
                ->rawColumns(['total','used','free'])
                ->make(true);
            }
        }

        if($request->for_type == 'os_release')
        {
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($item) use($type, $dynamic_tab_name) {
                $column = "0";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
             })
            ->addColumn('version', function ($item) use($type, $dynamic_tab_name) {
                $column = "1";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('os_release_id', function ($item) use($type, $dynamic_tab_name) {
                $column = "2";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('id_like', function ($item) use($type, $dynamic_tab_name) {
                $column = "3";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('pretty_name', function ($item) use($type, $dynamic_tab_name) {
                $column = "4";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('version_id', function ($item) use($type, $dynamic_tab_name) {
                $column = "5";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('home_url', function ($item) use($type, $dynamic_tab_name) {
                $column = "6";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('support_url', function ($item) use($type, $dynamic_tab_name) {
                $column = "7";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('bug_report_url', function ($item) use($type, $dynamic_tab_name) {
                $column = "8";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('privacy_policy_url', function ($item) use($type, $dynamic_tab_name) {
                $column = "9";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('version_codename', function ($item) use($type, $dynamic_tab_name) {
                $column = "10";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('ubuntu_codename', function ($item) use($type, $dynamic_tab_name) {
                $column = "11";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['name','version','os_release_id','id_like','version_id','home_url','support_url','bug_report_url','privacy_policy_url','version_codename','ubuntu_codename','pretty_name'])
            ->make(true);
        }

        if($request->for_type == 'total_user')
        {
            return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($item) use($type, $dynamic_tab_name) {
                $column = "0";
                $data = $this->toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
             })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['name'])
            ->make(true);
        }
    }

    private function toGetSerializeResponse($serializeArray, $type, $column, $dynamic_tab_name)
    {
        $unserialize = unserialize($serializeArray->server_monitoring_data);
        if($type == "cpu_usage")
        {
            if(!empty($unserialize))
            {
                if(array_key_exists('cpu_usage', $unserialize))
                {
                    $meta_data = $unserialize['cpu_usage'];
                    if (!is_array($meta_data)) {
                       $meta_data = [$meta_data];
                    }
                    if(array_key_exists($column, $meta_data))
                    {
                        return $meta_data[$column];
                    }
                    else
                    {
                        return "--";
                    }
                }
            }
            else
            {
                return "--";
            }
        }

        if($type == "disk_usage")
        {
            if(!empty($unserialize))
            {
                if(array_key_exists('disk_usage', $unserialize))
                {
                    $meta_data = $unserialize['disk_usage'];
                    if (!is_array($meta_data)) {
                       $meta_data = [$meta_data];
                    }
                    if(array_key_exists($column, $meta_data))
                    {
                        return $meta_data[$column];
                    }
                    else
                    {
                        return "--";
                    }
                }
            }
            else
            {
                return "--";
            }
        }

        if($type == "ram_usage")
        {
            if(!empty($unserialize))
            {
                if(array_key_exists('ram_usage', $unserialize))
                {
                    $meta_data = $unserialize['ram_usage'];
                    if (!is_array($meta_data)) {
                       $meta_data = [$meta_data];
                    }

                    if(array_key_exists($dynamic_tab_name, $meta_data))
                    {
                        $meta_data = $meta_data[$dynamic_tab_name];
                        if (!is_array($meta_data)) {
                           $meta_data = [$meta_data];
                        }
                        if(array_key_exists($column, $meta_data))
                        {
                            return $meta_data[$column];
                        }
                        else
                        {
                            return "--";
                        }
                    }
                    else
                    {
                        return "--";
                    }
                }
            }
            else
            {
                return "--";
            }
        }

        if($type == "os_release")
        {
            if(!empty($unserialize))
            {
                if(array_key_exists('os_release', $unserialize))
                {
                    $meta_data = $unserialize['os_release'];
                    if (!is_array($meta_data)) {
                       $meta_data = [$meta_data];
                    }
                    if(array_key_exists($column, $meta_data))
                    {
                        return $meta_data[$column];
                    }
                    else
                    {
                        return "--";
                    }
                }
            }
            else
            {
                return "--";
            }
        }

        if($type == "total_user")
        {
            if(!empty($unserialize))
            {
                if(array_key_exists('total_user', $unserialize))
                {
                    $meta_data = $unserialize['total_user'];
                    if (!is_array($meta_data)) {
                       $meta_data = [$meta_data];
                    }
                    if(array_key_exists($column, $meta_data))
                    {
                        return $meta_data[$column];
                    }
                    else
                    {
                        return "--";
                    }
                }
            }
            else
            {
                return "--";
            }
        }
    }

    public function getLatestServerLogs(Request $request)
    {
         $servers = Server::with(['serverLogs' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->get();
        foreach ($servers as $server) {
            $server_details = $server->serverLogs->first();
            if (Carbon::now()->diffInMinutes($server_details->created_at) > 5 && $server_details->last_down != null) {
               $server_details->last_down = Carbon::now();
               $server_details->save();
            }
        }
        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        $server = Server::find($request->id);
        if ($server != null) {
            $serverLogs = ServerDetail::where('server_id', $server->id);
            if ($serverLogs != null) {
                $serverLogs->delete();
            }
            $server->delete();
        }
    }

    public function edit(Request $request)
    {
        $server = Server::find($request->server_id);
        if($server != null)
        {
            $data['name']=$server->name;
            $data['ip_address']=$server->ip_address;
            $data['os']=$server->os;
            return response()->json(['success'=>true,'data'=>$data]);
        }
        return response()->json(['success'=>false]);
    }

    public function update(Request $request)
    {
        $server = Server::find($request->id);
        if($server != null)
        {
            $server->name = $request->name;
            $server->ip_address = $request->ip_address;
            $server->os = $request->operating_system;
            $server->save();
            return response()->json(['success'=>true]);
        }
        return response()->json(['success'=>false]);
    }
}
