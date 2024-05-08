@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user_details.css')}}">
@endsection

@section('content')

<h2 class="user-detail__header">ユーザー詳細</h2>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@elseif (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<div class="user-detail__container">
    <table class="user-detail__container__table">
        <div class="user-detail__container__table__inner">
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">メール</th>
                <td class="user-detail__container__table-row__detail">
                    <a class="user-detail__container__table-row__detail__mail-form" href="{{ route('create.user.mail', $user) }} ">個人メールフォーム</a>
                </td>
            </tr>
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">ID</th>
                <td class="user-detail__container__table-row__detail">{{ $user->id }}</td>
            </tr>
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">名前</th>
                <td class="user-detail__container__table-row__detail">{{ $user->name }}</td>
            </tr>
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">Email</th>
                <td class="user-detail__container__table-row__detail">{{ $user->email }}</td>
            </tr>
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">会員登録日</th>
                <td class="user-detail__container__table-row__detail">{{ $user->created_at }}</td>
            </tr>
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">役割</th>
                <td class="user-detail__container__table-row__detail">
                    <form class="user-detail__container__table-row__form-patch" action="{{ route('role.update', $user) }}" method="POST" onsubmit="return confirm('本当に変更しますか？');">
                        @csrf
                        @method('patch')
                        <select class="user-detail__container__table-role-select" name="role_id" id="role_id">
                            @foreach ($roles as $role)
                            <option class="user-detail__container__table-role-option role-{{ $role->id }}" value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>
                                @if($role->id == 1)
                                管理者
                                @elseif($role->id == 2)
                                店舗代表者
                                @elseif($role->id == 3)
                                利用者
                                @else
                                その他
                                @endif
                            </option>
                            @endforeach
                        </select>
                        <button class="user-detail__container__table__btn-patch" type="submit">変更</button>
                    </form>
                </td>
            </tr>
            @if($userRoleId == 2)
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">代表店選択</th>
                <td class="user-detail__container__table-row__detail">
                    <form class="user-detail__container__table-row__form-post" action="{{ route('admin.users.assign', $user) }}" method="POST" onsubmit="return confirm('店舗代表の店舗を追加しますか？');">
                        @csrf
                        <select class="user-detail__container__table-shop-select" name="shop_id" id="shop_id">
                            @foreach ($shops as $shop)
                            <option class="user-detail__container__table-shop-option" value="{{ $shop->id }}">
                                {{ $shop->shop_name }}
                            </option>
                            @endforeach
                        </select>
                        <button class="user-detail__container__table__btn-post" type="submit">追加</button>
                    </form>
                </td>
            </tr>

            @if($shopUsers->count() > 0)
            @foreach($shopUsers as $shopUser)
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">代表店舗</th>
                <td class="user-detail__container__table-row__detail">
                    <form class="user-detail__container__table-row__form-delete" action="{{ route('admin.users.remove', ['user' => $user, 'shop' => $shopUser->shop_id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <div class="user-detail__container__table-shop-input">
                            <input type="hidden" name="shop_id" value="{{ $shopUser->shop_id }}">
                            <input type="" name="" value="{{ App\Models\Shop::find($shopUser->shop_id)->shop_name }}" readonly>
                        </div>
                        <button class="user-detail__container__table__btn-delete" type="submit">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endif

            @endif
            <tr class="user-detail__container__table-row">
                <th class="user-detail__container__table-row__header">クーポン</th>
                <td class="user-detail__container__table-row__detail">
                    <form class="user-detail__container__table-row__form-submit" action="{{ route('admin.users.mail', $user)  }}" method="post">
                        @csrf
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="text" readonly value="期限:2024/12/31末">
                        <button class="user-detail__container__table__btn-submit" type="submit">送信</button>
                    </form>
                </td>
            </tr>
        </div>
    </table>
</div>
@endsection