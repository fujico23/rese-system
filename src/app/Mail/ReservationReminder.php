<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

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
            ->generate(route('reservation.confirm.qr', ['id' => $this->reservation->id]));

        // ユニークなファイル名を作成
        $fileName = 'qrcode_' . $this->reservation->id . '.png';

        // フォルダー名を予約日で作成
        $reservationDate = Carbon::parse($this->reservation->reservation_date);
        $folderName = 'qrcodes/' . $reservationDate->format('Y-m-d');

        // フルパスを作成
        $filePath = $folderName . '/' . $fileName;

        // QRコードを保存する処理：本番環境の場合はS3に変更
        Storage::disk('public')->put($filePath, $qrCode);
        $url = Storage::disk('public')->url($filePath);

        return $this->view('emails.reservation_reminder')
            ->with([
                'reservationDate' => $this->reservation->reservation_date,
                'reservationTime' => $this->reservation->reservation_time,
                'userName' => $this->reservation->user->name,
                'shopName' => $this->reservation->shop->shop_name,
                'numberOfGuests' => $this->reservation->number_of_guests,
                'paymentStatus' => $this->reservation->payment_status,
                'status' => $this->reservation->status,
                'courseName' => $this->reservation->course->course_name,
                'coursePrice' => $this->reservation->course->price,
                'filePath' => $url,
            ])
            ->subject('【Rese】本日ご予約の確認です');
    }
}
