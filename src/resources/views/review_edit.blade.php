@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/review_edit.css')}}">
@endsection

@section('content')

<div class="review__content">
    <form id="uploadForm" class="dropzone" action="{{ route('review.update', [$shop, $review]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('本当に変更しますか？');">
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
        </div>
        @if($shop->reservations->isNotEmpty())
        <input type="hidden" name="reservation_id" value="{{ $shop->reservations->first()->id }}">
        @endif
        <div class="review__form__group">
            <h3>画像の編集</h3>
            <div id="error-messages" class="alert alert-danger"></div>
            @error('images.*')
            <p class="alert alert-danger">{{ $message }}</p>
            @enderror
            <div class="sell-edit__container--form-tag form-input--style input-file">
                @if ($review->images->isNotEmpty())
                @foreach ($review->images as $image)
                <img src="{{ $image->image_url }}" alt="Review Image" width="100">
                @endforeach
                @endif
                <label for="images" class="custom-file-label btn--border-pink--small">クリックして写真を追加</label>
                <input type="file" name="images[]" id="images" multiple style="display: none;">
                <div class="preview" id="preview"></div>
            </div>
            <div class="dropzone-previews"></div>
        </div>
    </form>
    <button id="submitButton" type="submit">口コミを編集する</button>
</div>

<script>
    document.getElementById('submitButton').addEventListener('click', function() {
        document.getElementById('uploadForm').submit();
    });
    Dropzone.options.uploadForm = {

        withCredentials: true,
        paramName: "images",
        autoProcessQueue: false,
        dictDefaultMessage: "ここに画像をドラッグ＆ドロップしてください",
        uploadMultiple: true,
        parallelUploads: 5,
        maxFiles: 5,
        addRemoveLinks: true,
        init: function() {
            var myDropzone = this;

            // 送信ボタンのクリックイベントを監視
            document.getElementById('submitButton').addEventListener('click', function(e) {
                e.preventDefault(); // デフォルトの送信を防ぐ
                e.stopPropagation();

                // 追加したフォームデータ
                myDropzone.on("sendingmultiple", function(files, xhr, formData) {
                    formData.append("rating", document.querySelector('input[name="rating"]:checked').value);
                    formData.append("comment", document.getElementById("comment").value);
                });

                // キューの処理を開始
                myDropzone.processQueue();
            });

            // アップロード成功時の処理
            this.on("successmultiple", function(files, response) {
                console.log("アップロードが成功しました。", response);
            });

            // アップロード失敗時の処理
            this.on("errormultiple", function(files, response) {
                // エラーメッセージ表示用の要素をクリア
                var errorMessages = document.getElementById('error-messages');
                errorMessages.innerHTML = '';

                files.forEach(file => {
                    var errorMessage = document.createElement('div');
                    errorMessage.className = 'alert alert-danger';

                    // サーバからのエラーメッセージを取得して表示
                    if (response.errors) {
                        for (const [field, messages] of Object.entries(response.errors)) {
                            messages.forEach(message => {
                                var errorText = document.createElement('div');
                                errorText.textContent = message;
                                errorMessages.appendChild(errorText);
                            });
                        }
                    } else {
                        errorMessage.textContent = 'アップロードに失敗しました。';
                        file.previewElement.appendChild(errorMessage);
                    }
                });

                console.error("アップロードに失敗しました。", response);
            });
        }
    }
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