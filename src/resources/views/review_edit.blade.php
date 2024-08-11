@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/review_edit.css')}}">
@endsection

@section('content')

<div class="review__content">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <a class="return" href="{{ route('shop.detail', $shop) }}"> &lt;</a>
    <form id="reviewForm" action="{{ route('review.update', [$shop, $review]) }}" method="post">
        @csrf
        @method('patch')
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
            <div id="charCount">/400(最大文字数)</div>
        </div>
        <button id="submitReviewButton" type="submit">口コミを編集する</button>
        @if($shop->reservations->isNotEmpty())
        <input type="hidden" name="reservation_id" value="{{ $shop->reservations->first()->id }}">
        @endif
    </form>

    <!-- 画像アップロード用のフォーム -->
    <div class="review__form__group">
        <h3 class="upload-header">画像の編集</h3>
        @error('images.*')
        <p class="alert alert-danger">{{ $message }}</p>
        @enderror
        @if ($review->images->isNotEmpty())
        <form action="{{ route('reviews.images.delete-multiple', $review->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="input-file">
                @foreach ($review->images as $image)
                <img src="{{ $image->image_url }}" alt="Review Image" width="100">
                <input type="checkbox" name="images[]" value="{{ $image->id }}">
                @endforeach
            </div>
            <button type="submit" onclick="return confirm('選択した画像を削除してもよろしいですか？');">選択した画像を削除</button>
        </form>
        @endif
        <form id="uploadForm" class="dropzone" action="{{ route('image.upload', [$shop, $review]) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="dropzone-previews"></div>
            <input type="hidden" name="review_id" value="{{ $review->id }}">
        </form>
        <button id="submitUploadButton" type="button">画像をアップロードする</button>
    </div>
</div>

<script>
    document.getElementById('submitReviewButton').addEventListener('click', function() {
        document.getElementById('reviewForm').submit();
    });

    document.getElementById('comment').addEventListener('input', function() {
        var charCount = this.value.length;
        document.getElementById('charCount').innerText = charCount + '/400(最大文字数)';
    });

    Dropzone.options.uploadForm = {
        withCredentials: true,
        paramName: "images",
        autoProcessQueue: false,
        dictDefaultMessage: "クリックして写真を追加(またはドロッグアンドドラッグ)",
        uploadMultiple: true,
        parallelUploads: 5,
        maxFiles: 5,
        addRemoveLinks: true,
        init: function() {
            var myDropzone = this;

            // 画像アップロード専用の送信ボタンを用意する
            document.getElementById('submitUploadButton').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // 追加したフォームデータ
                myDropzone.on("sending", function(files, xhr, formData) {
                    formData.append("review_id", document.querySelector('input[name="review_id"]').value);
                });
                myDropzone.processQueue();
            });

            this.on("successmultiple", function(files, response) {
                console.log("アップロードが成功しました。", response);
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

                // エラーメッセージの追加処理
                files.forEach(file => {
                    if (response.errors) {
                        for (const [field, messages] of Object.entries(response.errors)) {
                            messages.forEach(message => {
                                var errorText = document.createElement('div');
                                errorText.textContent = message;
                                errorMessages.appendChild(errorText);
                            });
                        }
                    } else {
                        var errorMessage = document.createElement('div');
                        errorMessage.className = 'alert alert-danger';
                        errorMessage.textContent = 'アップロードに失敗しました。';
                        errorMessages.appendChild(errorMessage);
                    }
                });
                console.error("アップロードに失敗しました。", response);
            });
        }
    }
</script>

@endsection