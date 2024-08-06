<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ShopRequest;
use Illuminate\Support\Carbon;

class ShopController extends Controller
{
    public function index()
    {
        //メール認証していないユーザーに対してモーダルウィンドウで促す為に
        $emailVerified = true;
        if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
            $emailVerified = false;
        }

        $user = Auth::user();
        $areas = Area::all();
        $genres = Genre::all();

        // ログイン状態に関わらず初期化を実施
        $shops = collect();
        $reservedShopIds = [];
        $favoriteShopIds = [];
        $today = now();

        //ログインしている場合、2つの処理を行う
        if ($user) {
            // 予約している店舗のIDを取得後、予約が入っている店舗情報を取得
            //予約日時が過ぎた口コミ未送信のshopIdを取得
            $reservedShopIds = Reservation::where('user_id', $user->id)
                ->where('status', '予約済み')
                ->whereDate('reservation_date', '<', now())
                ->pluck('shop_id')
                ->toArray();

            $shopsReserved = Shop::with(['area', 'genre', 'images', 'reservations'])
                ->whereIn('id', $reservedShopIds)
                ->where('is_active', true)
                ->get();

            // 予約が入っていない店舗情報を取得
            $shopsNotReserved = Shop::with(['area', 'genre', 'images'])
                ->whereNotIn('id', $reservedShopIds)
                ->where('is_active', true)
                ->get();

            // (1)予約が入っているお店と予約が入っていないお店を結合し、予約が入っているお店から表示させる
            $shops = $shopsReserved->merge($shopsNotReserved);

            // (2)お気に入りの店舗IDを取得
            $favoriteShopIds = $user->favorites->pluck('shop_id')->toArray();
        } else {
            // ログインしていない場合はす全ての店舗情報を取得
            $shops = Shop::with(['area', 'genre', 'images'])
                ->where('is_active', true)
                ->get();
        }

        // 各店舗に予約済みか、お気に入りに登録されているかのフラグを設定
        $shops->each(function ($shop) use ($reservedShopIds, $favoriteShopIds) {
            $shop->isReserved = in_array($shop->id, $reservedShopIds);
            $shop->isFavorited = in_array($shop->id, $favoriteShopIds);
        });

        return view('index', compact('emailVerified', 'areas', 'genres', 'shops'));
    }

    public function search(Request $request)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::GenreSearch($request->genre_id)
            ->AreaSearch($request->area_id)
            ->KeywordSearch($request->keyword)
            ->get();

        $favoriteShopIds = Auth::user() ? Auth::user()
            ->favorites->pluck('shop_id')
            ->toArray() : [];

        $shops->each(function ($shop) use ($favoriteShopIds) {
            $shop->isFavorited = in_array($shop->id, $favoriteShopIds);
        });

        return view('index', compact('areas', 'genres', 'shops'));
    }

    public function show(Shop $shop)
    {
        $user = Auth::user();
        $reservations = [];
        $nowDate = Carbon::now()->toDateString();
        $twoHoursLater = Carbon::now()->addHours(2)->toTimeString();
        // ユーザーがログインしている場合の処理
        if ($user) {
            $reservations = Reservation::where('user_id', $user->id)
                ->where('shop_id', $shop->id)
                ->whereDate('reservation_date', '<', $nowDate)
                ->where('status', '予約済み')
                ->first();
        } else {
            // ユーザーがログインしていない場合の処理
            $reservations = [];
        }

        $startTime = Carbon::createFromTime(17, 0, 0);
        $endTime = Carbon::createFromTime(21, 0, 0);

        // 予約可能な時間を格納する配列を初期化
        $reservationTimes = [];

        // 開始時間から15分ごとに繰り返し生成し、配列に追加
        for ($time = $startTime; $time->lessThanOrEqualTo($endTime); $time->addMinutes(15)) {
            $reservationTimes[] = $time->format('H:i'); // 時間を配列に追加
        }

        $courses = Course::where('shop_id', $shop->id)
            ->get();

        $reservations = $shop->reservations()
            ->with('review', 'user',)
            ->where('status', '口コミ済み')
            ->get();

        return view('detail', compact('user', 'shop', 'reservations', 'reservationTimes', 'courses', 'reservations'));
    }

    //ハッシュタグ area検索
    public function filterByArea($areaName)
    {
        $user = Auth::user();
        $areas = Area::all();
        $genres = Genre::all();

        // 該当するエリア名のレコードを取得
        $shops = Shop::with(['area', 'genre', 'images'])
            ->whereHas('area', function ($query) use ($areaName) {
                $query->where('area_name', $areaName);
            })
            ->where('is_active', true)
            ->get();

        // 予約済みやお気に入りの処理を追加する場合は、indexメソッドと同様のロジックを適用する
        $reservedShopIds = [];
        $favoriteShopIds = [];

        if ($user) {
            $reservedShopIds = Reservation::where('user_id', $user->id)
                ->where('status', '予約済み')
                ->whereDate('reservation_date', '<', now())
                ->pluck('shop_id')
                ->toArray();

            $favoriteShopIds = $user->favorites->pluck('shop_id')->toArray();
        }

        $shops->each(function ($shop) use ($reservedShopIds, $favoriteShopIds) {
            $shop->isReserved = in_array($shop->id, $reservedShopIds);
            $shop->isFavorited = in_array($shop->id, $favoriteShopIds);
        });

        return view('index', compact('areas', 'genres', 'shops'));
    }

    //ハッシュタグ genre検索
    public function filterByGenre($genreName)
    {
        $user = Auth::user();
        $areas = Area::all();
        $genres = Genre::all();

        // 該当するジャンル名のレコードを取得
        $shops = Shop::with(['area', 'genre', 'images'])
            ->whereHas('genre', function ($query) use ($genreName) {
                $query->where('genre_name', $genreName);
            })
            ->where('is_active', true)
            ->get();

        // 予約済みやお気に入りの処理を追加する場合は、indexメソッドと同様のロジックを適用する
        $reservedShopIds = [];
        $favoriteShopIds = [];

        if ($user) {
            $reservedShopIds = Reservation::where('user_id', $user->id)
                ->where('status', '予約済み')
                ->whereDate('reservation_date', '<', now())
                ->pluck('shop_id')
                ->toArray();

            $favoriteShopIds = $user->favorites->pluck('shop_id')->toArray();
        }

        $shops->each(function ($shop) use ($reservedShopIds, $favoriteShopIds) {
            $shop->isReserved = in_array($shop->id, $reservedShopIds);
            $shop->isFavorited = in_array($shop->id, $favoriteShopIds);
        });

        return view('index', compact('areas', 'genres', 'shops'));
    }

    public function create()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('shop_create', compact('areas', 'genres'));
    }

    public function store(ShopRequest $request)
    {
        Shop::create($request->validated());
        return redirect()->route('shop.create')->with('success', '新しい店舗が追加されました！');
    }
}
