<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role_id = $user->role_id;
        $favorites = Favorite::with('shop')
            ->where('user_id', $user->id)
            ->get();

        $userReservations = Reservation::with('shop')
            ->where('user_id', $user->id)
            ->where('status', '予約済み')
            ->get();
        $reservationCount = count($userReservations);

        $startTime = Carbon::createFromTime(17, 0, 0);
        $endTime = Carbon::createFromTime(21, 0, 0);

        $reservationTimes = []; // 予約可能な時間を格納する配列を初期化

        // 開始時間から15分ごとに繰り返し生成し、配列に追加
        for ($time = $startTime; $time->lessThanOrEqualTo($endTime); $time->addMinutes(15)) {
            $reservationTimes[] = $time->format('H:i'); // 時間を配列に追加
        }

        Log::info('Session success: ' . Session::get('success'));
        Log::info('Session error: ' . Session::get('error'));

        return view('mypage', compact('role_id', 'favorites', 'userReservations', 'reservationCount','reservationTimes'));
    }

    public function destroy(Request $request)
    {
        $favoriteId = $request->input('favorite_id');

        Favorite::find($favoriteId)->delete();

        return redirect()->route('mypage');
    }
}
