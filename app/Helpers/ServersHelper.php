<?php

namespace App\Helpers;

use App\Monitor;
use App\Server;
use App\ServerDetail;
use App\UserWebsite;
use App\UserWebsitePermission;
use Auth;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class ServersHelper
{

    public static function dashboard()
    {
        $servers = Server::with(['serverLogs' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->where('user_id', Auth::user()->id)->get();
        $date = Carbon::now();
        return view('servers.dashboard', compact('servers', 'date'));
    }

    public static function getServers($request)
    {
        $query = Server::with('userInfo', 'serverLogs')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC');
        if ($request->ajax()) {
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('id', function ($item) {
                    return $item->id != null ? $item->id : 'N.A';
                })
                ->addColumn('title', function ($item) {
                    return $item->name != null ? $item->name : 'N.A';
                })
                ->addColumn('ip_address', function ($item) {
                    return $item->ip_address != null ? $item->ip_address : 'N.A';
                })
                ->addColumn('added_by', function ($item) {
                    return $item->user_id != null ? ($item->userInfo != null ? $item->userInfo->name : 'N.A') : 'N.A';
                })
                ->addColumn('file', function ($item) {
                    if ($item->file_path) {
                        $html_string = ' <a target="_blank" download href=' . $item->file_path . ' class="btn btn-outline-info btn-sm" title="Download" ><i class="fa fa-download"></i></a>';
                        return $html_string;
                    } else {
                        return $item->file_path != null ? $item->file_path : 'N.A';
                    }
                })
                ->addColumn('os', function ($item) {
                    return $item->os != null ? strtoupper($item->os) : 'N.A';
                })
                ->addColumn('server_logs', function ($item) {
                    if ($item->serverLogs->count() > 0) {
                        $html_string = ' <a target="_blank" href=' . url("server-logs/$item->id") . ' class="btn btn-outline-primary btn-sm" title="Server Logs" ><i class="fa fa-eye"></i></a>';
                        return $html_string;
                    } else {
                        return "N.A";
                    }
                })
                ->addColumn('binded_websites', function ($item) {
                    $html_string = ' <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm btn_binded_websites"  title="Binded Websites"><i class="fa fa-eye"></i></button>';
                    return $html_string;
                })
                ->addColumn('server_file', function ($item) {
                    if ($item->server_file_path) {
                        $html_string = ' <a target="_blank" download href=' . $item->server_file_path . ' class="btn btn-outline-info btn-sm" title="Download" ><i class="fa fa-download"></i></a>';
                        return $html_string;
                    } else {
                        return $item->server_file_path != null ? $item->server_file_path : 'N.A';
                    }
                })
                ->addColumn('action', function ($item) {
                    $html_string = ' <button  value="' . $item->id . '"class="btn  btn-outline-primary btn-sm btn-edit "  title="Edit"><i class="fa fa-pencil"></i></button>';
                    $html_string .= ' <button  value="' . $item->id . '"  class="btn btn-outline-danger btn-sm btn-delete"  title="Delete"><i class="fa fa-trash-o"></i></button>';
                    return $html_string;
                })
                ->setRowId(function ($item) {
                    return $item->id;
                })
                ->rawColumns(['name', 'ip_address', 'added_by', 'file', 'os', 'server_logs', 'action', 'binded_websites', 'server_file'])
                ->make(true);
        }
    }

    public static function addServer($request)
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
        $url  = $api_path . '/add_server';
        $data['ip_address']    = $create->ip_address;
        $data['user_id']       = $create->user_id;
        $data['server_id']     = $create->id;
        $data['access_token']  = $python_token;
        // token must be send with server add
        $response = ServersHelper::curl_call($url, $data);
        if ($response) {
            $create->file_path  = $response->file_path;
            $create->save();
        }
        $url  = $api_path . '/add_server_new';
        $response = ServersHelper::curl_call($url, $data);
        if ($response) {
            $create->server_file_path  = $response->file_path;
            $create->save();
        }
        return response()->json([
            'success' => true,
        ]);
    }

    public static function curl_call($url, $data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPTTIM_EOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public static function destroy($request)
    {
        $server = Server::find($request->id);
        if ($server != null) {
            $monitors = Monitor::where('server_id', $server->id)->get();
            foreach ($monitors as $monitor) {
                $websites = UserWebsite::where('website_id', $monitor->id)->get();
                foreach ($websites as $website) {
                    $website->delete();
                }
                $user_website_permissions = UserWebsitePermission::where('website_id', $monitor->id)->get();
                foreach ($user_website_permissions as $pivot) {
                    $pivot->delete();
                }
                $monitor->delete();
            }
            $serverLogs = ServerDetail::where('server_id', $server->id);
            if ($serverLogs != null) {
                $serverLogs->delete();
            }
            $server->delete();
        }
    }

    public static function edit($request)
    {
        $server = Server::find($request->server_id);
        if ($server != null) {
            $data['name'] = $server->name;
            $data['ip_address'] = $server->ip_address;
            $data['os'] = $server->os;
            return response()->json(['success' => true, 'data' => $data]);
        }
        return response()->json(['success' => false]);
    }

    public static function update($request)
    {
        $server = Server::find($request->id);
        if ($server != null) {
            $server->name = $request->name;
            $server->ip_address = $request->ip_address;
            $server->os = $request->operating_system;
            $server->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public static function bindedWebsites($request)
    {
        $server = Server::where('user_id', Auth::user()->id)->first();
        if (Auth::user() && Auth::user()->role_id == 2) {
            $server = Server::where('user_id', Auth::user()->parent_id)->first();
        }
        if ($server != null) {
            $monitors = Monitor::all();
            if ($monitors != null) {
                $html = "<option value='' selected disabled>Select Website</option>";
                foreach ($monitors as $monitor) {
                    $html .= "<option value='" . $monitor->id . "'>" . $monitor->getSiteDetails->title . "</option>";
                }
                return response()->json(['success' => true, 'html' => $html, 'server_id' => $request->server_id]);
            }
        }
    }

    public static function getBindedWebsites($request)
    {
        $query = Monitor::with('getSiteDetails')->where('server_id', $request->id);
        if ($request->ajax()) {
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('title', function ($item) {
                    return $item->getSiteDetails != null ? $item->getSiteDetails->title : 'N.A';
                })
                ->addColumn('url', function ($item) {
                    return $item->url != null ? $item->url : 'N.A';
                })
                ->setRowId(function ($item) {
                    return $item->id;
                })
                ->rawColumns(['title', 'url'])
                ->make(true);
        }
    }

    public static function saveBindedWebsites($request)
    {
        $monitor = Monitor::find($request->website_id);
        if ($monitor != null) {
            if ($monitor->server_id == $request->server_id) {
                return response()->json(['success' => false]);
            }
            $monitor->server_id = $request->server_id;
            $monitor->save();
            return response()->json(['success' => true]);
        }
    }
}
