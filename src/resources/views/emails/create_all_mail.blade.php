<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/mail/create_all_mail.css') }}">

</head>

<body>
    <h2 class="all-mail__heading">一斉メール送信 Form</h2>
    <form class="all-mail__form" method="POST" action="{{ route('admin.all.users.mail') }}">
        @csrf
        <div class="all-mail__form__group--title">
            <label for="title">メール件名</label>
            <div class="all-mail__form__group--title__item">
                <span class="all-mail__form__group--title--app">【Rese】</span>
                <span class="all-mail__form__group--title--fixed fixed">※固定</span>
            </div>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="all-mail__form__group--body">
            <label for="body">メール本文</label>
            <div class="all-mail__form__group--body__item">
                <span class="all-mail__form__group--body--beginning">いつも飲食店予約アプリケーション『Rese』をご利用いただき、誠にありがとうございます。</span>
                <span class="all-mail__form__group--body--fixed fixed">※固定</span>
            </div>
            <textarea id="body" name="body" required></textarea>
            <div class="all-mail__form__group--body__item">
                <span class="all-mail__form__group--body--closing-words">今後もより良いサービスを提供出来るよう尽力させていただきますので『Rese』のご利用を何卒宜しくお願い申し上げます。</span>
                <span class="all-mail__form__group--body--fixed fixed">※固定</span>
            </div>
            <p class="all-mail__form__group--body--app">Rese</p>
        </div>
        <button type="submit">全ユーザーに送信</button>
    </form>

</body>

</html>