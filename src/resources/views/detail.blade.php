@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
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
        <img src="{{ Storage::url($shop->images->first()->image_url) }}" alt="{{ $shop->shop_name }}"> -->
        @endif
        @else
        <p>準備中です</p>
        @endif
    </div>

    <div class="shop-details">
        <div class="hashtag">
            <span class="shop-area">#{{ $shop->area->area_name }}</span>
            <span class="shop-genre">#{{ $shop->genre->genre_name }}</span>
            <!-- 予約情報がある場合にレビューを記述するリンクを表示 -->
            <span class="reviews-index">
                <a href="{{ route('shop.review.index', $shop) }}" class="review-link">レビュー一覧</a>
            </span>
            @if(!empty($reservations))
            <span class="reviews-create">
                <a href="{{ route('shop.review.create', $shop) }}" class="review-link">レビューを書く</a>
            </span>
            @endif
        </div>
        <div class="shop-description">
            <p>{{ $shop->description }}</p>
        </div>
    </div>
</div>

<!-- 予約情報-->
<div class="reservation-container">
    <div class="reservation-container__inner">
        <form action="/detail/{shop}/reservation" method="post">
            @csrf
            <div class="reservation-form__inner">
                <h2 class="reservation__heading">予約</h2>
                <div class="reservation-date form__tag">
                    <input type="date" name="reservation_date" id="reservation_date" value="予約日を選択してください">
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
                        <option value="{{ $time }}">{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
                <p class="reservation-error">
                    @error('reservation_time')
                    {{ $message }}
                    @enderror
                </p>
                <div class="numer-of-guests form__tag">
                    <select name="number_of_guests">
                        <option value="">予約人数を選択してください</option>
                        @for ($count = 1; $count <= 20; $count++) <option value="{{ $count }}">{{ $count }}人</option>
                            @endfor
                    </select>
                </div>
                <p class="reservation-error">
                    @error('number_of_guests')
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
                </table>
            </div>
            <div class="reservation__btn">
                <button class="btn" type="submit" name="reservation_button">予約する</button>
            </div>
        </form>
    </div>
</div>


<script>
    // 今日の日付を取得
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;

    // 2ヶ月後の日付を計算
    var maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 2);
    var max_dd = String(maxDate.getDate()).padStart(2, '0');
    var max_mm = String(maxDate.getMonth() + 1).padStart(2, '0');
    var max_yyyy = maxDate.getFullYear();
    var twoMonthsLater = max_yyyy + '-' + max_mm + '-' + max_dd;

    // 最小の日付を設定
    document.getElementById("reservation_date").setAttribute("min", today);

    // 最大の日付を設定
    document.getElementById("reservation_date").setAttribute("max", twoMonthsLater);
    document.addEventListener('DOMContentLoaded', function() {
        var selectReservationTimeElement = document.querySelector('select[name="reservation_time"]');
        var reservationTimeElement = document.getElementById('reservation_time');

        var selectNumberOfGuestsElement = document.querySelector('select[name="number_of_guests"]');
        var guestCountElement = document.getElementById('guest_count');

        var reservationDateElement = document.getElementById('reservation_date');
        var reservationDateDisplayElement = document.getElementById('reservation_date_display');

        selectReservationTimeElement.addEventListener('change', function() {
            reservationTimeElement.textContent = selectReservationTimeElement.value;
        });

        selectNumberOfGuestsElement.addEventListener('change', function() {
            var guestCount = selectNumberOfGuestsElement.value;
            guestCountElement.textContent = guestCount + "人";
        });

        reservationDateElement.addEventListener('change', function() {
            reservationDateDisplayElement.textContent = reservationDateElement.value;
        });
    });
</script>
@endsection