<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Menu;
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

    public function chargeCourse(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    
            $token = $request->input('token');
            $amount = $request->input('amount');
    
            // Stripeのチャージを作成して実行
            $charge = Charge::create([
                'amount' => $amount,
                'currency' => 'JPY',
                'source' => $token,
                'description' => '予約料金の決済'
            ]);
    
            // 決済が成功した場合の処理
            // 予約のpayment_statusを更新するなどの処理を行う
    
            return redirect()->route('payment.success')->with('success', 'Payment was successful.');
        } catch (\Exception $e) {
            return back()->withErrors('Error! ' . $e->getMessage());
        }
    }
}
