@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css')}}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<h2 class="mypage__name">{{Auth::user()->name }} さん</h2>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@elseif (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="alert-danger__list">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="mypage__container">
    <div class="reservation">
        <h3 class="reservation__confirm">予約状況</h3>
        @if(count($userReservations) > 0)
        @foreach($userReservations as $key => $reservation)
        <div class="reservation__container">
            <div class="reservation__container__heading">
                <div class="reservation__container__heading__clock-img">
                    <i class="fa-regular fa-clock fa-xl" style="color: #f5f7fa;"></i>
                </div>
                <h4 class="reservation__container__heading__number">予約{{ $key + 1 }}</h4>
                <form class="reservation__container__heading__form-delete" action="{{ route('mypage.reservation.delete', ['id' => $reservation->id]) }}" method="post">
                    @csrf
                    @method('delete')
                    <div class="reservation__container__heading__form__inner">
                        <button class="fa-regular fa-circle-xmark fa-2xl" style="color: #f2f4f8;" type="submit" value=""></button>
                    </div>
                </form>
            </div>

            <form class="reservation__container__table__form-edit" action="{{ route('mypage.reservation.update', ['id' => $reservation->id]) }}" method="post">
                @method('patch')
                @csrf
                <table class="reservation__container__table">
                    <tr class="reservation__container__table__row">
                        <th class="reservation__container__table__row__header">Shop</th>
                        <td class="reservation__container__table__row__description">{{ $reservation->shop->shop_name }}</td>
                    </tr>
                    <tr class="reservation__container__table__row">
                        <th class="reservation__container__table__row__header">Date</th>
                        <td class="reservation__container__table__row__description">
                            <input class="input-field" id="editableInput{{ $reservation->id }}" disabled type="date" name="reservation_date" value="{{ $reservation->reservation_date }}">
                        </td>
                    </tr>
                    <tr class="reservation__container__table__row">
                        <th class="reservation__container__table__row__header">Time</th>
                        <td class="reservation__container__table__row__description">
                            <select disabled id="mySelect{{ $reservation->id }}" name="reservation_time" class="editable select-field">
                                <option value="{{ $reservation->reservation_time }}">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</option>
                                @foreach($reservationTimes as $time)
                                <option value="{{ $time }}">{{ $time }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="reservation__container__table__row">
                        <th class="reservation__container__table__row__header">Number</th>
                        <td class="reservation__container__table__row__description">
                            <select disabled id="mySelectNumber{{ $reservation->id }}" name="number_of_guests" class="editable select-field">
                                <option value="{{ $reservation->number_of_guests }}">{{ $reservation->number_of_guests }}人</option>
                                @for ($count = 1; $count <= 20; $count++) <option value="{{ $count }}">{{ $count }}人</option>
                                    @endfor
                            </select>
                        </td>
                    </tr>
                    <tr class="reservation__container__table__row">
                        <th class="reservation__container__table__row__header">payment</th>
                        <td class="reservation__container__table__row__description">{{ $reservation->payment_status }}</td>
                    </tr>
                </table>
                <div class="reservation__container__bottom">
                    <button id="enableEdit{{ $reservation->id }}" class="reservation__container__bottom-edit__btn btn" type="button" onclick="enableEditAndEnableSubmit('{{ $reservation->id }}')">編集</button>
                    <button id="enableSubmit{{ $reservation->id }}" class="reservation__container__bottom-submit__btn btn" type="submit" disabled>確定</button>
                </div>
                <input type="hidden" name="course_id" value="{{ $reservation->course_id }}">
            </form>
        </div>
        @endforeach
        @else
        <p>予約がありません</p>
        @endif
    </div>

    <div class="favorite-shop">
        <h3 class="favorite-shop__heading">お気に入り店舗</h3>
        <div class="card-container">
            @foreach($favorites as $favorite)
            <div class="card">
                <div class="shop__img">
                    @if (Str::startsWith($favorite->shop->images->first()->image_url, 'http')) <!-- S3のURLかどうかを確認 -->
                    <img src="{{ $favorite->shop->images->first()->image_url }}" alt="{{ $favorite->shop->shop_name }}">
                    @else
                    <img src="{{ asset('storage/' . $favorite->shop->images->first()->image_url) }}" alt="{{ $favorite->shop->shop_name }}">
                    <!-- 本番環境の場合
                    <img src="{{ Storage::url($favorite->shop->images->first()->image_url) }}" alt="{{ $favorite->shop->shop_name }}"> -->
                    @endif

                </div>
                <div class="shop__details">
                    <div>
                        <h4 class="shop-name">{{ $favorite->shop->shop_name }}</h4>
                        <span class="shop-area hashtag">#{{ $favorite->shop->area->area_name }}</span>
                        <span class="shop-genre hashtag">#{{ $favorite->shop->genre->genre_name }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/detail/{{ $favorite->shop->id }}" class="card-footer-btn btn">詳しく見る</a>
                    <form action="{{ route('mypage.favorite.delete') }}" method="POST">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="favorite_id" value="{{ $favorite->id }}">
                        <button class="heart heart-grey" type="submit">
                            <i class="fa-solid fa-heart" style="color: #fc030f;"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>




<script>
    // 予約削除の確認ダイアログ
    document.querySelectorAll('.reservation__container__heading__form-delete').forEach(function(form) {
        form.onsubmit = function(event) {

            const isConfirmed = confirm('本当に予約を削除しますか？');

            if (!isConfirmed) {
                event.preventDefault();
            }
        };
    });
    // 予約変更の確認ダイアログ
    document.querySelectorAll('.reservation__container__table__form-edit').forEach(form => {
        form.onsubmit = function(event) {
            const isConfirmed = confirm('本当に予約を変更しますか？');
            if (!isConfirmed) {
                event.preventDefault();
            }
        };
    });

    // ボタンをクリックしたらSelectタブとInputを有効にする
    function enableEditAndEnableSubmit(reservationId) {
        var editableElements = document.querySelectorAll("#editableInput" + reservationId + ", #mySelect" + reservationId + ", #mySelectNumber" + reservationId);
        for (var i = 0; i < editableElements.length; i++) {
            editableElements[i].removeAttribute("disabled");
            editableElements[i].style.backgroundColor = "#fff";
            editableElements[i].style.color = "black";
        }

        document.getElementById("enableEdit" + reservationId).disabled = true;
        document.getElementById("enableSubmit" + reservationId).disabled = false;
    }
</script>

@endsection