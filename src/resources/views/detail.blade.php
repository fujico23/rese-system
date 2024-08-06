@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')

<!-- ショップ情報 -->
<div class="shop-container">
    <div class="shop__heading">
        <a class="return" href="/">&lsaquo;</a>
        <h2 class="shop-name">{{ $shop->shop_name }}</h2>
        @auth
        <a class="shop__heading-takeout" href="{{ route('stripe', $shop) }}">TAKE OUT</a>
        @endauth
    </div>
    <div class="shop__img">
        @if ($shop->images->isNotEmpty())
        @if (Str::startsWith($shop->images->first()->image_url, 'http')) <!-- S3のURLかどうかを確認 -->
        <img src="{{ $shop->images->first()->image_url }}" alt="{{ $shop->shop_name }}">
        @else
        <img src="{{ asset('storage/' . $shop->images->first()->image_url) }}" alt="{{ $shop->shop_name }}">
        <!-- 本番環境の場合
        <img src="{{ Storage::disk('s3')->url($shop->images->first()->image_url) }}" alt="{{ $shop->shop_name }}"> -->
        @endif
        @else
        <p>準備中です</p>
        @endif
    </div>

    <div class="shop-details">
        <div class="hashtag">
            <span class="shop-area">#{{ $shop->area->area_name }}</span>
            <span class="shop-genre">#{{ $shop->genre->genre_name }}</span>
        </div>
        <div class="shop-description">
            <p>{{ $shop->description }}</p>
        </div>
        <!-- 予約情報がある場合にレビューを記述するリンクを表示 -->
        @if(!empty($reservations))
        <a href="{{ route('shop.review.create', $shop) }}" class="review-link">口コミを投稿する</a>
        @endif
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <details class="admin-table-comment-description">
            <summary class="reviews-index">全ての口コミ情報</summary>
            <ul class="review-section__container">
                @foreach ($reservations as $reservation)
                @if ($reservation->review)
                <li class="review-section__container__group">
                    <div class="review-section__container__group__inner review-area">
                        @if (Auth::check())
                        <div class="review-edit-delete__group">
                            @if ($user->id === $reservation->user_id)
                            <a href="{{ route('review.edit', [$shop, $reservation]) }}">口コミを編集 </a>
                            @endif
                            @if ($user->role_id === 1 || $user->id === $reservation->user_id)
                            <form action="{{ route('review.delete', $reservation) }}" method="post" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('delete')
                                <button type="submit" name="review_id">口コミを削除</button>
                            </form>
                            @endif
                        </div>
                        @endif
                        <p class="name">{{ $reservation->user->name }}さん</p>
                        <p class="star{{ $reservation->review->rating }}"></p>
                        <p class="comment">{{ $reservation->review->comment }}</p>
                        @if ($reservation->review->images->isNotEmpty())
                        <div>
                            @foreach ($reservation->review->images as $image)
                            <img src="{{ $image->image_url }}" alt="Review Image" width="100">
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <a href="#"></a>
                </li>
                @endif
                @endforeach
            </ul>
        </details>
    </div>
</div>

