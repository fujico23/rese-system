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
use App\Http\Controllers\ManagementController;



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


//'role'にて全ページに配置されているメニューバーをrole_idによって変更
Route::middleware('role')->group(function () {
    //ログインしなくても一覧ページと詳細ページは閲覧可能
    Route::get('/thanks', [AuthController::class, 'thanks']);
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
    Route::get('/detail/{shop}', [ShopController::class, 'show'])->name('shop.detail');
    //会員登録かつメール認証後にお気に入り機能・マイページ閲覧・予約機能可能
    Route::middleware('auth', 'verified')->group(function () {
        Route::post('/favorites/add', [FavoriteController::class, 'store'])->name('favorite.add');
        Route::delete('/favorites/delete', [FavoriteController::class, 'destroy'])->name('favorite.delete');

        Route::get('/mypage', [UserController::class, 'index'])->name('mypage');
        Route::delete('mypage/favorite/delete', [UserController::class, 'destroy'])->name('mypage.favorite.delete');

        Route::post('/detail/{shop}/reservation', [ReservationController::class, 'store']);
        Route::get('/done', [ReservationController::class, 'done']);
        Route::delete('/mypage/reservation/{id}', [ReservationController::class, 'destroy'])->name('mypage.reservation.delete');
        Route::patch('/mypage/reservation/{id}', [ReservationController::class, 'update'])->name('mypage.reservation.update');
        //role_id 1 もしくは　2のみ店舗管理ページに遷移出来る
        Route::middleware('shop.management')->group(function () {
            Route::get('/shop/management', [ManagementController::class, 'index'])->name('management');
            Route::patch('/management/edit/{shop}', [ManagementController::class, 'update'])->name('management.edit');
            Route::get('/shop/reservation/confirm', [ManagementController::class, 'show']);
            Route::delete('/shop/management//image/delete/{image}', [ManagementController::class, 'destroy'])->name('images.delete');
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

    //role_id 1のみが管理画面遷移出来る
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'admin']);
        Route::get('/admin/users/{user}', [AdminController::class, 'show'])->name('users.show');
        Route::patch('/admin/users/{user}/update', [AdminController::class, 'update'])->name('role.update');
        Route::post('/admin/users/{user}/assign', [AdminController::class, 'store'])->name('admin.users.assign');
        Route::delete('/admin/users/{user}/remove', [AdminController::class, 'remove'])->name('admin.users.remove');
        Route::get('admin/shop/create', [ShopController::class, 'create'])->name('shop.create');
        Route::post('admin/shop/post', [ShopController::class, 'store'])->name('shops.store');
        Route::delete('admin/shop/delete', [ShopController::class, 'destroy'])->name('shop.delete');
    });
});
