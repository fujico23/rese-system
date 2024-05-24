<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/mail/email.css')}}">
</head>

<body>
    <h1 class="user-name">{{ $userName }} 様</h1>
    <div class="reminder__container">
        <p class="reminder__container-message">昨日は<span>Rese</span>のご利用と</p>
        <p class="reminder__container-message"><span>{{ $shopName }}</span>へのご来店、誠にありがとうございました。</p>
        <p class="reminder__container-message">お食事やサービスはいかがでしたでしょうか？</p>
        <p class="reminder__container-message">貴重なご意見をいただきたく{{ $userName}}様にレビューの投稿をお願いいたします。</p>
        <p class="reminder__container-message">ご意見を参考にさせていただき</p>
        <p class="reminder__container-message">サービスの向上に努めてまいります。</p>
        <p class="reminder__container-message">今後もReseのご利用を心よりおまちしております。</p>
    </div>
</body>

</html>