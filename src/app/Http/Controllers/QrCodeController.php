<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Reservation;

use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function couponIndex()
    {
        $twoMonthsLater = Carbon::now()->addMonths(2);
        return view('emails.coupon', compact('twoMonthsLater'));
    }

    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('emails.reservation_confirm_qr', [
            'reservationDate' => $reservation->reservation_date,
            'reservationTime' => $reservation->reservation_time,
            'userName' => $reservation->user->name,
            'shopName' => $reservation->shop->shop_name,
            'numberOfGuests' => $reservation->number_of_guests,
        ]);
    }
}
