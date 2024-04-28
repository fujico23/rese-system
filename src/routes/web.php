<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMailController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StripeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//(FormRequest使用の為)Fortifyの認証機能をオーバーライド
$limiter = '15,60';
Route::post('/login', [LoginController::class, 'store'])
        ->middleware(([
            'guest',
            $limiter ? 'throttle:'.$limiter : null,
        ]));

//'role'にて全ページに配置されているメニューバーをrole_idによって変更
Route::middleware('role')->group(function () {
    //ログインしなくても一覧ページと詳細ページは閲覧可能
    Route::get('/thanks', [AuthController::class, 'thanks']);
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
    Route::get('/detail/{shop}', [ShopController::class, 'show'])->name('shop.detail');
    Route::get('detail/{shop}/review/index', [ReviewController::class, 'index'])->name('shop.review.index');
    //会員登録かつメール認証後にお気に入り機能・マイページ閲覧・予約機能可能
    Route::middleware('auth', 'verified')->group(function () {
        //お気に入り機能(indexページ)
        Route::post('/favorites/add', [FavoriteController::class, 'store'])->name('favorite.add');
        Route::delete('/favorites/delete', [FavoriteController::class, 'destroy'])->name('favorite.delete');
        //マイページ(閲覧・お気に入り削除)
        Route::get('/mypage', [UserController::class, 'index'])->name('mypage');
        Route::delete('mypage/favorite/delete', [UserController::class, 'destroy'])->name('mypage.favorite.delete');

        //予約機能(detailページ・mypage)
        Route::post('/detail/{shop}/reservation', [ReservationController::class, 'store']);
        Route::get('/done', [ReservationController::class, 'done']);
        Route::delete('/mypage/reservation/{id}', [ReservationController::class, 'destroy'])->name('mypage.reservation.delete');
        Route::patch('/mypage/reservation/{id}', [ReservationController::class, 'update'])->name('mypage.reservation.update');

        //決済機能
        Route::get('/detail/{shop}/stripe', [StripeController::class, 'index'])->name('stripe');
        Route::post('change', [StripeController::class, 'charge'])->name('stripe.charge');

        //レビュー機能
        Route::get('/detail/{shop}/review', [ReviewController::class, 'create'])->name('shop.review.create');
        Route::post('/detail/{shop}/review/store', [ReviewController::class, 'store'])->name('shop.review.store');
        Route::get('detail/review/done', [ReviewController::class, 'done']);

        //(店舗編集機能・予約閲覧機能(role_id 1 もしくは　2のみ遷移)
        Route::middleware('shop.management')->group(function () {
            Route::get('/shop/management', [ManagementController::class, 'index'])->name('management');
            Route::patch('/management/edit/{shop}', [ManagementController::class, 'update'])->name('management.edit');
            Route::delete('/shop/management/image/delete', [ManagementController::class, 'destroy'])->name('images.delete');
            Route::get('/shop/reservation/confirm', [ManagementController::class, 'show'])->name('reservation.confirm');
            Route::post('/shop/reservation/{reservation}/update/status', [ManagementController::class, 'updateStatus'])->name('status.update');
        });
    });

    //メール確認の通知
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    //メール確認のリンクをクリックした後の処理
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/thanks');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    //メール確認の再送信
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

    //管理画面機能（role_id 1のみ遷移）
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'admin']);
        Route::get('/admin/users/{user}', [AdminController::class, 'show'])->name('users.show');
        Route::patch('/admin/users/{user}/update', [AdminController::class, 'update'])->name('role.update');
        Route::post('/admin/users/{user}/assign', [AdminController::class, 'store'])->name('admin.users.assign');
        Route::delete('/admin/users/{user}/remove', [AdminController::class, 'remove'])->name('admin.users.remove');
        Route::get('admin/shop/create', [ShopController::class, 'create'])->name('shop.create');
        Route::post('admin/shop/post', [ShopController::class, 'store'])->name('shops.store');
        Route::delete('admin/shop/delete', [ShopController::class, 'destroy'])->name('shop.delete');
        Route::post('admin/users/{user}/mail', [AdminMailController::class, 'send'])->name('admin.users.mail');
        Route::post('admin/users/mail', [AdminMailController::class, 'sendToAllUsers'])->name('admin.all.users.mail');
    });
});
