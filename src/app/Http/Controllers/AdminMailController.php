<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AdminMail;
use App\Mail\AdminMailAll;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdminMailController extends Controller
{
    public function send(Request $request, User $user)
    {
        $details = [
            'title' => '常連様限定！',
            'body' => 'リピーター向け特別割引!!'
        ];

        Mail::to($request->email)->send(new AdminMail($details));

        return back()->with('success', 'メールが送信されました！');
    }

    public function sendToAllUsers()
    {
        //全ユーザーを取得
        $users = User::all();

        //各ユーザーに対してメールを送信
        foreach ($users as $user)
        {
            $details = [
                'title' => 'Reseより大切なお知らせ',
                'body' => '夜間アプリケーションシステムの休止について'
            ];

            Mail::to($user->email)->send(new AdminMailAll($details));
        }

        return back()->with('success', '全ユーザーにメールが送信されました！');
    }
}
