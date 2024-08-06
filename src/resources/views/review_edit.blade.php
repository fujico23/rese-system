@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review_edit.css')}}">
@endsection

@section('content')
<form class="review__form" action="{{ route('review.update', [$shop, $review]) }}" method="post" enctype="multipart/form-data" onsubmit="return confirm('本当に変更しますか？');">
    @csrf
    @method('patch')
    <div class="review__content">
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
        </div>
    </div>
    <button type="submit">口コミを編集する</button>
</form>
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