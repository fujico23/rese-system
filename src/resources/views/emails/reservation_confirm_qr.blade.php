<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
    <link rel="stylesheet" href="{{ asset('css/reservation_confirm_qr.css')}}">
</head>

<body>
    <h1 class="reservation__header">Reservation Confirm</h1>
    <div class="reservation__container">
        <p class="reservation__container__item">Shop Name: {{ $shopName }}</p>
        <p class="reservation__container__item">Reservation Date: {{ $reservationDate }}</p>
        <p class="reservation__container__item">Reservation Time: {{ $reservationTime }}</p>
        <p class="reservation__container__item">Name: {{ $userName }}</p>
        <p class="reservation__container__item">Number of Guests: {{ $numberOfGuests }}</p>
        <p class="reservation__container__item">Course：{{ $courseName }}</p>
        <p class="reservation__container__item">Amount per person：¥{{ $coursePrice }}</p>
        <p class="reservation__container__item">Total Amount: ¥{{ $totalAmount }}</p>
        <p class="reservation__container__item">Payment_status：{{ $paymentStatus }}</p>
        <p class="reservation__container__item">Status：{{ $status }}</p>
    </div>
</body>

</html>