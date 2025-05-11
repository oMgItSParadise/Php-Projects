<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ./sayfalar/giris.php");
    exit();
}
?>
<header class="header">
  <div class="logo">logo</div>
  <nav class="menus">
    <ul id="menuid">
      <li onclick="">Home</li>
      <li onclick="">About</li>
      <li onclick="">Info</li>
      <li onclick="">Contact</li>
      <li onclick="window.location.href='sayfalar/logout.php';">Çıkış Yap</li>
    </ul>
  </nav>
</header>

<link rel="stylesheet" href="kaynak/css/headerbar.css">
<link rel="stylesheet" href="kaynak/css/main.css">
<script src="kaynak/js/main.js"></script>
