@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mail/create_all_mail.css') }}">
@endsection

@section('content')
<h2 class="all-mail__heading">一斉メール送信 Form</h2>
<div class="all-mail--return">
    <a class="all-mail--return__inner" href="/admin">return</a>
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
<form class="all-mail__form" method="POST" action="{{ route('admin.all.users.mail') }}">
    @csrf
    <div class="all-mail__form__group--title">
        <label for="title">メール件名</label>
        <div class="all-mail__form__group--title__item">
            <span class="all-mail__form__group--title--app">【Rese】</span>
            <span class="all-mail__form__group--title--fixed fixed">※固定</span>
        </div>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="all-mail__form__group--body">
        <label for="body">メール本文</label>
        <div class="all-mail__form__group--body__item">
            <span class="all-mail__form__group--body--beginning">いつも飲食店予約アプリケーション『Rese』をご利用いただき、誠にありがとうございます。</span>
            <span class="all-mail__form__group--body--fixed fixed">※固定</span>
        </div>
        <textarea id="body" name="body" required></textarea>
        <div class="all-mail__form__group--body__item">
            <span class="all-mail__form__group--body--closing-words">今後もより良いサービスを提供出来るよう尽力させていただきますので『Rese』のご利用を何卒宜しくお願い申し上げます。</span>
            <span class="all-mail__form__group--body--fixed fixed">※固定</span>
        </div>
        <p class="all-mail__form__group--body--app">Rese</p>
    </div>
    <button class="all-mail__form__submit-btn" type="submit">全ユーザーに送信</button>
</form>
@endsection