<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('css')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header__heading">
                <div class="header__heading__left">
                    <div class="hamburger-menu" onclick="toggleMenu()">
                        <img src="{{ asset('images/logo.png') }}" alt="logo" width="32" height="32">
                    </div>
                    @if (Auth::check())
                    @if($role_id == 3)
                    @include('menu.menu3')
                    @elseif($role_id == 2)
                    @include('menu.menu2')
                    @elseif($role_id == 1)
                    @include('menu.menu1')
                    @endif
                    @else
                    @include('menu.menu4')
                    @endif
                    <h1 class="header__heading__left-logo2"><a href="{{ route('index') }}">Rese</a></h1>

                </div>
                <div class="header__heading__right">
                    @if(Route::is('index'))
                    @if (Auth::check())
                    <p class="header__heading__right-user-name">{{ Auth::user()->name }} さん</p>
                    @else
                    <p class="header__heading__right-user-name">ゲストさん</p>
                    @endif
                    @endif
                </div>
            </div>

            @if(Route::is('index','search'))
            <div class="header__search">
                <form class="header__search-form" action="/search" method="get">
                    @csrf
                    <div class="header__search-form__inner">
                        <div class="header__search-form__inner__group">
                            <div class="header__search-form__inner__group__item search-form__area">
                                <select class="header__search-form__inner__group__item-select" name="area_id" onChange="myFunction()">
                                    <option value="">All area</option>
                                    @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-sharp fa-solid fa-caret-down" style="color: #b0b0b0;"></i>
                            </div>
                            <div class="header__search-form__inner__group__item search-form__genre">
                                <select class="header__search-form__inner__group__item-select" name="genre_id">
                                    <option value="">All genre</option>
                                    @foreach ($genres as $genre)
                                    <option value="{{ $genre->id }}">{{ $genre->genre_name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-sharp fa-solid fa-caret-down" style="color: #b0b0b0;"></i>
                            </div>
                        </div>
                        <div class="header__search-form__inner__group">
                            <div class="header__search-form__inner__group__keyword">
                                <div class="header__search-form__inner__group__keyword-action">
                                    <button class="" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                    <input class="header__search-form__inner__group__keyword-action-input search-form__keyword-input" type="text" name="keyword" placeholder="Search..." value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endif
            @yield('link')
        </header>

        <main class="main">
            @yield('content')
        </main>
    </div>


    <script>
        $(document).ready(function() {
            $('.search-form__group-select').change(function() {
                // フォームデータを取得
                var formData = $('.search-form').serialize();

                // Ajaxリクエストを送信
                $.ajax({
                    type: 'GET',
                    url: '/search',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>