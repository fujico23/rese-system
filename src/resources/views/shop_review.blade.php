@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_review.css')}}">
@endsection

@section('content')
<div class="content">

    <div class="review__content">
        <div class="shop-card__group">
            <h2 class="review__heading">今回のご利用はいかがでしたか？</h2>
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
                    <span class="card__details__area hashtag">
                        #{{ $shop->area->area_name }}
                    </span>
                    <span class="card__details__genre hashtag">
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
            <form id="uploadForm" class="dropzone" action="{{ route('shop.review.store', $shop) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="review__form__group">
                    <div class="review__form__group__header">
                        <h3>体験を評価してください</h3>
                        <span class="require">必須</span>
                    </div>
                    @error('rating')
                    <p class="alert alert-danger">{{ $message }}</p>
                    @enderror
                    <div class="review__form__group-evaluation">
                        <input id="star1" type="radio" name="rating" value="5" />
                        <label for="star1"><span class="text"></span>★</label>
                        <input id="star2" type="radio" name="rating" value="4" />
                        <label for="star2"><span class="text"></span>★</label>
                        <input id="star3" type="radio" name="rating" value="3" />
                        <label for="star3"><span class="text"></span>★</label>
                        <input id="star4" type="radio" name="rating" value="2" />
                        <label for="star4"><span class="text"></span>★</label>
                        <input id="star5" type="radio" name="rating" value="1" />
                        <label for="star5"><span class="text"></span>★</label>
                    </div>
                </div>
                <div class="review__form__group-textarea">
                    <div class="review__form__group__header">
                        <h3 class="review__form__group__label">口コミを投稿</h3>
                        <span class="require">必須</span>
                    </div>
                    @error('comment')
                    <p class="alert alert-danger">{{ $message }}</p>
                    @enderror
                    <textarea placeholder="カジュアルな夜のお出かけにおすすめのスポット" name="comment" id="comment" rows="4" cols="50"></textarea>
                    <div id="charCount">/400(最大文字数)</div>
                </div>
                @if($shop->reservations->isNotEmpty())
                <input type="hidden" name="reservation_id" value="{{ $shop->reservations->first()->id }}">
                @endif
                <div class="review__form__group">
                    <h3>画像の追加</h3>
                    @error('images.*')
                    <p class="alert alert-danger">{{ $message }}</p>
                    @enderror
                    <div class="sell-edit__container--form-tag form-input--style input-file">
                        <label for="images" class="custom-file-label btn--border-pink--small">クリックして写真を追加</label>
                        <input type="file" name="images[]" id="images" multiple style="display: none;">
                        <div class="preview" id="preview"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <button id="submitButton" type="submit">口コミを投稿</button>
</div>
<script>
    document.getElementById('submitButton').addEventListener('click', function() {
        var form = document.getElementById('uploadForm');
        form.submit();
    });
    document.getElementById('comment').addEventListener('input', function() {
        var charCount = this.value.length;
        document.getElementById('charCount').innerText = charCount + '/400(最大文字数)';
    });
</script>
<script>
    //ファイル選択時に選ばれたファイルを一時的に保持するための配列
    let selectedFiles = [];

    // ファイルが選ばれた時のイベントリスナー
    document.getElementById('images').addEventListener('change', function(event) {
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