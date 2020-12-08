<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SSLCertificateExpiry extends Mailable
{
    use Queueable, SerializesModels;
    public $monitors_arr;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($monitors_arr,$type)
    {
        $this->monitors_arr=$monitors_arr;
        $this->type=$type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $monitors_arr=$this->monitors_arr;
        $type=$this->type;
        return $this->subject('SSL Expiry Notification')->
        markdown('emails.ssl_certificate_expiry');
    }
}
