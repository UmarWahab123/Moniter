<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;
use App\Mail\SiteStatusMail;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();


        // $schedule->command('monitor:check-uptime')->withoutOverlapping()->everyMinute()  
        // ->appendOutputTo('storage/logs/check-uptime.log');
        $schedule->command('monitor:send-emails')->withoutOverlapping()->everyMinute()  
        ->appendOutputTo('storage/logs/send-emails.log');
        // $schedule->command('monitor:check-certificate')->withoutOverlapping()->daily()
        // ->appendOutputTo('storage/logs/check-certificate.log');

        // $schedule->command('monitor:check-ssl-certificate-expiry')->withoutOverlapping()->everyMinute()
        // ->appendOutputTo('storage/logs/check-ssl-certificate-expiry.log');

        // $schedule->command('monitor:monthly-summry')->withoutOverlapping()->monthly()
        // ->appendOutputTo('storage/logs/monthly-summry.log');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
