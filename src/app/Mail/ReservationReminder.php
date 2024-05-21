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
        ->generate(route('reservation.confirm', ['id' => $this->reservation->id]));

        //qrCodeを保存する処理:本番強の場合はs3に変更
        $fileName = 'qrcode.png';
        Storage::disk('public')->put($fileName, $qrCode);
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
