@extends('layouts/app')

@section('css')
<script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
<form id="productForm">
    @foreach ($menus as $menu)
    <div class="product">
        <img src="" alt="">
        <h2>{{ $menu->menu_name }}</h2>
        <p>{{ $menu->menu_description }}</p>
        <p>{{ number_format($menu->price) }}円</p>
        <input type="checkbox" name="products" value="{{ $menu->price }}" data-name="{{ $menu->menu_name }}" data-id="{{ $menu->id }}">この商品を選択<br>
    </div>
    @endforeach
    <button type="button" id="customButton">会計</button>
</form>

<script type="text/javascript">
    var stripeKey = "{{ env('STRIPE_KEY') }}";
</script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    var handler = StripeCheckout.configure({
        key: stripeKey,
        locale: 'auto',
        token: function(token) {
            // トークン生成後の処理。例えば、サーバーへトークンを送信。
        }
    });

    document.getElementById('customButton').addEventListener('click', function(e) {
        var totalAmount = 0;
        var productDescriptions = [];
        var selectedProducts = [];

        document.querySelectorAll('#productForm input[name="products"]:checked').forEach(function(item) {
            totalAmount += parseInt(item.value, 10); // 合計金額を計算
            productDescriptions.push(item.dataset.name); // 商品名を集める
            selectedProducts.push({
                id: item.dataset.id,
                name: item.dataset.name
            }); // 選択された商品の詳細情報を集める
        });

        if (totalAmount === 0) {
            alert("商品を選択してください。");
            return;
        }

        handler.open({
            name: 'お支払い画面',
            description: productDescriptions.join(", "),
            amount: totalAmount,
            currency: 'JPY'
        });
        e.preventDefault();
    });

    window.addEventListener('popstate', function() {
        handler.close();
    });
</script>
@endsection