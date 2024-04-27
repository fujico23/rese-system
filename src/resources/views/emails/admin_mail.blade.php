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
        <p>普段からご利用いただいているご常連様に心からの感謝を込めて</p>
        <p>次回【Rese】からご予約をされた場合<span>「10%OFF」</span>とさせていただきます。</p>
        <p>この機会にぜひ、お気に入りのお店でお得にお楽しみください。</p>
        <div class="content__list">
            <h3 class="content__list__header">▼クーポン利用条件</h3>
            <ul>
                <li>有効期限:発行日より1ヶ月間</li>
                <li>利用可能時間:ランチタイムを除く17:00～21:00</li>
                <li>事前予約時にクーポン利用の旨をお伝えください</li>
            </ul>
        </div>
        <p>是非ご活用下さい。</p>
    </div>
    <div class="footer">
        <p>お問い合わせは<a href="mailto:support@example.com">support@example.com</a>まで</p>
    </div>
</body>

</html>