<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: sayfalar/giris.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Eticaret Projesi</title>
    <link rel="stylesheet" href="kaynak/css/main.css">
</head>
<body>
    <?php
        include('includes/header.php');
        include('sayfalar/anasayfa.php');
        include('includes/footer.php');
    ?>
</body>
</html>
