@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_management.css')}}">
@endsection

@section('content')
<div class="shop-management">
  <h2 class="shop-management__header">店舗編集画面</h2>
  @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif
  @foreach ($shops as $shop)
  <div class="shop-management__container">
    <table class="shop-management__container__table">
      <form class="shop-management__container__table-row__form-post" action="{{ route('management.edit', $shop) }}" method="POST" enctype="multipart/form-data">
        @method('patch')
        @csrf
        <div class="shop-management__container__table__inner">
          <tr class="shop-management__container__table-row">
            <th class="shop-management__container__table-row__header">店舗名</th>
            <td class="shop-management__container__table-row__detail">
              <input type="text" name="shop_name" value="{{ old('shop_name', $shop->shop_name) }}">
            </td>
          </tr>
          <tr class="shop-management__container__table-row">
            <th class="shop-management__container__table-row__header">店舗画像</th>
            <td class="shop-management__container__table-row__detail">
              <input class="shop-management__container__table-row__detail-input" type="file" name="image_url">
            </td>
            @error('image_url')
            <p class="alert alert-danger">{{ $message }}</p>
            @enderror
          </tr>
          <tr class="shop-management__container__table-row">
            <th class="shop-management__container__table-row__header">エリア</th>
            <td class="shop-management__container__table-row__detail">
              <select class="shop-management__container__table-row__detail-select" name="area_id">
                <option value="">{{ $shop->area->area_name }}</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}">
                  {{ $area->area_name }}
                </option>
                @endforeach
              </select>
            </td>
          </tr>
          <tr class="shop-management__container__table-row">
            <th class="shop-management__container__table-row__header">ジャンル</th>
            <td class="shop-management__container__table-row__detail">
              <select class="shop-management__container__table-row__detail-select" name="genre_id">
                <option value="">{{ $shop->genre->genre_name }}</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}">
                  {{ $genre->genre_name }}
                </option>
                @endforeach
              </select>
            </td>
          </tr>
          <tr class="shop-management__container__table-row">
            <th class="shop-management__container__table-row__header">店舗説明</th>
            <td class="shop-management__container__table-row__detail">
              <textarea class="shop-management__container__table-row__detail-textarea" name="description">{{ old('description', $shop->description) }}</textarea>
            </td>
            @error('description')
            <p class="alert alert-danger">{{ $message }}</p>
            @enderror
          </tr>
          <tr class="shop-management__container__table-row">
            <th class="shop-management__container__table-row__header">公開</th>
            <td class="shop-management__container__table-row__detail">
              <input type="checkbox" name="is_active" value="1" {{ $shop->is_active ? 'checked' : '' }}>
            </td>
          </tr>
        </div>
        <button class="shop-management__container__table__btn-post" type="submit">編集</button>
      </form>
    </table>

  </div>
  @endforeach
</div>
@endsection