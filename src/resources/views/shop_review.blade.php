@extends('layouts/app')

@section('css')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
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
                    <div class="review__form__group__header comment-header">
                        <h3>口コミを投稿</h3>
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
                    <h3 class="upload-header">画像の追加</h3>
                    <div class="sell-edit__container--form-tag form-input--style input-file">
                        <label for="images" class="custom-file-label btn--border-pink--small">クリックして写真を追加</label>
                        <input type="file" name="images[]" id="images" multiple style="display: none;">
                        <div class="preview" id="preview"></div>
                    </div>
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

                this.on("errormultiple", function(files, response) {
                    // error-messages要素が存在しない場合、新たに作成して配置
                    var errorMessages = document.getElementById('error-messages');
                    if (!errorMessages) {
                        errorMessages = document.createElement('div');
                        errorMessages.id = 'error-messages';
                        errorMessages.className = 'alert alert-danger';

                        // エラーメッセージを<h3 class="upload-header">の直後に挿入
                        var uploadHeader = document.querySelector('.upload-header');
                        if (uploadHeader) {
                            uploadHeader.parentNode.insertBefore(errorMessages, uploadHeader.nextSibling);
                        }
                    } else {
                        // 既存のエラーメッセージをクリア
                        errorMessages.innerHTML = '';
                    }

                    // 画像アップロードに関するエラーメッセージを表示
                    if (response.errors) {
                        // imagesまたはimages.*に関連するエラーのみ処理
                        const imageErrors = Object.entries(response.errors).filter(([field, messages]) => {
                            return field.startsWith('images');
                        });

                        // 画像関連のエラーメッセージを表示
                        imageErrors.forEach(([field, messages]) => {
                            messages.forEach(message => {
                                var errorText = document.createElement('div');
                                errorText.textContent = message;
                                errorMessages.appendChild(errorText);
                            });
                        });

                        // 画像関連のエラーメッセージがない場合のフォールバック
                        if (imageErrors.length === 0) {
                            var errorMessage = document.createElement('div');
                            errorMessage.className = 'alert alert-danger';
                            errorMessage.textContent = '画像アップロードに失敗しました。';
                            errorMessages.appendChild(errorMessage);
                        }
                    } else {
                        var errorMessage = document.createElement('div');
                        errorMessage.className = 'alert alert-danger';
                        errorMessage.textContent = 'アップロードに失敗しました。';
                        errorMessages.appendChild(errorMessage);
                    }

                    // commentとratingのエラーメッセージの表示処理
                    var commentHeader = document.querySelector('.comment-header');
                    var ratingHeader = document.querySelector('.rating-header');

                    // 既存のエラーメッセージをクリア
                    if (commentHeader) {
                        var existingCommentErrors = commentHeader.nextSibling;
                        if (existingCommentErrors && existingCommentErrors.classList && existingCommentErrors.classList.contains('alert-danger')) {
                            existingCommentErrors.remove();
                        }
                    }
                    if (ratingHeader) {
                        var existingRatingErrors = ratingHeader.nextSibling;
                        if (existingRatingErrors && existingRatingErrors.classList && existingRatingErrors.classList.contains('alert-danger')) {
                            existingRatingErrors.remove();
                        }
                    }

                    // commentのエラー表示
                    if (response.errors.comment && commentHeader) {
                        var commentErrorMessages = document.createElement('div');
                        commentErrorMessages.className = 'alert alert-danger';
                        response.errors.comment.forEach(message => {
                            var errorText = document.createElement('div');
                            errorText.textContent = message;
                            commentErrorMessages.appendChild(errorText);
                        });
                        commentHeader.parentNode.insertBefore(commentErrorMessages, commentHeader.nextSibling);
                    }

                    // ratingのエラー表示
                    if (response.errors.rating && ratingHeader) {
                        var ratingErrorMessages = document.createElement('div');
                        ratingErrorMessages.className = 'alert alert-danger';
                        response.errors.rating.forEach(message => {
                            var errorText = document.createElement('div');
                            errorText.textContent = message;
                            ratingErrorMessages.appendChild(errorText);
                        });
                        ratingHeader.parentNode.insertBefore(ratingErrorMessages, ratingHeader.nextSibling);
                    }
                });
            }
        });
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