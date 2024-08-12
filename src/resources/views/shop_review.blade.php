@extends('layouts/app')

@section('css')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/shop_review.css')}}">
@endsection

@section('content')
<div class="container">
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger review-alert-danger">{{$error}}</div>
    @endforeach
    <a class="return" href="{{ route('shop.detail', $shop) }}"> &lt;</a>
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
                <script>
                    Dropzone.autoDiscover = false;
                </script>
                <form id="uploadForm" action="{{ route('shop.review.store', $shop) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="review__form__group">
                        <div class="review__form__group__header rating-header">
                            <h3>体験を評価してください</h3>
                            <span class="require">必須</span>
                        </div>
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
                        <div class="review__form__group__header comment-header">
                            <h3>口コミを投稿</h3>
                            <span class="require">必須</span>
                        </div>
                        <textarea placeholder="カジュアルな夜のお出かけにおすすめのスポット" name="comment" id="comment" rows="4" cols="50"></textarea>
                        <div id="charCount">/400(最大文字数)</div>
                    </div>
                    @if($shop->reservations->isNotEmpty())
                    <input type="hidden" name="reservation_id" value="{{ $shop->reservations->first()->id }}">
                    @endif
                    <div class="review__form__group">
                        <h3 class="upload-header">画像の追加</h3>
                        <div id="myDropzone" class="dropzone"></div>
                    </div>
                </form>
            </div>
        </div>
        <button id="submitButton" type="button">口コミを投稿</button>
    </div>
</div>
<script>
    document.getElementById('comment').addEventListener('input', function() {
        var charCount = this.value.length;
        document.getElementById('charCount').innerText = charCount + '/400(最大文字数)';
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('uploadForm');
        var submitButton = document.getElementById('submitButton');

        // 手動でDropzoneを初期化する
        var myDropzone = new Dropzone('#myDropzone', {
            url: "{{ route('shop.review.store', $shop) }}",
            paramName: "images",
            dictDefaultMessage: 'クリックして写真を追加(またはドラッグアンドドロップ)',
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFilesize: 5,
            addRemoveLinks: true,
            dictRemoveFile: '削除',

            init: function() {
                var dropzoneInstance = this;

                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (dropzoneInstance.getQueuedFiles().length > 0) {
                        dropzoneInstance.on("sending", function(file, xhr, formData) {
                            formData.append("_token", document.querySelector('input[name="_token"]').value);
                            formData.append("comment", document.getElementById('comment').value);
                            var rating = document.querySelector('input[name="rating"]:checked');
                            if (rating) {
                                formData.append("rating", rating.value);
                            }
                        });

                        dropzoneInstance.processQueue();
                    } else {
                        form.submit();
                    }
                });


                this.on("successmultiple", function(files, response) {
                    // 成功した場合、指定されたURLにリダイレクト
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    }
                });

                this.on("errormultiple", function(files, response) {
                    // 既存のエラーメッセージ要素を探す
                    var errorMessages = document.getElementById('error-messages');
                    if (!errorMessages) {
                        // 新しくエラーメッセージのコンテナを作成する
                        errorMessages = document.createElement('div');
                        errorMessages.id = 'error-messages';
                        errorMessages.className = 'alert alert-danger';

                        // .containerの直下にエラーメッセージを挿入する
                        var container = document.querySelector('.container');
                        if (container) {
                            container.insertBefore(errorMessages, container.firstChild);
                        }
                    } else {
                        // 既存のエラーメッセージをクリアする
                        errorMessages.innerHTML = '';
                    }

                    // エラーメッセージをすべて表示
                    if (response.errors) {
                        for (let field in response.errors) {
                            response.errors[field].forEach(message => {
                                var errorText = document.createElement('div');
                                errorText.textContent = message;
                                errorMessages.appendChild(errorText);
                            });
                        }
                    }
                });
            }
        });
    });
</script>
@endsection