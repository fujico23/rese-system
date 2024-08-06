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
use Exception;
use Illuminate\Support\Carbon;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        //メール認証していないユーザーに対してモーダルウィンドウで促す為に
        $emailVerified = true;
        if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
            $emailVerified = false;
        }

        $user = Auth::user();
        $areas = Area::all();
        $genres = Genre::all();
        $sortOption = $request->input('sort', 'default');

        // ソート処理
        switch ($sortOption) {
            case 'default':
                $shops = Shop::with(['area', 'genre', 'images', 'reservations.review'])
                    ->where('is_active', true)
                    ->get();
                break;
            case 'random':
                $shops = Shop::with(['area', 'genre', 'images', 'reservations.review'])
                    ->where('is_active', true)
                    ->inRandomOrder()
                    ->get();
                break;
            case 'view_many':
                $shops = Shop::with(['area', 'genre', 'images', 'reservations.review'])
                    ->where('is_active', true)
                    ->orderByRaw('views = 0, views DESC')
                    ->get();
                break;
            case 'view_few':
                $shops = Shop::with(['area', 'genre', 'images', 'reservations.review'])
                    ->where('is_active', true)
                    ->orderByRaw('views = 0, views ASC')
                    ->get();
                break;
        }

        //ログインしている場合、予約とお気に入りを取得
        if ($user) {
            // 予約している店舗のIDを取得後、予約が入っている店舗情報を取得
            //予約日時が過ぎた口コミ未送信のshopIdを取得
            $reservedShopIds = Reservation::where('user_id', $user->id)
                ->where('status', '予約済み')
                ->whereDate('reservation_date', '<', now())
                ->pluck('shop_id')
                ->toArray();
            // 各店舗に予約済みか、お気に入りに登録されているかのフラグを設定
            $favoriteShopIds = $user->favorites->pluck('shop_id')->toArray();
            $shops->each(function ($shop) use ($reservedShopIds, $favoriteShopIds) {
                $shop->isReserved = in_array($shop->id, $reservedShopIds);
                $shop->isFavorited = in_array($shop->id, $favoriteShopIds);
            });
        }
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
        $shop->increment('views');
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

    public function csvImport(Request $request)
    {
        if ($request->hasFile('csvFile')) {
            $file = $request->file('csvFile');
            $path = $file->getRealPath();

            $fp = fopen($path, 'r');

            fgetcsv($fp);

            while (($csvData = fgetcsv($fp)) !== FALSE) {
                $this->InsertCsvData($csvData);
            }
            fclose($fp);
        } else {
            throw new Exception('CSVファイルの取得に失敗しました');
        }
    }

    public function InsertCsvData($csvData)
    {
        /* $shop = Shop::create([
            'area_id' => $csvData[0],
            'genre_id' => $csvData[1],
            'shop_name' => $csvData[2],
            'description' => $csvData[3],
            'is_active' => $csvData[4],
        ]);
        */
        $shop = new Shop;
        $shop->area_id = $csvData[0];
        $shop->genre_id = $csvData[1];
        $shop->shop_name = $csvData[2];
        $shop->description = $csvData[3];
        $shop->is_active = $csvData[4];

        $shop->save();

        /* $image = new Image;

        $image->shop_id = $shop->id;
        $image->image_url = $csvData[5];
        */
    }
}
