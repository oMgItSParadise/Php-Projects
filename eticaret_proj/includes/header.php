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
      <li onclick="window.location.href='sayfalar/siparislerim.php';">Siparişlerim</li>
      <li onclick="window.location.href='sayfalar/sepet.php';">Sepetim</li>
      <li onclick="window.location.href='sayfalar/logout.php';">Çıkış Yap</li>
    </ul>
  </nav>
</header>

<link rel="stylesheet" href="kaynak/css/headerbar.css">
<link rel="stylesheet" href="kaynak/css/main.css">
<script src="kaynak/js/main.js"></script>
