<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use App\Models\ReviewImage;
use App\Models\Shop;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(ImageRequest $request, Shop $shop, Review $review)
    {
        // 画像のアップロード処理
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
        return response()->json(['success' => '画像がアップロードされました']);
    }

    public function deleteMultipleImages(Request $request, Review $review, ReviewImage $image)
    {
        // チェックボックスで選択された画像IDのリストを取得
        $imageIds = $request->input('images', []);
        $images = $review->images->whereIn('id', $imageIds);
        foreach ($images as $image) {
            $image->delete();
        }
        return redirect()->back()->with('success', '選択された画像が削除されました。');
    }
}
