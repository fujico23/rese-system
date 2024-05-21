<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Coupon extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $fileUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $fileUrl)
    {
        $this->user = $user;
        $this->fileUrl = $fileUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【Rese】クーポン送信のお知らせ')
        ->view('emails.coupon_index')
        ->with('fileUrl', $this->fileUrl);
    }
}
