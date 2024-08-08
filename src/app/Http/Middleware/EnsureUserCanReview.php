<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class EnsureUserCanReview
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $shopId = $request->route('shop')->id;

        // role_idが1の時は管理者のため、投稿不可
        if ($user->role_id === 1) {
            return redirect()->back()->with('error', '管理者権限ユーザーは口コミを投稿できません');
        }

        // role_idが2の時は店舗代表者のため、投稿不可
        if ($user->role_id === 2) {
            return redirect()->back()->with('error', '店舗代表者権限ユーザーは口コミを投稿できません');
        }

        // role_idが3の時に口コミ投稿が可能か確認
        if ($user && $user->role_id === 3) {
            // ユーザーの該当店舗に対する予約を確認
            $reservation = Reservation::where('user_id', $user->id)
                ->where('shop_id', $shopId)
                ->first();

            if (!$reservation) {
                return redirect()->back()->with('error', '予約をした店舗に来店後に口コミ出来ます');
            }

            // 予約のステータスに応じた条件分岐
            switch ($reservation->status) {
                case '口コミ済み':
                    return redirect()->back()->with('error', '既にこの店舗に口コミを投稿しています');
                case 'キャンセル':
                    return redirect()->back()->with('error', '予約後来店が無かったため口コミ投稿出来ません');
                case '予約済み':
                    // ユーザーが既にレビューを投稿していないか確認
                    $existingReview = $reservation->review()->where('reservation_id', $reservation->id)->first();
                    if (!$existingReview) {
                        return $next($request);
                    }
                    return redirect()->back()->with('error', '既にこの店舗に口コミを投稿しています');
                default:
                    return redirect()->back()->with('error', '予約の状態により口コミを投稿できません');
            }
        }

        return redirect()->back()->with('error', 'この操作は許可されていません');
    }
}
