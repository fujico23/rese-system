<!-- ハンバーガーメニューのコンテンツ -->
<div id="menuContent" class="menu__content">
  <ul class="menu__inner">
    <li>
      <label class="close" onclick="toggleMenu()">
        <i class="fa-solid fa-square-xmark fa-lg" style="color: #0d09fb;"></i>
      </label>
    </li>
    <li class="menu__link"><a href="/">Home</a></li>
    <li class="menu__link"><a href="/register">Registration</a></li>
    <li class="menu__link"><a href="/login">Login</a></li>
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