<!-- 予約情報-->
<div class="reservation-container">
    <div class="reservation-container__inner">
        <form class="reservation-form" action="/detail/{shop}/reservation" method="post">
            @csrf
            <div class="reservation-form__inner">
                <h2 class="reservation__heading">予約</h2>
                <div class="reservation-date form__tag">
                    <input type="date" name="reservation_date" id="reservation_date" placeholder="予約日を選択してください" value="{{ old('reservation_date') }}">
                </div>
                <p class="reservation-error">
                    @error('reservation_date')
                    {{ $message }}
                    @enderror
                </p>
                <div class="reservation-time form__tag">
                    <select name="reservation_time">
                        <option value="">予約時間を選択してください</option>
                        @foreach($reservationTimes as $time)
                        <option value="{{ $time }}" {{ old('reservation_time') == $time ? 'selected' : '' }}>
                            {{ $time }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <p class="reservation-error">
                    @error('reservation_time')
                    {{ $message }}
                    @enderror
                </p>
                <div class="numer-of-guests form__tag">
                    <select name="number_of_guests" id="number_of_guests">
                        <option value="">予約人数を選択してください</option>
                        @for ($count = 1; $count <= 20; $count++) <option value="{{ $count }}" {{ old('number_of_guests') == $count ? 'selected' : '' }}>
                            {{ $count }}人
                            </option>
                            @endfor
                    </select>
                </div>
                <p class="reservation-error">
                    @error('number_of_guests')
                    {{ $message }}
                    @enderror
                </p>
                <div class="course form__tag">
                    <select name="course_id">
                        <option value="">予約コースを選択してください</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-price="{{ $course->price }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }} - ¥{{ number_format($course->price) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <p class="reservation-error">
                    @error('course_id')
                    {{ $message }}
                    @enderror
                </p>
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">

                <table class="reservation__table">
                    <tr class="table__row">
                        <td>Shop</td>
                        <td>{{ $shop->shop_name }}</td>
                    </tr>
                    <tr class="table__row">
                        <td>Date</td>
                        <td id="reservation_date_display">選択して下さい</td>
                    </tr>
                    <tr class="table__row">
                        <td>Time</td>
                        <td id="reservation_time">選択して下さい</td>
                    </tr>
                    <tr class="table__row">
                        <td>Number</td>
                        <td id="guest_count">選択して下さい</td>
                    </tr>
                    <tr class="table__row">
                        <td>合計金額</td>
                        <td id="total_price">0円</td>
                    </tr>
                </table>
            </div>

            <div class="reservation__btn">
                <button class="btn" type="submit" id="submit-button" name="reservation_button">予約する</button>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var reservationDateElement = document.getElementById('reservation_date');
        var reservationDateDisplayElement = document.getElementById('reservation_date_display');
        reservationDateElement.addEventListener('change', function() {
            reservationDateDisplayElement.textContent = reservationDateElement.value;
        });

        var selectReservationTimeElement = document.querySelector('select[name="reservation_time"]');
        var reservationTimeElement = document.getElementById('reservation_time');
        selectReservationTimeElement.addEventListener('change', function() {
            reservationTimeElement.textContent = selectReservationTimeElement.value;
        });

        var selectNumberOfGuestsElement = document.querySelector('select[name="number_of_guests"]');
        var guestCountElement = document.getElementById('guest_count');
        selectNumberOfGuestsElement.addEventListener('change', function() {
            var guestCount = selectNumberOfGuestsElement.value;
            guestCountElement.textContent = guestCount + "人";
        });

        var selectCourseElement = document.querySelector('select[name="course_id"]');
        var totalPriceElement = document.getElementById('total_price');

        function updateTotalPrice() {
            var selectedCourseId = selectCourseElement.value;
            var selectedGuestCount = parseInt(selectNumberOfGuestsElement.value);

            // バリデーション: コースIDと人数が選択されているか確認
            if (!selectedCourseId || isNaN(selectedGuestCount)) {
                totalPriceElement.textContent = '0円';
                return;
            }

            // 合計金額を計算（ここでは仮にコースの価格を人数で乗算する例を示します）
            var coursePrice = parseFloat(selectCourseElement.options[selectCourseElement.selectedIndex].getAttribute('data-price'));
            var totalPrice = coursePrice * selectedGuestCount;

            // 合計金額をテーブルに反映
            totalPriceElement.textContent = totalPrice.toLocaleString() + '円';
        }

        // 人数またはコースが変更されたら合計金額を更新
        selectCourseElement.addEventListener('change', updateTotalPrice);
        selectNumberOfGuestsElement.addEventListener('change', updateTotalPrice);

        // 初期状態で合計金額を更新（人数が選択されるのを待つ）
        updateTotalPrice();
    });
</script>
@endsection