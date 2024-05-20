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
        <p class="reminder__container-message">この度は<span>Rese</span>をご利用いただき、誠にありがとうございます。</p>
        <p class="reminder__container-message">本日のご予約情報をお送りさせていただきました。</p>
        <p class="reminder__container-message">以下の内容をご確認ください。</p>
        <div class="reminder__container-confirm">
            <p class="reminder__container-confirm-description" id="shop_name">店舗名：{{ $shopName }}</p>
            <p class="reminder__container-confirm-description" id="reservation_date">予約日：{{ $reservationDate }}</p>
            <p class="reminder__container-confirm-description" id="reservation_time">予約時間：{{ $reservationTime }}</p>
            <p class="reminder__container-confirm-description" id="number_of_guests">予約人数：{{ $numberOfGuests }}人</p>
        </div>

        <p class="reminder__container-message">なお、ご予約を変更される場合は、お手数ですが{{ $shopName }}様に直接ご連絡をお願いいたします。</p>
        <p class="reminder__container-message">無断でのキャンセルの場合、次回のご予約に影響が出る可能性がございますので、</p>
        <p class="reminder__container-message">何卒ご了承いただきますようお願い申し上げます。</p>
        <p class="reminder__container-message">またのご利用を心よりおまちしております。</p>
        <p>予約のご確認はマイページからお願い致します</p>
        <img src="{{ $filePath }}" alt="QR Code1">
    </div>
</body>

</html>