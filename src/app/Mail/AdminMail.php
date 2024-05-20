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
    public $qrCode;
    public $filePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $body, $user, $qrCode, $filePath)
    {
        $this->title = $title;
        $this->body = $body;
        $this->user = $user;
        $this->qrCode = $qrCode;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【Rese】' . $this->title)
        ->view('emails.send_user_mail')
        ->with('filePath', $this->filePath);
    }
}
