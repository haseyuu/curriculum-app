<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messagetxt;
    public $url;
    public $buttonText;
    public $subjectText;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subjectText, $messagetxt, $url, $buttonText)
    {
        $this->subjectText = $subjectText;
        $this->messagetxt = $messagetxt;
        $this->url = $url;
        $this->buttonText = $buttonText;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view('email.common_link');
    }
}
