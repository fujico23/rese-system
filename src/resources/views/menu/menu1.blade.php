<!-- ハンバーガーメニューのコンテンツ -->
<div id="menuContent" class="menu__content">
  <ul class="menu__inner">
    <li>
      <label class="close" onclick="toggleMenu()">
        <i class="fa-solid fa-square-xmark fa-lg" style="color: #0d09fb;"></i>
      </label>
    </li>
    <li class="menu__link"><a href="/">Home</a></li>
    <li class="menu__link"><a href="/mypage">Mypage</a></li>
    <li class="menu__link"><a href="/admin">admin</a></li>
    <li class="menu__link"><a href="/admin/shop/create">Shop Create</a></li>
    <li class="menu__link">
      <form class="form" action="/logout" method="post">
        @csrf
        <button class="header-nav__button">Logout</button>
      </form>
    </li>
  </ul>
</div>

<script>
  const menu = document.getElementById('menuContent');

  // メニューの初期状態は非表示
  menu.style.display = 'none';

  function toggleMenu() {
    // メニューの表示/非表示を切り替える
    if (menu.style.display === 'block') {
      menu.style.display = 'none';
    } else {
      menu.style.display = 'block';
    }
  }
</script>