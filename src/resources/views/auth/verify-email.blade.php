@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/authentication.css')}}">
@endsection

@section('content')
<div class="auth__container">
    <h2 class="auth__container__heading">{{ __('メールアドレス認証のお願い') }}</h2>
    <form class="auth-form" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <div class="auth-form__inner">
            @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('あなたのEメールアドレスに新しい認証リンクが送信されました。') }}
            </div>
            @endif

            <ul class="auth-form__group-list">
                <li>{{ __('お気に入り店舗登録') }}</li>
                <li>{{ __('予約') }}</li>
                <li>{{ __('Mypage') }}</li>
                <li>{{ __('TAKEOUT') }}</li>
            </ul>
            <p class="auth-form__group">{{ __('上記サービスはメール認証後ご利用いただけます。') }}</p>
            <p class="auth-form__group">{{ __('会員登録時に設定したメールアドレスに認証メールが届いていないかご確認をお願いいたします。') }}</p>
            <p class="auth-form__group">{{ __('認証メールが届いていない場合、下のリンクをクリックして再度メールをリクエストして下さい。') }}</p>
            <div class="auth-form__btn">
                <button type="submit" class="auth-form__group__button btn">{{ __('再送信') }}</button>
            </div>
        </div>
    </form>
</div>
@endsection