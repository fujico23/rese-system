<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AdminMail;
use App\Mail\AdminMailAll;
use App\Models\User;
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

        Mail::to($user->email)->send(new AdminMail($title, $body, $user));

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
