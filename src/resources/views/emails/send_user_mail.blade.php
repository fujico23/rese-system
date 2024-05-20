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
    <p>{{ $user->name }}様</p>
    <p>いつも飲食店予約アプリケーション『Rese』をご利用いただき、誠にありがとうございます。</p>
    <p>{{ $body }}</p>
    <p>今後もより良いサービスを提供出来るよう尽力させていただきますので『Rese』のご利用を何卒宜しくお願い申し上げます。</p>
    <p>Rese</p>
    <p>Reseの一覧ページ表示はこちらから</p>
    <img src="{{ $filePath }}" alt="QR Code1">
</body>

</html>