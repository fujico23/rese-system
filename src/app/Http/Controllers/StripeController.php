<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Menu;
use App\Models\Reservation;
use Stripe\Webhook;
use Stripe\Stripe;
use Stripe\Charge;

use Illuminate\Http\Request;

class StripeController extends Controller
{

    public function index(Shop $shop)
    {
        $menus = Menu::with('shop')
            ->where('shop_id', $shop->id)
            ->get();
        return view('stripe', compact('shop', 'menus'));
    }
    public function charge(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $charge = Charge::create([
                'amount' => 1000, // 金額（セント単位）
                'currency' => 'usd',
                'description' => 'Example charge',
                'source' => $request->stripeToken,
            ]);

            // 決済成功時の処理
            return redirect()->route('payment.success')->with('success', 'Payment was successful.');
        } catch (\Exception $e) {
            // エラー時の処理
            return back()->withErrors('Error! ' . $e->getMessage());
        }
    }


    public function handleWebhook(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\Exception $e) {
            return response('Invalid payload', 400);
        }

        // 受信したイベントを処理する
        switch ($event->type) {
            case 'checkout.session.completed':
                $checkoutSession = $event->data->object;
                $reservationId = $checkoutSession->metadata->reservation_id;
                $this->handleCheckoutSessionCompleted($reservationId);
                break;
                // ...
            default:
                // 未処理のイベントタイプの場合は無視
                break;
        }

        return response('Webhook handled', 200);
    }
    private function handleCheckoutSessionCompleted($reservationId)
    {
        // reservationsテーブルのpayment_statusを更新する処理をここに書く
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->payment_status = 'paid';
        $reservation->save();
    }
}
