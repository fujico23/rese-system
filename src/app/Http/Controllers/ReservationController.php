<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $data = $request->only(['reservation_date', 'reservation_time', 'number_of_guests', 'shop_id', 'course_id']);
        $user_id = Auth::id();
        $data['user_id'] = $user_id;

        $reservation = Reservation::create($data);
        $reservationId = $reservation->id;

        $course = Course::findOrFail($data['course_id']);

        //Stripeと連携させる
        Stripe::setApiKey(config('services.stripe.st_key'));
        //StripeCheckoutSessionを作成した時にStripe APIから返されるオブジェクトを設定
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
            'metadata' => [
                'reservation_id' => $reservationId,
            ],
            'mode' => 'payment',
            'success_url' => route('done', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('undone', [], true),
        ]);

        //ユーザーを、Stripeが生成した支払いフォームのURLに誘導
        return redirect($checkout_session->url);
    }

    public function done(Request $request)
    {
        $sessionId = $request->input('session_id');
        Log::info('Session ID: ' . $sessionId);

        // StripeのセッションIDを使って支払い情報を取得
        Stripe::setApiKey(config('services.stripe.st_key'));
        $session = Session::retrieve($sessionId);

        // 支払いが成功している場合の処理
        if ($session->payment_status === 'paid') {
            $reservationId = $session->metadata['reservation_id'];
            // 対応する予約データのpayment_statusを更新
            Reservation::where('id', $reservationId)->update(['payment_status' => 'paid']);
            return view('done');
        }
    }
    public function undone()
    {
        return view('undone');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('mypage')->with('success', '予約が削除されました');
    }

    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $data = $request->only(['reservation_date', 'reservation_time', 'number_of_guests']);

        $reservation->update($data);

        return redirect()->route('mypage')->with('success', '予約が正しく編集されました');
    }
}
