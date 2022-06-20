<?php

namespace App\Helpers;

use Artisan;
use Auth;
use Spatie\Url\Url;
use App\UserWebsite;
use Illuminate\Support\Facades\Mail;
use App\Mail\SiteStatusMail;
use Yajra\Datatables\Datatables;
use App\WebsiteLog;
use App\Monitor;
use App\Setting;

class WebsiteHelper
{
    public static function index($request)
    {
        $query = Monitor::whereHas('getUserWebsites', function ($q) {
            $q->where('user_id', Auth::user()->id);
        })->with('getSiteDetails')->get();
        $websites = $query;
        if ($request->ajax()) {
            return WebsiteHelper::WebsitesDatatable($query);
        }
        return view('admin.websites.index', compact('websites'));
    }

    public static function WebsitesDatatable($query)
    {
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                if ($item->getSiteDetails != null) {
                    $html_string = null;
                    if ($item->getSiteDetails->is_featured == 1) {
                        $html_string = ' <button   value="' . $item->id . '" data-status="0" class="btn btn-outline-secondary btn-sm feature"  title="Click to unfetaure"><i class="fa fa-star "></i></button>';
                    } else {
                        $html_string = ' <button   value="' . $item->id . '" data-status="1" class="btn btn-outline-success btn-sm feature"  title="Click to fetaure"><i class="fa fa-star "></i></button>';
                    }
                }
                $html_string .= ' <button  value="' . $item->id . '" data-developer_email="' . $item->getSiteDetails->developer_email . '" data-emails="' . $item->getSiteDetails->emails . '" data-emails="' . $item->getSiteDetails->emails . '" data-ssl="' . $item->certificate_check_enabled . '"  class="btn  btn-outline-primary btn-sm edit-site "  title="Edit"><i class="fa fa-pencil"></i></button>';
                $html_string .= ' <a  href=' . url("admin/website-logs/$item->id") . ' value="' . $item->id . '"  class="btn btn-outline-info btn-sm"  title="Details"><i class="fa fa-eye "></i></a>';
                $html_string .= ' <button  value="' . $item->id . '"  class="btn btn-outline-danger btn-sm delete-site"  title="Delete"><i class="fa fa-trash-o"></i></button>';
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
            ->rawColumns(['action', 'status', 'certificate_check'])
            ->make(true);
    }

    public static function store($request)
    {
        $validator = $request->validate([
            'url' => 'required|url|unique:monitors,url,NULL,id,user_id,' . Auth::user()->id,
            'title' => 'required',
            'emails' => 'email|required',
        ], [
            'unique' => 'The url already existed'
        ]);
        $mailData = $request->all();
        define('STDIN', fopen("php://stdin", "r"));
        $output = Artisan::call("monitor:create " . $request->url);
        $websites = Monitor::get();
        $url = Url::fromString($request->url);
        $ssl = null;
        foreach ($websites as $web) {
            $webUrl = Url::fromString($web->url);
            if ($webUrl->getHost() == $url->getHost()) {
                $uweb = new UserWebsite();
                $uweb->website_id = $web->id;
                $uweb->user_id = Auth::user()->id;
                $uweb->title = $request->title;
                $uweb->emails = $request->emails;
                $uweb->developer_email = $request->developer_email;
                $uweb->owner_email = $request->owner_email;
                if (isset($request->ssl)) {
                    $ssl = 1;
                    $mailData['ssl'] = "True";
                } else {
                    $ssl = 0;
                    $mailData['ssl'] = "False";
                }
                $uweb->ssl = $ssl;
                $uweb->save();
                Monitor::where('id', $web->id)->update(['certificate_check_enabled' => $ssl, 'user_id' => Auth::user()->id]);
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
            return response()->json(['success' => true]);
        }
    }

    public static function edit($request)
    {
        $monitor = Monitor::find($request->website_id);
        if ($monitor != null) {
            $data['title'] = $monitor->getSiteDetails->title;
            $data['emails'] = $monitor->getSiteDetails->emails;
            $data['developer_email'] = $monitor->getSiteDetails->developer_email;
            $data['owner_email'] = $monitor->getSiteDetails->owner_email;
            $data['ssl'] = $monitor->getSiteDetails->ssl;
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
        if ($monitor->save()) {
            UserWebsite::where('website_id', $request->id)->update(['emails' => $request->emails, 'title' => $request->title, 'developer_email' => $request->developer_email, 'owner_email' => $request->owner_email]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public static function featureWebsite($request)
    {
        $count = UserWebsite::where('user_id', Auth::user()->id)->where('is_featured', 1)->count();
        if ($request->status == 1) {
            if ($count < 10) {
                UserWebsite::where('id', $request->id)->update(['is_featured' => $request->status]);
                return response()->json(['success' => true, 'limit' => 0]);
            } else {
                return response()->json(['success' => true, 'limit' => 1]);
            }
        } else {
            UserWebsite::where('id', $request->id)->update(['is_featured' => $request->status]);
            return response()->json(['success' => true, 'limit' => 2]);
        }
    }
}
