<?php

namespace App\Console\Commands;

use App\Mail\SSLCertificateExpiry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Monitor;
use App\Setting;
use DateTime;
class CheckSSLCertificateExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:check-ssl-certificate-expiry';

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
        $type=null;
        $monitors=Monitor::with('getSiteDetails')->get();
        $today=Date('Y-m-d H:i:s');
        $monitor_ids=[];
        foreach($monitors as $monitor)
        {
            $before_expiry=new DateTime($monitor->certificate_expiration_date);
            $before_expiry=$before_expiry->modify('-7 day')->format('Y-m-d H:i:s');
            if($today >= $before_expiry && $today <= $monitor->certificate_expiration_date)
            {
                $monitor_ids[]=$monitor->id;
            }
        }
        if($monitor_ids!=null)
        {
            $this->info('These urls are going to expire in this week!'.implode(',',$monitor_ids));
            $this->info('****************************');
            $monitors=Monitor::whereIn('id',$monitor_ids)->get();
            $monitors_arr=[];
            foreach($monitors as $monitor)
            {
                $monitors_arr[]=([
                    'url'=>$monitor->url,
                    'certificate_expiration_date'=>$monitor->certificate_expiration_date,
                    'name'=>($monitor->getSiteDetails!=null)?$monitor->getSiteDetails->title:'N/A',
                    'type'=>'admin',
                    // 'email'=>($monitor->getSiteDetails!=null)?$monitor->getSiteDetails->emails:'N/A',
                ]);
                $urlEmailData=[];
                $urlEmailData['url']=$monitor->url;
                $urlEmailData['certificate_expiration_date']=$monitor->certificate_expiration_date;
                $urlEmailData['name']=($monitor->getSiteDetails!=null)?$monitor->getSiteDetails->title:'N/A';
                $type='url';

                if($monitor->getSiteDetails!=null)
                {
                    $emails=$monitor->getSiteDetails->emails;
                    if($emails!=null)
                    {
                        $mails=explode(',',$emails);
                        foreach($mails as $mail)
                        {
                            Mail::to($mail)->send(new SSLCertificateExpiry($urlEmailData,$type));
                        }
    
                    }
                }
            }
            $setting=Setting::where('type','email')->first();
            if($setting==null)
            {
                $type='admin';
                $default_mail=config('uptime-monitor.notifications.mail.to');
                if($default_mail!=null)
                {
                    Mail::to($default_mail[0])->send(new SSLCertificateExpiry($monitors_arr,$type));
                }
            }
            else
            {
                $type='admin';
                Mail::to($setting->settings)->send(new SSLCertificateExpiry($monitors_arr,$type));
                // /dd('up',$setting->settings);

            }

            // $this->info('These urls are going to expire in this week!'.$monitors_arr['url']);
            $this->info('****************************');
        }
       

    }
}
