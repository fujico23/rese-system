@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_reservation_confirm.css')}}">
@endsection

@section('content')
<div class="shop-reservation-confirm">
  <h2 class="shop-reservation-confirm__header">店舗予約確認画面</h2>
  @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif
  <div class="shop-reservation-confirm__container">
    @foreach ($shops as $shop)
    <h3 class="shop-reservation-confirm__container-shop-name">{{ $shop->shop_name }}</h3>
    <table class="shop-reservation-confirm__container__table">
      <thead>
        <tr class="shop-reservation-confirm__container__table-row">
          <th class="shop-reservation-confirm__container__table-row__header">予約名</th>
          <th class="shop-reservation-confirm__container__table-row__header">日程</th>
          <th class="shop-reservation-confirm__container__table-row__header">時間</th>
          <th class="shop-reservation-confirm__container__table-row__header">人数</th>
          <th class="shop-reservation-confirm__container__table-row__header">ステータス</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($shop->reservations as $reservation)
        <tr class="shop-reservation-confirm__container__table-row">
          <td class="shop-reservation-confirm__container__table-row__detail">{{ $reservation->user->name }}</td>
          <td class="shop-reservation-confirm__container__table-row__detail">{{ $reservation->reservation_date }}</td>
          <td class="shop-reservation-confirm__container__table-row__detail">
            @php
            $reservationTime = \Carbon\Carbon::parse($reservation->reservation_time);
            @endphp
            {{ $reservationTime->format('H:i') }}
          </td>
          <td class="shop-reservation-confirm__container__table-row__detail">{{ $reservation->number_of_guests }}</td>
          <td class="shop-reservation-confirm__container__table-row__detail">
            <form action="{{ route('status.update', ['reservation' => $reservation->id]) }}" method="POST">
              @csrf
              <select class="shop-reservation-confirm__container__table-row__detail__select" name="status" id="reservation_status_{{ $reservation->id }}">
                <option value="{{ $reservation->status }}">{{ $reservation->status }}</option>
                <option value="予約済み">予約済み</option>
                <option value="キャンセル">キャンセル</option>
                <option value="口コミ済み">口コミ済み</option>
                <option value="口コミ無し">口コミ無し</option>
              </select>
              <button class="shop-reservation-confirm__container__table-row__detail__submit" type="submit">更新</button>
            </form>
          </td>
        </tr>
        @endforeach
        @if ($shop->reservations->isEmpty())
        <tr class="shop-reservation-confirm__container__table-row">
          <td class="shop-reservation-confirm__container__table-row__detail" colspan="5">No reservations found.</td>
        </tr>
        @endif
      </tbody>
    </table>
    @endforeach
  </div>
</div>
@endsection