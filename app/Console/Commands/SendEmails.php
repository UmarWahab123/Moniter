<?php

namespace App\Console\Commands;

use App\Mail\SiteUptimeStatus;
use App\Monitor;
use App\Setting;
use App\User;
use App\UserWebsite;
use App\WebsiteLog;
use FCM;
use Illuminate\Console\Command;
# FireBase Notifications
use Illuminate\Support\Facades\Mail;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

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
        $sites = Monitor::get();

        foreach ($sites as $site) {
            if ($site->uptime_status == 'up') {

                $checkLogs = WebsiteLog::where('website_id', $site->id)->where('up_time', null)->first();
                if ($checkLogs != null) {
                    if ($checkLogs->up_time == null) {
                        $user_website = UserWebsite::where('website_id', $site->id)->first();
                        $user_id = ($user_website->user != null) ? $user_website->user->id : null;
                        $device_found = User::where('id', $user_id)->value('token');
                        if ($device_found) {

                            // send notification at existing device with firebase

                            $existing_device_token = $device_found;

                            $optionBuilder = new OptionsBuilder();
                            $optionBuilder->setTimeToLive(60 * 20);

                            $notificationBuilder = new PayloadNotificationBuilder('Your site is Up:'.$site->url);
                            $notificationBuilder->setBody('Your website '.$site->url.' status has been changed to Up')
                                ->setSound('default');

                            $dataBuilder = new PayloadDataBuilder();
                            // $dataBuilder->addData(['a_data' => 'my_data']);
                            $dataBuilder->addData([
                                // 'data_for' => 'user',
                                'click_action'=> 'FLUTTER_NOTIFICATION_CLICK',
                                'sound'=> 'default',
                                'com.google.firebase.messaging.default_notification_channel_id' => '104',
                                'title' => 'Uptime Status',
                                'message' => 'UP',
                            ]);

                            $option = $optionBuilder->build();
                            $notification = $notificationBuilder->build();
                            $data = $dataBuilder->build();

                            $token = $existing_device_token;
                            // $token = $firebase_token;

                            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
                            // $downstreamResponse = FCM::sendTo($token, $option, null, $data);

                            $downstreamResponse->numberSuccess();
                            $downstreamResponse->numberFailure();
                            $downstreamResponse->numberModification();

                            // return Array - you must remove all this tokens in your database
                            $downstreamResponse->tokensToDelete();

                            // return Array (key : oldToken, value : new token - you must change the token in your database)
                            $downstreamResponse->tokensToModify();

                            // return Array - you should try to resend the message to the tokens in the array
                            $downstreamResponse->tokensToRetry();

                            // return Array (key:token, value:error) - in production you should remove from your database the tokens
                            $downstreamResponse->tokensWithError();
                            // notification code ends

                        }
                        $website = $site->url;
                        $mailData['status'] = "Up";
                        $mailData['site'] = $website;
                        WebsiteLog::where('website_id', $site->id)->where('up_time', null)->update(['up_time' => $site->uptime_status_last_change_date]);
                        if ($site->getSiteDetails != null) {
                            $emails = $site->getSiteDetails->emails;
                            if ($emails != null) {
                                $mails = explode(',', $emails);
                                foreach ($mails as $mail) {
                                    Mail::to($mail)->send(new SiteUptimeStatus($mailData));
                                }
                                $this->info('Mail sent to customer mail for website sent!' . $mailData['site']);

                            } else {
                                $setting = Setting::where('type', 'email')->first();
                                if ($setting == null) {
                                    $default_mail = config('uptime-monitor.notifications.mail.to');
                                    if ($default_mail != null) {
                                        Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                                    }
                                } else {
                                    Mail::to($setting->settings)->send(new SiteUptimeStatus($mailData));
                                    // /dd('up',$setting->settings);

                                }
                            }
                        } else {
                            $setting = Setting::where('type', 'email')->first();
                            if ($setting == null) {
                                $default_mail = config('uptime-monitor.notifications.mail.to');
                                if ($default_mail != null) {
                                    Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                                    $this->info('Mail sent to config default mail for website sent!' . $mailData['site']);

                                }
                            } else {
                                Mail::to($setting->settings)->send(new SiteUptimeStatus($mailData));
                                $this->info('Mail sent to setting mail for website sent!' . $mailData['site']);

                            }
                            $this->info('Mail sent to default mail for website sent!' . $mailData['site']);

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
            } elseif ($site->uptime_status == 'down') {
                $checkLogs = WebsiteLog::where('website_id', $site->id)->where('up_time', null)->first();
                if ($checkLogs != null) {

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
                } else {
                    $user_website = UserWebsite::where('website_id', $site->id)->first();
                    $user_id = ($user_website->user != null) ? $user_website->user->id : null;
                    $device_found = User::where('id', $user_id)->value('token');
                    if ($device_found) {

                        // send notification at existing device with firebase

                        $existing_device_token = $device_found;

                        $optionBuilder = new OptionsBuilder();
                        $optionBuilder->setTimeToLive(60 * 20);

                        $notificationBuilder = new PayloadNotificationBuilder('Your site is Down:'.$site->url);
                        $notificationBuilder->setBody('Your website '.$site->url.' status has been changed to Down')
                            ->setSound('default');

                        $dataBuilder = new PayloadDataBuilder();
                        // $dataBuilder->addData(['a_data' => 'my_data']);
                        $dataBuilder->addData([
                            // 'data_for' => 'user',
                            'click_action'=> 'FLUTTER_NOTIFICATION_CLICK',
                            'sound'=> 'default',
                            'com.google.firebase.messaging.default_notification_channel_id' => '104',
                            'title' => 'Uptime Status',
                            'message' => 'Down',
                        ]);

                        $option = $optionBuilder->build();
                        $notification = $notificationBuilder->build();
                        $data = $dataBuilder->build();

                        $token = $existing_device_token;
                        // $token = $firebase_token;

                        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
                        // $downstreamResponse = FCM::sendTo($token, $option, null, $data);

                        $downstreamResponse->numberSuccess();
                        $downstreamResponse->numberFailure();
                        $downstreamResponse->numberModification();

                        // return Array - you must remove all this tokens in your database
                        $downstreamResponse->tokensToDelete();

                        // return Array (key : oldToken, value : new token - you must change the token in your database)
                        $downstreamResponse->tokensToModify();

                        // return Array - you should try to resend the message to the tokens in the array
                        $downstreamResponse->tokensToRetry();

                        // return Array (key:token, value:error) - in production you should remove from your database the tokens
                        $downstreamResponse->tokensWithError();
                        // notification code ends

                    }
                    $website_log = new WebsiteLog();
                    $website_log->website_id = $site->id;
                    $website_log->down_time = $site->uptime_status_last_change_date;
                    $website_log->down_reason = $site->uptime_check_failure_reason;
                    $website_log->save();
                    $website = $site->url;
                    $mailData['status'] = "Down";
                    $mailData['site'] = $website;
                    if ($site->getSiteDetails != null) {
                        $emails = $site->getSiteDetails->emails;
                        if ($emails != null) {
                            $mails = explode(',', $emails);
                            foreach ($mails as $mail) {
                                Mail::to($mail)->send(new SiteUptimeStatus($mailData));
                            }
                            $this->info('Mail sent to customer mail for website sent!' . $mailData['site']);

                        } else {
                            $setting = Setting::where('type', 'email')->first();
                            if ($setting == null) {
                                $default_mail = config('uptime-monitor.notifications.mail.to');
                                if ($default_mail != null) {
                                    Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                                }
                            } else {
                                Mail::to($setting->settings)->send(new SiteUptimeStatus($mailData));
                                //dd('down',$setting->settings);

                            }
                        }
                    } else {
                        $setting = Setting::where('type', 'email')->first();
                        if ($setting == null) {
                            $default_mail = config('uptime-monitor.notifications.mail.to');
                            if ($default_mail != null) {
                                Mail::to($default_mail[0])->send(new SiteUptimeStatus($mailData));
                                $this->info('Mail sent to config default mail for website sent!' . $mailData['site']);

                            }
                        } else {
                            Mail::to($setting->settings)->send(new SiteUptimeStatus($mailData));
                            $this->info('Mail sent to setting mail for website sent!' . $mailData['site']);

                        }
                        $this->info('Mail sent to default mail for website sent!' . $mailData['site']);
                    }

                }
            }
        }
        $this->info('All mails sent!');

    }
}
