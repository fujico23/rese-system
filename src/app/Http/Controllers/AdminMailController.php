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
}
