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
        <form class="reservation-form" action="/detail/{shop}/reservation" method="post">
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
                    <select name="number_of_guests" id="number_of_guests"><!-- idを追加 -->
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
                <div class="course form__tag">
                    <select name="course_id">
                        <option value="">予約コースを選択してください</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-price="{{ $course->price }}">{{ $course->course_name }} - ¥{{ number_format($course->price) }}</option>
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
                    <tr class="table__row">
                        <td>事前決済</td>
                        <td id="total_price">
                            <form id="productForm">
                                <button class="stripe__btn" type="submit" id="customButton">注文</button>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="reservation__btn">
                <button class="btn" type="submit" id="submit-button" name="reservation_button">予約する</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var stripeKey = "{{ env('STRIPE_KEY') }}";
</script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    var handler = StripeCheckout.configure({
        key: stripeKey,
        locale: 'auto',
        token: function(token) {
            // トークン生成後の処理。例えば、サーバーへトークンを送信。
        }
    });
    document.getElementById('customButton').addEventListener('click', function(e) {
        var totalAmount = 0;
        var productDescriptions = [];
        var selectedProducts = [];

        document.querySelectorAll('#productForm input[name="products"]:checked').forEach(function(item) {
            totalAmount += parseInt(item.value, 10); // 合計金額を計算
            productDescriptions.push(item.dataset.name); // 商品名を集める
            selectedProducts.push({
                id: item.dataset.id,
                name: item.dataset.name
            }); // 選択された商品の詳細情報を集める
        });

        handler.open({
            name: 'お支払い画面',
            description: productDescriptions.join(", "),
            amount: totalAmount,
            currency: 'JPY'
        });
        e.preventDefault();
    });

    window.addEventListener('popstate', function() {
        handler.close();
    });
</script>
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