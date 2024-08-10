<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\ReviewImage;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Shop $shop)
    {
        return view('shop_review', compact('shop'));
    }

    public function store(Shop $shop, ReviewRequest $request)
    {
        $userId = Auth::id();
        // 同店舗・同ユーザーで複数回予約が入っていた場合、statusが予約済みの最初のデータを取得
        $reservation = $shop->reservations()
            ->where('user_id', Auth::id())
            ->where('status', '予約済み')
            ->first();

        if ($reservation) {
            // まずレビューを保存
            $reviewData = [
                'reservation_id' => $reservation->id,
                'comment' => $request->input('comment'),
                'rating' => $request->input('rating'),
            ];
            $review = Review::create($reviewData);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('review_images/' . $review->id, 'public');
                    $image_url = Storage::url($path);

                    ReviewImage::create([
                        'review_id' => $review->id,
                        'image_url' => $image_url,
                    ]);
                }
            }

            $reservation->update(['status' => '口コミ済み']);

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

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->back()->with('success', 'コメントが削除されました');
    }

    public function edit(Shop $shop, Reservation $reservation)
    {
        $review = Review::with('images')
            ->where('reservation_id', $reservation->id)
            ->first();
        return view('review_edit', compact('shop', 'review'));
    }
    public function update(ReviewRequest $request, Shop $shop, Review $review)
    {
        $review->update([
            'comment' => $request->input('comment'),
            'rating' => $request->input('rating'),
        ]);

        // 画像のアップロードとReviewImagesテーブルへの保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('review_images/' . $review->id, 'public');
                $image_url = Storage::url($path);

                ReviewImage::create([
                    'review_id' => $review->id,
                    'image_url' => $image_url,
                ]);
            }
        }

        return back()->with('success', 'コメントが編集されました');
    }
}
