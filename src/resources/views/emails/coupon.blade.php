<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/coupon.css') }}">

</head>

<body>
<div class="coupon">
    <h1 class="coupon__logo">Rese</h1>
    <h2 class="coupon__header">特別クーポン</h2>
    <p>このクーポンをご提示で<strong class="discount">10% OFF</strong></p>
    <p class="expiration">有効期限:{{ $twoMonthsLater->format('Y-m-d') }}</p>
</div>
</body>

</html>