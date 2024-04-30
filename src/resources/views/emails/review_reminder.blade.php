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
        弊社情報はこちら↓<br>
        {!! QrCode::generate(url('https://coachtech.site/?gad_source=1&utm_campaign=coachtech&utm_medium=ppc&hsa_mt=e&hsa_cam=11225035106&hsa_kw=coachtech&hsa_acc=5245015319&utm_term=coachtech&gclid=CjwKCAjwuJ2xBhA3EiwAMVjkVFxZjAvZqx_2ZWpIQszH5AwEMYMKjUQkNu-DE80W1flteiCoc0bDERoCkZYQAvD_BwE&hsa_tgt=aud-1435517835582:kwd-768959043722&hsa_src=g&hsa_ver=3&hsa_grp=107325014382&hsa_ad=533125441083&hsa_net=adwords&utm_source=adwords/')) !!}<br>
    </div>
</body>

</html>