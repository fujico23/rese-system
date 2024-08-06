@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review_edit.css')}}">
@endsection

@section('content')
<form class="review__form" action="{{ route('review.update', [$shop, $review]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('本当に変更しますか？');">
    @csrf
    @method('patch')
    <div class="review__content">
        <div class="shop-card__group">
            <h2 class="review__heading">{{ $shop->shop_name }}に対する口コミを編集します</h2>
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
                    <span class="card__details__area hashtag" onclick="window.location.href='{{ route('shops.filterByArea', ['areaName' => $shop->area->area_name]) }}'">
                        #{{ $shop->area->area_name }}
                    </span>
                    <span class="card__details__genre hashtag" onclick="window.location.href='{{ route('shops.filterByGenre', ['genreName' => $shop->genre->genre_name]) }}'">
                        #{{ $shop->genre->genre_name }}
                    </span>
                </div>
                <div class="card__footer">
                    <a href="{{ route('shop.detail', $shop) }}" class="card__footer-btn btn">詳しく見る</a>
                    @if ($shop->isFavorited)
                    <p class="heart heart-grey">
                        <i class="fa-solid fa-heart" style="color: #fc030f;"></i>
                    </p>
                    @else
                    <p class="heart heart-grey">
                        <i class="fa-solid fa-heart"></i>
                    </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="review">
            @csrf
            <div class="review__form__group">
                <h3>体験を編集してください</h3>
                @error('rating')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="review__form__group-evaluation">
                    <input id="star1" type="radio" name="rating" value="5" {{ $review->rating == 5 ? 'checked' : '' }} />
                    <label for="star1"><span class="text"></span>★</label>
                    <input id="star2" type="radio" name="rating" value="4" {{ $review->rating == 4 ? 'checked' : '' }} />
                    <label for="star2"><span class="text"></span>★</label>
                    <input id="star3" type="radio" name="rating" value="3" {{ $review->rating == 3 ? 'checked' : '' }} />
                    <label for="star3"><span class="text"></span>★</label>
                    <input id="star4" type="radio" name="rating" value="2" {{ $review->rating == 2 ? 'checked' : '' }} />
                    <label for="star4"><span class="text"></span>★</label>
                    <input id="star5" type="radio" name="rating" value="1" {{ $review->rating == 1 ? 'checked' : '' }} />
                    <label for="star5"><span class="text"></span>★</label>
                </div>
            </div>
            <div class="review__form__group-textarea">
                <h3 class="review__form__group__label">口コミを編集</h3>
                @error('comment')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <textarea name="comment" id="comment" rows="4" cols="50">{{ $review->comment }}</textarea>
            </div>
            @if($shop->reservations->isNotEmpty())
            <input type="hidden" name="reservation_id" value="{{ $shop->reservations->first()->id }}">
            @endif
            <div class="review__form__group">
                <h3>画像の編集</h3>
                @error('image_url')
                <p class="alert alert-danger"> {{ $message }}</p>
                @enderror
                @error('image_url.*')
                <p class="alert alert-danger">{{ $message }}</p>
                @enderror
                <div class="sell-edit__container--form-tag form-input--style input-file">
                    <label for="image_url" class="custom-file-label btn--border-pink--small">クリックして写真を追加</label>
                    <input type="file" name="image_url[]" id="image_url" multiple style="display: none;">
                    <div class="preview" id="preview"></div>
                </div>
            </div>
            <input type="hidden" name="status" value="口コミ済み">
        </div>
    </div>
    <button type="submit">口コミを編集する</button>
</form>
<script>
    //ファイル選択時に選ばれたファイルを一時的に保持するための配列
    let selectedFiles = [];

    // ファイルが選ばれた時のイベントリスナー
    document.getElementById('image_url').addEventListener('change', function(event) {
        let files = event.target.files;
        let preview = document.getElementById('preview');

        // ファイルの情報を取得し、selectedFiles配列に追加
        selectedFiles = [...files];
        preview.innerHTML = '';

        selectedFiles.forEach(file => {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection