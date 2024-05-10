<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $data = $request->only(['reservation_date', 'reservation_time', 'number_of_guests', 'shop_id', 'course_id']);
        $shop = $request->input('shop_id');
        $user_id = Auth::id();
        $data['user_id'] = $user_id;

        Reservation::create($data);
        return view('done', compact('shop'));
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('mypage')->with('success', '予約が削除されました');
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $data = $request->only(['reservation_date', 'reservation_time', 'number_of_guests']);

        $reservation->update($data);

        return redirect()->route('mypage')->with('success', '予約が正しく編集されました');
    }
}
