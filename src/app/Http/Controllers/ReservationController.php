<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $data = $request->only(['reservation_date', 'reservation_time', 'number_of_guests', 'shop_id', 'course_id']);
        $user_id = Auth::id();
        $data['user_id'] = $user_id;

        Reservation::create($data);
        $course = Course::findOrFail($data['course_id']);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $course->course_name,
                    ],
                    'unit_amount' => $course->price,
                ],
                'quantity' => $data['number_of_guests'],
            ]],
            'mode' => 'payment',
            'success_url' => route('done', ['session_id' => '{CHECKOUT_SESSION_ID}']),
            'cancel_url' => route('done'),
            ]);
            return redirect($checkout_session->url);
        //return view('done', compact('shop'));
    }

    public function done(Request $request)
    {
        $session_id = $request->input('session_id');
        return view('done');
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
