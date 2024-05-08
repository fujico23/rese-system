<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $body;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $body, $user)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【Rese】' . $this->title)
        ->view('emails.send_user_mail');
    }
}
