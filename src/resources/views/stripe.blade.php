@extends('layouts/app')

@section('css')
<script src="https://js.stripe.com/v3/"></script>
<link rel="stylesheet" href="{{ asset('css/stripe.css')}}">
@endsection

@section('content')
<h2 class="stripe__header">TAKE OUT MENU</h2>
<form id="productForm">
    <ol class="stripe__ol">
        @foreach ($menus as $menu)
        <li class="stripe__ol-list">
            <div class="stripe__ol-list__product">
                <div class="stripe__ol-list__product-top">
                    <div class="stripe__ol-list__product-top__menu">
                        <input type="checkbox" name="products" value="{{ $menu->price }}" data-name="{{ $menu->menu_name }}" data-id="{{ $menu->id }}"><br>
                        <h3 class="stipe__ol-list__product__top__menu--name">{{ $menu->menu_name }}</h3>
                    </div>
                    <p class="stripe__ol-list__product__top--price">{{ number_format(round($menu->price)) }}円</p>
                </div>
                <p class="stipe__ol-list__product__menu_description">{{ $menu->menu_description }}</p>
            </div>
        </li>
        @endforeach
    </ol>
    <button class="stripe__btn" type="button" id="customButton">注文</button>
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