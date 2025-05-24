<?php
session_start();
include(__DIR__ . '/../db/config.php');

if (!$conn) {
    die("Veritabanı bağlantısı başarısız!");
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Sepetiniz boş.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase'])) {
    $user_id = 1;

    $stmt = $conn->prepare("INSERT INTO siparisler (kullanici_id, olusturma_tarihi) VALUES (?, NOW())");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $stmt_item = $conn->prepare("INSERT INTO siparis_ogeleri (siparis_id, urun_id, miktar) VALUES (?, ?, ?)");
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt_item->execute();
    }

    unset($_SESSION['cart']);

    header("Location: siparislerim.php");
    exit();
}

$cart = $_SESSION['cart'];
$placeholders = implode(',', array_fill(0, count($cart), '?'));
$types = str_repeat('i', count($cart));
$sql = "SELECT id, isim, fiyat FROM urunler WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...array_keys($cart));
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['id']] = $row;
}

?>

<h1>Sepetim</h1>
<?php if (!empty($products)): ?>
<table border="1">
    <tr>
        <th>Ürün Adı</th>
        <th>Fiyat</th>
        <th>Adet</th>
        <th>Toplam</th>
    </tr>
    <?php 
    $total_price = 0;
    foreach ($cart as $product_id => $quantity):
        $product = $products[$product_id];
        $line_total = $product['fiyat'] * $quantity;
        $total_price += $line_total;
    ?>
    <tr>
        <td><?= htmlspecialchars($product['isim']) ?></td>
        <td><?= htmlspecialchars($product['fiyat']) ?> TL</td>
        <td><?= $quantity ?></td>
        <td><?= $line_total ?> TL</td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Genel Toplam</strong></td>
        <td><strong><?= $total_price ?> TL</strong></td>
    </tr>
</table>

<form method="post" action="sepet.php">
    <button type="submit" name="purchase">Satın Al</button>
</form>
<?php else: ?>
<p>Sepetinizde ürün bulunmamaktadır.</p>
<?php endif; ?>
