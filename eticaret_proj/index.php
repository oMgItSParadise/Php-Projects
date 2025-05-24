<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: sayfalar/giris.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header("Location: index.php");
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
