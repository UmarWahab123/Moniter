<?php

namespace App\Helpers;

use Auth;
use Artisan;
use App\User;
use App\Server;
use App\Monitor;
use App\Setting;
use App\WebsiteLog;
use Spatie\Url\Url;
use App\UserWebsite;
use App\Mail\SiteStatusMail;
use App\UserWebsitePermission;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Mail;

class WebsiteHelper
{
    public static function index($request)
    {
        $query = Monitor::whereHas('getUserWebsites', function ($q) {
            $q->where('user_id', Auth::user()->id);
        })
            ->orWhereHas('user', function ($q) {
                $q->where('parent_id', Auth::user()->id);
            })
            ->with('getSiteDetails', 'UserWebsitePivot')->get();
        $websites = $query;
        if ($request->ajax()) {
            return WebsiteHelper::WebsitesDatatable($query);
        }
        $no_of_websites_allowed = @auth()->user()->package->no_of_websites;
        $ids = User::where('parent_id', auth()->user()->id)->orWhere('id', auth()->user()->id)->pluck('id')->toArray();
        $user_website_added = UserWebsite::whereIn('user_id', $ids)->count();
        $servers = Server::select('id', 'name')->where('user_id', Auth::user()->id)->get();
        $users = User::where('role_id', 2)->where('parent_id', Auth::user()->id)->select('id', 'name')->get();
        return view('admin.websites.index', compact('websites', 'servers', 'users','no_of_websites_allowed','user_website_added'));
    }

