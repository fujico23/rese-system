<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/mail/admin_mail.css') }}">

</head>

<body>
    <h2 class="heading">{{ $details['title'] }}</h2>
    <p class="body">{{ $details['body'] }}</p>
    <div class="content">
        <p>いつも【Rese】をご利用いただき、誠にありがとうございます。</p>
        <p>普段からご利用いただいている皆様に心からの感謝を込めて</p>
        <p>次回【Rese】からご予約をされた場合<span>「10%OFF」</span>とさせていただきます。</p>
        <p>この機会にぜひ、お気に入りのお店でお得にお楽しみください。</p>

        <img src="{{ asset('images/coupon_qr_local.png') }}" alt="coupon" style="width: 30%; height: auto;">
        <div class="content__list">
            <h3 class="content__list__header">▼クーポン利用条件</h3>
            <ul>
                <li>ご来店時にクーポン表示の準備をお願いいたします</li>
                <li>お一人様お会計が2,500円以上の場合に限ります</li>
                <li>期間内は何度でもご利用いただきます</li>
            </ul>
        </div>
        <p>是非ご活用下さい。</p>
    </div>
</body>

</html>