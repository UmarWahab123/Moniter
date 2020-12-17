<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MonthlySummary extends Mailable
{
    use Queueable, SerializesModels;
    public $urlData;
    public $lastmonthName;
    /**lastmonthName
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($urlData,$lastmonthName)
    {
        $this->urlData=$urlData;
        $this->lastmonthName=$lastmonthName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       $urlData= $this->urlData;
       $lastmonthName= $this->lastmonthName;
        return $this->subject('Monthly Summary of ' .$lastmonthName)->markdown('emails.monthly_summary');
    }
}
