@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mail/create_user_mail.css') }}">
@endsection

@section('content')
<h2 class="user-mail__heading">個別メール Form</h2>
<div class="user-mail--return">
    <a class="user-mail--return__inner" href="/admin/users/{{ $user->id }}">return</a>
</div>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@elseif (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<form class="user-mail__form" method="POST" action="{{ route('admin.users.mail', $user)  }}">
    @csrf
    <div class="user-mail__form__group--title">
        <label for="title">メール件名</label>
        <div class="user-mail__form__group--title__item">
            <span class="user-mail__form__group--title--app">【Rese】</span>
            <span class="user-mail__form__group--title--fixed fixed">※固定</span>
        </div>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="user-mail__form__group--body">
        <label for="body">メール本文</label>
        <div class="user-mail__form__group--body__item">
            <span class="user-mail__form__group--body--beginning">いつも飲食店予約アプリケーション『Rese』をご利用いただき、誠にありがとうございます。</span>
            <span class="user-mail__form__group--body--fixed fixed">※固定</span>
        </div>
        <textarea id="body" name="body" required></textarea>
        <div class="user-mail__form__group--body__item">
            <span class="user-mail__form__group--body--closing-words">今後もより良いサービスを提供出来るよう尽力させていただきますので『Rese』のご利用を何卒宜しくお願い申し上げます。</span>
            <span class="user-mail__form__group--body--fixed fixed">※固定</span>
        </div>
        <p class="user-mail__form__group--body--app">Rese</p>
    </div>
    <button class="user-mail__form__submit-btn" type="submit">ユーザーに送信</button>
</form>
@endsection