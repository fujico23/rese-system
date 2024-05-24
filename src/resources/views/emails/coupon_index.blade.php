<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
</head>

<body>
    <h1>{{ $user->name }}様</h1>
    <p>いつも飲食店予約アプリケーション『Rese』をご利用いただき、誠にありがとうございます。</p>
    <p>さてこの度、Reseをご利用いただいているお客様に感謝の気持ちを込めましてお得なクーポンを贈らせていただきます</p>
    <p>ご来店の際に店員にQRコードを表示して下さい</p>
    <img src="{{ $fileUrl }}" alt="QR Code1">
    <h2>クーポンの条件</h2>
    <ul>
        <li>・ランチタイムを除く</li>
        <li>・お一人様2,500円以上ご利用の時</li>
        <li>・期間中は何度でもご利用可能</li>
    </ul>
    <p>今後もより良いサービスを提供出来るよう尽力させていただきますので『Rese』のご利用を何卒宜しくお願い申し上げます。</p>
    <p>Rese</p>
</body>

</html>