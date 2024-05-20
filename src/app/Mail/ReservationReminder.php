<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ReservationReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $qrCode = QrCode::format('png')
        ->size(200)
        ->generate('http://localhost/mypage');

        //qrCodeを保存する処理
        $fileName = 'qrcode.png'; //ファイルの名前を設定
        Storage::disk('public')->put($fileName, $qrCode); //filesystem.phpの'public'を設定しqrCodeを保存
        $filePath = Storage::disk('public')->url($fileName);

        return $this->view('emails.reservation_reminder')
        ->with([
            'reservationDate' => $this->reservation->reservation_date,
            'reservationTime' => $this->reservation->reservation_time,
            'userName' => $this->reservation->user->name,
            'shopName' => $this->reservation->shop->shop_name,
            'numberOfGuests' => $this->reservation->number_of_guests,
            'filePath' => $filePath,
        ])
        ->subject('【Rese】本日ご予約の確認です');
    }
}
