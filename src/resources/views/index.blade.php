@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="card-container">
  @foreach ($shops as $shop)
  <div class="card">
  <div class="shop__img">
  @if ($shop->images->isNotEmpty())
    @if (Str::startsWith($shop->images->first()->image_url, 'http')) <!-- S3のURLかどうかを確認 -->
      <img src="{{ $shop->images->first()->image_url }}" alt="{{ $shop->shop_name }}">
    @else
    <img src="{{ asset('storage/' . $shop->images->first()->image_url) }}" alt="{{ $shop->shop_name }}">
     <!--　本番環境の場合
     <img src="{{ Storage::url($shop->images->first()->image_url) }}" alt="Shop Image"> -->

    @endif
  @else
    <p>準備中です</p>
  @endif
</div>
    <div class="card__details">
        <h2 class="card__details__name">{{ $shop->shop_name }}</h2>
        <span class="card__details__area hashtag">#{{ $shop->area->area_name }}</span>
        <span class="card__details__genre hashtag">#{{ $shop->genre->genre_name }}</span>
        @if($shop->isReserved)
        <i class="fa-solid fa-square-pen fa-shake fa-xl" style="color: #FFD43B;"></i>
        @endif
    </div>
    <div class="card__footer">
      <a href="{{ route('shop.detail', $shop) }}" class="card__footer-btn btn">詳しく見る</a>
      @if ($shop->isFavorited)
      <form class="card__footer__form-delete" id="favorite-delete-form" action="{{ route('favorite.delete') }}" method="POST">
        @method('delete')
        @csrf
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <button class="heart heart-grey" type="submit">
          <i class="fa-solid fa-heart" style="color: #fc030f;"></i>
        </button>
      </form>
      @else
      <form  class="card__footer__form-post" id="" action="{{ route('favorite.add') }}" method="POST">
        @csrf
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <button class="heart heart-grey" type="submit">
          <i class="fa-solid fa-heart"></i>
        </button>
      </form>
      @endif
    </div>
  </div>
  @endforeach
</div>
@endsection
