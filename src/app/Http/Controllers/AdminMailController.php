<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AdminMail;
use App\Mail\AdminMailAll;
use App\Mail\Coupon;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AdminMailController extends Controller
{
    public function createUserMail(User $user)
    {
        return view('emails.create_user_mail', compact('user'));
    }

    public function sendUserMail(Request $request, User $user)
    {
        $title = $request->input('title');
        $body = $request->input('body');
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate(route('index)'));
            //('http://localhost');

        //本番環境の場合、publicをs3に変更
        $fileName = 'qrcode.png'; //ファイルの名前を設定
        Storage::disk('public')->put($fileName, $qrCode);
        $filePath = Storage::disk('public')->url($fileName);

        Mail::to($user->email)->send(new AdminMail($title, $body, $user, $qrCode, $filePath));

        return back()->with('success', 'メールが送信されました！');
    }

    public function createAllMail()
    {
        return view('emails.create_all_mail');
    }

    public function sendAllMail(Request $request)
    {
        $title = $request->input('title');
        $body = $request->input('body');
        $users = User::all();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new AdminMailAll($title, $body));
        }

        return back()->with('success', '全ユーザーにメールが送信されました！');
    }

    public function sendCoupon(User $user)
    {
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate(route('coupon.index'));
            //('http://localhost/coupon');

        //本番環境の場合、publicをs3に変更
        $fileName = 'coupon.png';
        Storage::disk('public')->put($fileName, $qrCode);
        $fileUrl = Storage::disk('public')->url($fileName);

        Mail::to($user->email)->send(new Coupon($user, $fileUrl));

        return back()->with('success', 'クーポン付メールが送信されました');
    }
}
