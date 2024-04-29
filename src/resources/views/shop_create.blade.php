@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_create.css')}}">
@endsection

@section('content')
<div class="shop-create">
    <h2 class="shop-create__header">店舗作成画面</h2>
    <div class="shop-create__container">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <table class="shop-create__container__table">
            <form class="shop-create__container__table-row__form-post" action="{{ route('shops.store') }}" method="POST">
                @csrf
                <div class="shop-create__container__table__inner">
                    <tr class="shop-create__container__table-row">
                        <th class="shop-create__container__table-row__header">店舗名<span>必須</span></th>
                        <td class="shop-create__container__table-row__detail">
                            <input class="shop-create__container__table-row__detail-input" type="text" name="shop_name">
                        </td>
                        @error('shop_name')
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    </tr>
                    <tr class="shop-create__container__table-row">
                        <th class="shop-create__container__table-row__header">エリア<span>必須</span></th>
                        <td class="shop-create__container__table-row__detail">
                            <select class="shop-create__container__table-area-select" name="area_id">
                                <option value="">選択してください</option>
                                @foreach($areas as $area)
                                <option class="shop-create__container__table-shop-option" value="{{ $area->id }}">{{ $area->area_name }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        @error('area_id')
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    </tr>
                    <tr class="shop-create__container__table-row">
                        <th class="shop-create__container__table-row__header">ジャンル<span class="required">必須</span></th>
                        <td class="shop-create__container__table-row__detail">
                            <select class="shop-create__container__table-genre-select" name="genre_id">
                                <option value="">選択してください</option>
                                @foreach($genres as $genre)
                                <option class="shop-create__container__table-shop-option" value="{{ $genre->id }}">{{ $genre->genre_name }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="shop-create__container__table-row">
                        <th class="shop-create__container__table-row__header">店舗説明</th>
                        <td class="shop-create__container__table-row__detail">
                            <input class="shop-create__container__table-row__detail-input" type="textarea" name="description">
                        </td>
                        @error('genre_id')
                        <p class="alert alert-danger">{{ $message }}</p>
                        @enderror
                    </tr>
                </div>
                <button class="shop-create__container__table__btn-post" type="submit">作成</button>
            </form>
        </table>
    </div>
</div>
@endsection