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
}
