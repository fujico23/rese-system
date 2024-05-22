@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_review.css')}}">
@endsection

@section('content')
<h2 class="review__heading">Review Form</h2>
<form class="review__form" action="{{ route('shop.review.store', $shop) }}" method="post">
    @csrf
    <div class="review__form__group">
        <p>{{ Auth::user()->name }} さん</p>
        <p><span>
                @if($reservation = $shop->reservations->first())
                {{ $reservation->reservation_date }}
                @endif
            </span>に
        </p>
        <p>「<span>{{ $shop->shop_name }}</span>」へご来店いただいた際の</p>
        <p>ご意見をお聞かせ下さい。</p>
        <p>今後の参考にさせていただきます!</p>
    </div>
    @error('rating')
    <p class="alert alert-danger">{{ $message }}</p>
    @enderror
    <div class="review__form__group-evaluation">
        <input id="star1" type="radio" name="rating" value="5" />
        <label for="star1"><span class="text">5</span>★</label>
        <input id="star2" type="radio" name="rating" value="4" />
        <label for="star2"><span class="text">4</span>★</label>
        <input id="star3" type="radio" name="rating" value="3" />
        <label for="star3"><span class="text">3</span>★</label>
        <input id="star4" type="radio" name="rating" value="2" />
        <label for="star4"><span class="text">2</span>★</label>
        <input id="star5" type="radio" name="rating" value="1" />
        <label for="star5"><span class="text">1</span>★</label>
    </div>
    @error('comment')
    <p class="alert alert-danger">{{ $message }}</p>
    @enderror
    <div class="review__form__group-textarea">
        <label class="review__form__group__label">コメント</label>
        <textarea name="comment" id="comment" rows="4" cols="50"></textarea>
    </div>
    @if($shop->reservations->isNotEmpty())
    <input type="hidden" name="reservation_id" value="{{ $shop->reservations->first()->id }}">
    @endif
    <input type="hidden" name="status" value="口コミ済み">
    <button type="submit">送信</button>
</form>
@endsection