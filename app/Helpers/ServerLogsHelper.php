<?php

namespace App\Helpers;

use App\Server;
use App\ServerDetail;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class ServerLogsHelper
{
    public static function serverLogs($id)
    {
        $server = Server::find($id);
        return view('servers.server_details', compact('server', 'id'));
    }

    public static function serverLogsInDetails(Request $request)
    {
        $type  = $request->for_type;
        $dynamic_tab_name = '';
        if ($type == "os_release" || $type == "total_user") {
            $query = ServerDetail::where('server_id', $request->server_id)->orderBy('id', 'DESC')->take(1)->get();
        } else {
            $query = ServerDetail::where('server_id', $request->server_id)->orderBy('id', 'DESC');
        }
        if ($request->for_type == 'cpu_usage') {
            return ServerLogsHelper::cpuUsageDatatable($query, $type, $dynamic_tab_name);
        }
        if ($request->for_type == 'disk_usage') {
            return ServerLogsHelper::diskUsageDatatable($query, $type, $dynamic_tab_name);
        }
        if ($request->for_type == 'ram_usage') {
            return ServerLogsHelper::ramUsageDatatable($query, $type, $dynamic_tab_name, $request);
        }
        if ($request->for_type == 'os_release') {
            return ServerLogsHelper::oSReleaseDatatable($query, $type, $dynamic_tab_name);
        }
        if ($request->for_type == 'total_user') {
            return ServerLogsHelper::totalUserDatatable($query, $type, $dynamic_tab_name);
        }
    }

    private static function toGetSerializeResponse($serializeArray, $type, $column, $dynamic_tab_name)
    {
        $unserialize = unserialize($serializeArray->server_monitoring_data);
        if ($type == "cpu_usage") {
            if (!empty($unserialize)) {
                if (array_key_exists('cpu_usage', $unserialize)) {
                    $meta_data = $unserialize['cpu_usage'];
                    if (!is_array($meta_data)) {
                        $meta_data = [$meta_data];
                    }
                    if (array_key_exists($column, $meta_data)) {
                        return $meta_data[$column];
                    } else {
                        return "--";
                    }
                }
            } else {
                return "--";
            }
        }

        if ($type == "disk_usage") {
            if (!empty($unserialize)) {
                if (array_key_exists('disk_usage', $unserialize)) {
                    $meta_data = $unserialize['disk_usage'];
                    if (!is_array($meta_data)) {
                        $meta_data = [$meta_data];
                    }
                    if (array_key_exists($column, $meta_data)) {
                        return $meta_data[$column];
                    } else {
                        return "--";
                    }
                }
            } else {
                return "--";
            }
        }

        if ($type == "ram_usage") {
            if (!empty($unserialize)) {
                if (array_key_exists('ram_usage', $unserialize)) {
                    $meta_data = $unserialize['ram_usage'];
                    if (!is_array($meta_data)) {
                        $meta_data = [$meta_data];
                    }

                    if (array_key_exists($dynamic_tab_name, $meta_data)) {
                        $meta_data = $meta_data[$dynamic_tab_name];
                        if (!is_array($meta_data)) {
                            $meta_data = [$meta_data];
                        }
                        if (array_key_exists($column, $meta_data)) {
                            return $meta_data[$column];
                        } else {
                            return "--";
                        }
                    } else {
                        return "--";
                    }
                }
            } else {
                return "--";
            }
        }

        if ($type == "os_release") {
            if (!empty($unserialize)) {
                if (array_key_exists('os_release', $unserialize)) {
                    $meta_data = $unserialize['os_release'];
                    if (!is_array($meta_data)) {
                        $meta_data = [$meta_data];
                    }
                    if (array_key_exists($column, $meta_data)) {
                        return $meta_data[$column];
                    } else {
                        return "--";
                    }
                }
            } else {
                return "--";
            }
        }

        if ($type == "total_user") {
            if (!empty($unserialize)) {
                if (array_key_exists('total_user', $unserialize)) {
                    $meta_data = $unserialize['total_user'];
                    if (!is_array($meta_data)) {
                        $meta_data = [$meta_data];
                    }
                    if (array_key_exists($column, $meta_data)) {
                        return $meta_data[$column];
                    } else {
                        return "--";
                    }
                }
            } else {
                return "--";
            }
        }
    }

    private static function cpuUsageDatatable($query, $type, $dynamic_tab_name)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('user', function ($item) use ($type, $dynamic_tab_name) {
                $column = "user";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('nice', function ($item) use ($type, $dynamic_tab_name) {
                $column = "nice";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('system', function ($item) use ($type, $dynamic_tab_name) {
                $column = "system";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('iowait', function ($item) use ($type, $dynamic_tab_name) {
                $column = "iowait";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('steal', function ($item) use ($type, $dynamic_tab_name) {
                $column = "steal";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('idle', function ($item) use ($type, $dynamic_tab_name) {
                $column = "idle";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['user', 'nice', 'system', 'iowait', 'steal', 'idle'])
            ->make(true);
    }

    private static function diskUsageDatatable($query, $type, $dynamic_tab_name)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('size', function ($item) use ($type, $dynamic_tab_name) {
                $column = "size";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('used', function ($item) use ($type, $dynamic_tab_name) {
                $column = "used";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('available', function ($item) use ($type, $dynamic_tab_name) {
                $column = "available";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('used_percentage', function ($item) use ($type, $dynamic_tab_name) {
                $column = "used_percentage";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['size', 'used', 'available', 'used_percentage'])
            ->make(true);
    }

    private static function ramUsageDatatable($query, $type, $dynamic_tab_name, $request)
    {
        $dynamic_tab_name = $request->for_type_ram;
        if ($dynamic_tab_name == 'memory') {
            return ServerLogsHelper::ramMemoryDatatable($query, $type, $dynamic_tab_name);
        } else {
            return ServerLogsHelper::ramSwapDatatable($query, $type, $dynamic_tab_name);
        }
    }

    private static function ramMemoryDatatable($query, $type, $dynamic_tab_name)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('total', function ($item) use ($type, $dynamic_tab_name) {
                $column = "total";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('used', function ($item) use ($type, $dynamic_tab_name) {
                $column = "used";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('free', function ($item) use ($type, $dynamic_tab_name) {
                $column = "free";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('shared', function ($item) use ($type, $dynamic_tab_name) {
                $column = "shared";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('buff_cache', function ($item) use ($type, $dynamic_tab_name) {
                $column = "buff/cache";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('available', function ($item) use ($type, $dynamic_tab_name) {
                $column = "available";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['total', 'used', 'free', 'shared', 'buff_cache', 'available'])
            ->make(true);
    }

    private static function ramSwapDatatable($query, $type, $dynamic_tab_name)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('total', function ($item) use ($type, $dynamic_tab_name) {
                $column = "total";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('used', function ($item) use ($type, $dynamic_tab_name) {
                $column = "used";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('free', function ($item) use ($type, $dynamic_tab_name) {
                $column = "free";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['total', 'used', 'free'])
            ->make(true);
    }

    private static function oSReleaseDatatable($query, $type, $dynamic_tab_name)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($item) use ($type, $dynamic_tab_name) {
                $column = "0";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('version', function ($item) use ($type, $dynamic_tab_name) {
                $column = "1";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('os_release_id', function ($item) use ($type, $dynamic_tab_name) {
                $column = "2";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('id_like', function ($item) use ($type, $dynamic_tab_name) {
                $column = "3";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('pretty_name', function ($item) use ($type, $dynamic_tab_name) {
                $column = "4";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('version_id', function ($item) use ($type, $dynamic_tab_name) {
                $column = "5";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('home_url', function ($item) use ($type, $dynamic_tab_name) {
                $column = "6";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('support_url', function ($item) use ($type, $dynamic_tab_name) {
                $column = "7";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('bug_report_url', function ($item) use ($type, $dynamic_tab_name) {
                $column = "8";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('privacy_policy_url', function ($item) use ($type, $dynamic_tab_name) {
                $column = "9";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('version_codename', function ($item) use ($type, $dynamic_tab_name) {
                $column = "10";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->addColumn('ubuntu_codename', function ($item) use ($type, $dynamic_tab_name) {
                $column = "11";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['name', 'version', 'os_release_id', 'id_like', 'version_id', 'home_url', 'support_url', 'bug_report_url', 'privacy_policy_url', 'version_codename', 'ubuntu_codename', 'pretty_name'])
            ->make(true);
    }

    private static function totalUserDatatable($query, $type, $dynamic_tab_name)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($item) use ($type, $dynamic_tab_name) {
                $column = "0";
                $data = ServerLogsHelper::toGetSerializeResponse($item, $type, $column, $dynamic_tab_name);
                return $data;
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    public static function getLatestServerLogs($request)
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
}