    public static function WebsitesDatatable($query)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($item) {
                $html_string = '<input type="checkbox" name="' . $item->id . '"" id="' . $item->id . '"" value="' . $item->id . '" class="checkbox dt_checkboxes">';
                return $html_string;
            })
            ->addColumn('action', function ($item) {
                $html_string = null;
                $user_website_permission = $item->UserWebsitePivot->where('user_id', Auth::user()->id)->first();
                if ($item->user_id == Auth::user()->id || ($user_website_permission && $user_website_permission->permission == 1) || Auth::user()->role_id == 1) {
                    if ($item->getSiteDetails != null) {
                        if ($item->getSiteDetails->is_featured == 1) {
                            $html_string = ' <button   value="' . $item->id . '" data-status="0" class="btn btn-outline-secondary btn-sm feature"  title="Click to unfetaure"><i class="fa fa-star "></i></button>';
                        } else {
                            $html_string = ' <button   value="' . $item->id . '" data-status="1" class="btn btn-outline-success btn-sm feature"  title="Click to fetaure"><i class="fa fa-star "></i></button>';
                        }
                    }
                    $html_string .= ' <button  value="' . $item->id . '" data-developer_email="' . $item->getSiteDetails->developer_email . '" data-emails="' . $item->getSiteDetails->emails . '" data-emails="' . $item->getSiteDetails->emails . '" data-ssl="' . $item->certificate_check_enabled . '"  class="btn  btn-outline-primary btn-sm edit-site "  title="Edit"><i class="fa fa-pencil"></i></button>';

                    $html_string .= ' <button  value="' . $item->id . '"  class="btn btn-outline-danger btn-sm delete-site"  title="Delete"><i class="fa fa-trash-o"></i></button>';
                }

                $html_string .= ' <a  href=' . url("admin/website-logs/$item->id") . ' value="' . $item->id . '"  class="btn btn-outline-info btn-sm"  title="Details"><i class="fa fa-eye "></i></a>';
                return $html_string;
            })
            ->addColumn('assigned_users', function ($item) {
                $html_string = ' <button  value="' . $item->id . '"  class="btn btn-outline-primary btn-sm btn_view_assigned_users"  title="View Assigned Users"><i class="fa fa-eye"></i></button>';
                return $html_string;
            })
            ->addColumn('title', function ($item) {
                return $item->getSiteDetails != null ? $item->getSiteDetails->title : '--';
            })
            ->addColumn('status', function ($item) {
                if ($item->uptime_status == 'up')
                    $html = '<span class="badge badge-success ">Up</span>';
                else if ($item->uptime_status == 'down')
                    $html = '<span class="badge badge-danger ">Down</span>';
                else
                    $html = '<span class="badge badge-info">' . $item->uptime_status . '</span>';
                return $html;
            })
            ->addColumn('status_change_on', function ($item) {
                return $item->uptime_status_last_change_date;
            })
            ->addColumn('last_status_check', function ($item) {
                return ($item->uptime_last_check_date == null) ? '--' : $item->uptime_last_check_date;
            })
            ->addColumn('certificate_expiry_date', function ($item) {
                return ($item->certificate_expiration_date == null) ? '--' : $item->certificate_expiration_date;
            })
            ->addColumn('certificate_check', function ($item) {
                if ($item->certificate_check_enabled == 1) {
                    return '<span class="badge badge-success ">ON</span>';
                } else {
                    return '<span class="badge badge-danger ">OFF</span>';
                }
            })
            ->addColumn('certificate_issuer', function ($item) {
                return ($item->certificate_issuer == null) ? '--' : $item->certificate_issuer;
            })
            ->addColumn('url', function ($item) {
                return $item->url;
            })
            ->addColumn('domain_creation_date', function ($item) {
                return ($item->domain_creation_date != null) ? $item->domain_creation_date : '--';
            })
            ->addColumn('domain_updated_date', function ($item) {
                return ($item->domain_updated_date != null) ? $item->domain_updated_date : '--';
            })
            ->addColumn('domain_expiry_date', function ($item) {
                return ($item->domain_expiry_date != null) ? $item->domain_expiry_date : '--';
            })
            ->addColumn('reason', function ($item) {
                return ($item->uptime_check_failure_reason != null) ? $item->uptime_check_failure_reason : '--';
            })
            ->setRowId(function ($item) {
                return $item->id;
            })
            ->rawColumns(['action', 'status', 'certificate_check', 'checkbox', 'assigned_users'])
            ->make(true);
    }

    public static function store($request)
    {
        $url = $request->protocol . $request->url;  
        $url = rtrim($url,"/");
        $validator = $request->validate([
            'url' => 'required|unique:monitors,url,NULL,id,user_id,' . Auth::user()->id,
            'title' => 'required',
            'emails' => 'email|required',
        ], [
            'unique' => 'The url already existed'
        ]);
        $mailData = $request->all();
        if (!defined('STDIN')) {
            define('STDIN', fopen("php://stdin", "r"));
        }
        $output = Artisan::call("monitor:create " . $url);
        $websites = Monitor::get();
        $url = Url::fromString($url);
        $ssl = null;
        foreach ($websites as $web) {
            $webUrl = Url::fromString($web->url);
            if ($webUrl->getHost() == $url->getHost()) {
                $uweb = new UserWebsite();
                $uweb->website_id = $web->id;
                $uweb->user_id = Auth::user()->id;
                $uweb->title = $request->title;
                $uweb->emails = $request->emails;
                $uweb->server_id = $request->server_id;
                $uweb->developer_email = $request->developer_email;
                $uweb->owner_email = $request->owner_email;
                $uweb->domain_expiry_date = $request->domain_expiry_date;
                $uweb->domain_registrar = $request->domain_registrar;
                if (isset($request->ssl)) {
                    $ssl = 1;
                    $mailData['ssl'] = "True";
                } else {
                    $ssl = 0;
                    $mailData['ssl'] = "False";
                }
                $uweb->ssl = $ssl;
                $uweb->save();
                Monitor::where('id', $web->id)->update(['certificate_check_enabled' => $ssl, 'user_id' => Auth::user()->id, 'server_id' => @$mailData['server_id']]);
                $mails = $request->emails;
                if ($mails != null) {
                    $mails = explode(",", $request->emails);
                    foreach ($mails as $mail) {
                        Mail::to($mail)->send(new SiteStatusMail($mailData));
                    }
                } else {
                    $setting = Setting::where('type', 'email')->first();
                    if ($setting == null) {
                        $default_mail = config('uptime-monitor.notifications.mail.to');
                        if ($default_mail != null) {
                            Mail::to($default_mail[0])->send(new SiteStatusMail($mailData));
                        }
                    } else {
                        Mail::to($setting->settings)->send(new SiteStatusMail($mailData));
                    }
                }
                $no_of_websites_allowed = @auth()->user()->package->no_of_websites;
                $user_websites_added = UserWebsite::where('user_id', auth()->user()->id)->count();
                return response()->json([
                    'success' => true,
                    'no_of_websites_allowed' => $no_of_websites_allowed,
                    'user_websites_added' => $user_websites_added
                ]);
                return response()->json(['success' => true, '']);
            }
        }
        return response()->json(['error' => true]);
    }

    public static function destroy($request)
    {
        $output = Monitor::where('id', $request->id)->delete();
        if ($output > 0) {
            UserWebsite::where('website_id', $request->id)->delete();
            WebsiteLog::where('website_id', $request->id)->delete();
            $permissions = UserWebsitePermission::where('website_id', $request->id)->get();
            foreach ($permissions as $permission) {
                $permission->delete();
            }
            $no_of_websites_allowed = @auth()->user()->package->no_of_websites;
            $user_websites_added = UserWebsite::where('user_id', auth()->user()->id)->count();
            return response()->json(['success' => true,
            'no_of_websites_allowed' => $no_of_websites_allowed,
            'user_websites_added' => $user_websites_added
           ]);
        }
    }

    public static function edit($request)
    {
        $monitor = Monitor::find($request->website_id);
        if ($monitor != null) {
            $url = $monitor->url;
            $url = Url::fromString($url);
            $protocol = $url->getScheme();
            $eurl = $url->getHost();
            $protocol = $protocol. '://';
            $data['protocol'] = $protocol;
            $data['url'] = $eurl;
            $data['title'] = $monitor->getSiteDetails->title;
            $data['emails'] = $monitor->getSiteDetails->emails;
            $data['developer_email'] = $monitor->getSiteDetails->developer_email;
            $data['owner_email'] = $monitor->getSiteDetails->owner_email;
            $data['ssl'] = $monitor->getSiteDetails->ssl;
            $data['domain_expiry_date'] = $monitor->getSiteDetails->domain_expiry_date;
            $data['domain_registrar'] = $monitor->getSiteDetails->domain_registrar;
            $data['server_id'] = $monitor->server_id;
            return response()->json(['success' => true, 'data' => $data]);
        }
        return response()->json(['success' => false]);
    }

    public static function update($request)
    {
        $monitor = Monitor::find($request->id);
        $ssl = 0;
        if (isset($request->ssl)) {
            $ssl = 1;
        }
        $monitor->certificate_check_enabled = $ssl;
        $monitor->server_id = $request['server_id'];
        $url = $request->protocol . $request->url;  
        $url = rtrim($url,"/");
        $monitor->url = $url;
        $monitor->save();
        if ($monitor->save()) {
            UserWebsite::where('website_id', $request->id)->update(['emails' => $request->emails, 'title' => $request->title, 'developer_email' => $request->developer_email, 'domain_expiry_date'=>$request->domain_expiry_date, 'domain_registrar'=>$request->domain_registrar, 'owner_email' => $request->owner_email]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public static function featureWebsite($request)
    {
        $count = UserWebsite::where('user_id', Auth::user()->id)->where('is_featured', 1)->count();
        if ($request->status == 1) {
            if ($count < 10) {
                UserWebsite::where('website_id', $request->id)->update(['is_featured' => $request->status]);
                return response()->json(['success' => true, 'limit' => 0]);
            } else {
                return response()->json(['success' => true, 'limit' => 1]);
            }
        } else {
            UserWebsite::where('website_id', $request->id)->update(['is_featured' => $request->status]);
            return response()->json(['success' => true, 'limit' => 2]);
        }
    }

    public static function assignWebsiteToSubUser($request)
    {
        $websites = Monitor::whereIn('id', $request->selected_items)->get();
        foreach ($websites as $website) {
            $permission = UserWebsitePermission::where('website_id', $website->id)->where('user_id', $request->sub_user_id)->first();
            if ($permission != null) {
                $permission->permission = $request->permission;
            } else {
                $permission = new UserWebsitePermission();
                $permission->user_id = $request->sub_user_id;
                $permission->website_id = $website->id;
                $permission->permission = $request->permission;
            }
            $permission->save();
        }
        return response()->json(['success' => true]);
    }

    public static function showAssignedUser($request)
    {
        $website = Monitor::find($request->id);
        if ($website != null) {
            $html = "";
            foreach ($website->UserWebsitePivot as $item) {
                $permission = ($item->permission == 0) ? 'Read' : 'Read/Write';
                $html .= "<tr>";
                $html .= "<td>" . $item->user->name . "</td>";
                $html .= "<td>" . $permission . "</td>";
                $html .= "</tr>";
            }
            return response()->json(['success' => true, 'html' => $html]);
        }
    }
}
