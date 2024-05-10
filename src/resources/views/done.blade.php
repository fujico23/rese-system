@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css')}}">
@endsection

@section('content')
<div class="thanks__container">
    <div class="thanks__container__inner">
        <h2 class="thanks__heading">ご予約ありがとうございます</h2>
        <div class="thanks__container-btn">
            <a class="thanks__container-btn-link btn" href="{{ route('shop.detail', $shop) }}">戻る</a>
        </div>
        <div class="thanks__container-btn--mypage">
            <a class="thanks__container-btn-link--mypage btn" href="{{ route('mypage') }}">マイページへ</a>
        </div>
    </div>
</div>
@endsection