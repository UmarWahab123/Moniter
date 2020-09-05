<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SiteUptimeStatus;
use App\WebsiteLog;

use App\Monitor;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Emails When Status of websites change';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sites=Monitor::get();

        foreach($sites as $site)
        {
            if($site->uptime_status=='up')
            {
                $checkLogs=WebsiteLog::where('website_id',$site->id)->where('up_time',null)->first();
                if($checkLogs!=null)
                {
                    if($checkLogs->up_time==null)
                    {
                        $website=$site->url;
                        $mailData['status']="Up";
                        $mailData['site']=$website;
                        WebsiteLog::where('website_id',$site->id)->where('up_time',null)->update(['up_time'=>$site->uptime_status_last_change_date]);
                        if($site->getSiteDetails!=null)
                        {
                            $emails=$site->getSiteDetails->emails;
                            if($emails!=null)
                            {
                                $mails=explode(',',$emails);
                                foreach($mails as $mail)
                                {
                                    Mail::to($mail)->send(new SiteUptimeStatus($mailData));
                                }
                                $this->info('Mail sent to customer mail for website sent!'.$mailData['site']);

                            }
                            else
                            {
                                $default_mail=config('uptime-monitor.notifications.mail.to');
                                if(!empty($default_mail))
                                {
                                    Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                                }
                                $this->info('Mail sent to default mail for website sent!'.$mailData['site']);

                            }
                        }
                        else
                        {
                            $default_mail=config('uptime-monitor.notifications.mail.to');
                            if(!empty($default_mail))
                            {
                                Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                            }
                            $this->info('Mail sent to default mail for website sent!'.$mailData['site']);

                        }
                         

                    }
                    // else
                    // {
                    //     $website_log=new WebsiteLog();
                    //     $website_log->website_id=$site->id;
                    //     $website_log->down_time=$site->uptime_status_last_change_date->toDateTimeString();
                    //     $website_log->up_time=$site->id;
                    //     $website_log->save();
                    // }
                }
                // else 
                // {
                //     $website_log=new WebsiteLog();
                //     $website_log->website_id=$site->id;
                //     $website_log->down_time=$site->uptime_status_last_change_date->toDateTimeString();
                //     $website_log->up_time=$site->id;
                //     $website_log->save();
                // }
            }
            elseif($site->uptime_status=='down')
            {
                $checkLogs=WebsiteLog::where('website_id',$site->id)->where('up_time',null)->first();
                if($checkLogs!=null)
                {

                    // if($checkLogs->up_time==null)
                    // {
                    //     WebsiteLog::where('website_id',$site->id)->where('up_time',null)->update(['up_time'=>$site->uptime_status_last_change_date->toDateTimeString()]);
                    // }
                    // else
                    // {
                    //     $website_log=new WebsiteLog();
                    //     $website_log->website_id=$site->id;
                    //     $website_log->down_time=$site->uptime_status_last_change_date->toDateTimeString();
                    //     $website_log->up_time=$site->id;
                    //     $website_log->save();
                    // }
                }
                else 
                {

                    $website_log=new WebsiteLog();
                    $website_log->website_id=$site->id;
                    $website_log->down_time=$site->uptime_status_last_change_date;
                    $website_log->save();
                    $website=$site->url;
                    $mailData['status']="Down";
                    $mailData['site']=$website;
                    if($site->getSiteDetails!=null)
                    {
                        $emails=$site->getSiteDetails->emails;
                        if($emails!=null)
                        {
                            $mails=explode(',',$emails);
                            foreach($mails as $mail)
                            {
                                Mail::to($mail)->send(new SiteUptimeStatus($mailData));
                            }
                            $this->info('Mail sent to customer mail for website sent!'.$mailData['site']);
                            
                        }
                        else
                        {
                            $default_mail=config('uptime-monitor.notifications.mail.to');
                            if(!empty($default_mail))
                            {
                                Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                            }
                            $this->info('Mail sent to default mail for website sent!'.$mailData['site']);

                        }
                    }
                    else
                    {
                        $default_mail=config('uptime-monitor.notifications.mail.to');
                        if(!empty($default_mail))
                        {
                            Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                        }
                        $this->info('Mail sent to default mail for website sent!'.$mailData['site']);

                    }
                  
                }
            }
        }
        $this->info('All mails sent!');

    }
}
