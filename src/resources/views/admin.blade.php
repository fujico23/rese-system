@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css')}}">
@endsection

@section('content')
<div class="admin">
    <h2 class="admin__header">管理者画面</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="admin__container">
        <form method="POST" action="{{ route('admin.all.users.mail') }}">
            @csrf <!-- CSRF トークン -->
            <button type="submit" class="admin__container__send-mail-button">一斉メール送信</button>
        </form>
        <table class="admin__container__table">
            <div class="admin__table__inner">
                <tr class="admin__container__table-row">
                    <th class="admin__container__table-row__header">ID</th>
                    <th class="admin__container__table-row__header">名前</th>
                    <th class="admin__container__table-row__header">役割</th>
                    <th class="admin__container__table-row__header"></th>
                </tr>
                @foreach ($users as $user)
                <tr class="admin__container__table-row">
                    <td class="admin__container__table-row__detail">{{ $user->id }}</td>
                    <td class="admin__container__table-row__detail">{{ $user->name }}</td>
                    <td class="admin__container__table-row__detail">
                        @if($user->role->id == 1)
                        管理人
                        @elseif($user->role->id == 2)
                        店舗代表
                        @elseif($user->role->id == 3)
                        利用者
                        @endif</td>
                    <td class="admin__container__table-row__detail"><a href="{{ route('users.show', $user) }}">詳細</a></td>
                </tr>
                @endforeach
            </div>
        </table>
    </div>
</div>
@endsection