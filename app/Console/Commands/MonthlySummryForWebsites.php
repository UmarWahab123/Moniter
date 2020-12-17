<?php

namespace App\Console\Commands;

use App\Mail\MonthlySummary;
use App\Monitor;
use App\Setting;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MonthlySummryForWebsites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:monthly-summry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $dateObj=new DateTime();
        $lastmonth=$dateObj->modify('-30 day')->format('Y-m-d');
        $lastmonthName=$dateObj->format('F');
        $today=Date('Y-m-d');
        $monitors=Monitor::with('getSiteDetails')->get();
        $urlData=[];
        foreach($monitors as $monitor)
        {
            $logs=null;
            // $title=($monitor->getSiteDetails!=null)?$monitor->getSiteDetails->title:'N/A';
            // $down_count=($monitor->getSiteDetails!=null)?$monitor->getSiteLogs->where('down_time','!=',null)->count():'N/A';
            // $up_count=($monitor->getSiteDetails!=null)?$monitor->getSiteLogs->where('up_time','!=',null)->count():'N/A';
            if($monitor->getSiteLogs!=null)
            {
                $logs=$monitor->getSiteLogs->first();
            }
           $urlData[]=([
                'title'=>($monitor->getSiteDetails!=null)?$monitor->getSiteDetails->title:'N/A',
                'url'=>$monitor->url,
                'ssl_expiry_date'=>($monitor->certificate_expiration_date!=null)?$monitor->certificate_expiration_date:'N/A',
                'last_check'=>$monitor->uptime_last_check_date,
                'last_up'=>($logs !=null)?$logs->up_time:'N/A',
                'last_down'=>($logs !=null)?$logs->down_time:'N/A',
                'down_count'=>($monitor->getSiteLogs!=null)?$monitor->getSiteLogs->where('down_time','!=',null)->count():'N/A',
                'up_count'=>($monitor->getSiteLogs!=null)?$monitor->getSiteLogs->where('up_time','!=',null)->count():'N/A',
                
           ]);
        }
        
        if($urlData!=null)
        {
            $setting=Setting::where('type','email')->first();
            if($setting==null)
            {
                $default_mail=config('uptime-monitor.notifications.mail.to');
                if($default_mail!=null)
                {
                    Mail::to($default_mail[0])->send(new MonthlySummary($urlData,$lastmonthName));
                }
                $this->info('Mail sent to default email address');
                $this->info('************ '.$lastmonthName.' ****************');
            }
            else
            {
                Mail::to($setting->settings)->send(new MonthlySummary($urlData,$lastmonthName));
                $this->info('Mail sent to admin email address');
                $this->info('************* '.$lastmonthName.' ***************');
            }
        }
    }
}
