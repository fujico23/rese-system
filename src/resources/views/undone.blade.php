@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css')}}">
@endsection

@section('content')
<div class="thanks__container">
    <div class="thanks__container__inner">
        <h2 class="thanks__heading">ご予約ありがとうございます</h2>
        <p style="color:red"> - お支払いは済んでいません - </p>
        <div class="thanks__container-btn--mypage">
            <a class="thanks__container-btn-link--mypage btn" href="{{ route('mypage') }}">マイページへ</a>
        </div>
    </div>
</div>
@endsection