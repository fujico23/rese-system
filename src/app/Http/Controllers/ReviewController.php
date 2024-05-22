<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    public function create(Shop $shop)
    {
        return view('shop_review', compact('shop'));
    }

    public function store(Shop $shop, ReviewRequest $request)
    {
        // 同店舗・同ユーザーで複数回予約が入っていた場合、statusが予約済みの最初のデータを取得
        $reservation = $shop->reservations()
            ->where('user_id', Auth::id())
            ->where('status', '予約済み')
            ->first();

        if ($reservation) {
            $reviewData = [
                'reservation_id' => $reservation->id,
                'comment' => $request->input('comment'),
                'rating' => $request->input('rating'),
            ];
            Review::create($reviewData);

            $status = $request->input('status');
            $reservation->update(['status' => $status]);

            return view('review_done', compact('shop'));
        } else {
            return back()->with('error', '予約が見つかりませんでした。');
        }
    }

    public function index(Shop $shop)
    {
        $reservations = $shop->reservations()
        ->with('review', 'user',)
        ->where('status', '口コミ済み')
        ->get();
        return view('shop_review_index', compact('reservations', 'shop'));
    }
}
