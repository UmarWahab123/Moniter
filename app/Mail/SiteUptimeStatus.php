<?php

namespace App\Mail;

use App\EmailTemplate;
use App\TemplateKeyword;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SiteUptimeStatus extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailData = $this->mailData;
        $email_template = EmailTemplate::where('name', 'SiteUptimeStatus')->where('status', 1)->first();
        if ($email_template) {
            $template_keywords = TemplateKeyword::select('keyword')->where('status', 1)->get();
            $body = $email_template->body;
            $subject = $email_template->subject;
            foreach ($template_keywords as $template_keyword) {
                $keyword = $template_keyword->keyword;
                switch ($keyword) {
                    case '[[status]]':
                        $body = str_replace($keyword, $mailData['status'], $body);
                        $subject = str_replace($keyword, $mailData['status'], $subject);
                        break;
                    case '[[site]]':
                        $body = str_replace($keyword, $mailData['site'], $body);
                        $subject = str_replace($keyword, $mailData['site'], $subject);
                        break;
                }
            }
            $body = html_entity_decode($body);
            return $this->subject($subject)->markdown('emails.siteuptimestatus', compact('email_template', 'body'));
        }
        return $this->subject('Your site is ' . $mailData['status'] . ': ' . $mailData['site'])->markdown('emails.siteuptimestatus');
    }
}
