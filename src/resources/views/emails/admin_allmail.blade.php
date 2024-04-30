<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/mail/admin_allmail.css') }}">

</head>

<body>
    <h1 class="heading">{{ $details['title'] }}</h1>
    <p class="body">{{ $details['body'] }}</p>
    <div class="container">
        <p>いつもReseをご活用ありがとうございます。</p>
        <p>この度、夜間のアプリケーションシステムについて、定期的なメンテナンスおよびアップグレード作業を行うため、一時的にシステムを休止させていただくことになりました。</p>
        <p class="date">休止予定日時:<strong>2024/5/25 23:00</strong> から <strong>2024/5/26 2:00</strong></p>
        <p>この休止期間中は、アプリケーションの利用が制限されますので、予めご了承ください。ご不便をおかけいたしますことをお詫び申し上げます。</p>
        <p>休止終了後には、より安定したシステム環境を提供できるよう努めてまいります。</p>
        <p>ご理解とご協力を賜りますよう、よろしくお願い申し上げます。</p>
    </div>
</body>

</html>