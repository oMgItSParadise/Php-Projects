<?php
session_start();
include(__DIR__ . '/../db/config.php');

if (!$conn) {
    die("Veritabanı bağlantısı başarısız!");
}

if (!isset($_GET['id'])) {
    die("Ürün ID belirtilmedi.");
}

$product_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id_post = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id_post])) {
        $_SESSION['cart'][$product_id_post]++;
    } else {
        $_SESSION['cart'][$product_id_post] = 1;
    }
    header("Location: urundetaylari.php?id=" . $product_id_post);
    exit();
}

$sql = "SELECT urunler.id, urunler.isim, urunler.fiyat, urunler.durum, urunler.stok, saticilar.satici_adi, kategoriler.kategori_adi, urunler.aciklama
        FROM urunler
        LEFT JOIN saticilar ON urunler.satici_id = saticilar.id
        LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id
        WHERE urunler.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Ürün bulunamadı.");
}

$product = $result->fetch_assoc();

?>

<h1><?= htmlspecialchars($product['isim']) ?></h1>
<p><strong>Fiyat:</strong> <?= htmlspecialchars($product['fiyat']) ?> TL</p>
<p><strong>Satıcı:</strong> <?= htmlspecialchars($product['satici_adi']) ?></p>
<p><strong>Kategori:</strong> <?= htmlspecialchars($product['kategori_adi']) ?></p>
<p><strong>Durum:</strong> <?= htmlspecialchars($product['durum']) ?></p>
<p><strong>Stok:</strong> <?= htmlspecialchars($product['stok'] ?? 'Stok bilgisi yok') ?></p>
<p><strong>Açıklama:</strong> <?= nl2br(htmlspecialchars($product['aciklama'])) ?></p>

<form method="post" action="urundetaylari.php?id=<?= $product_id ?>">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <button type="submit" name="add_to_cart">Sepete Ekle</button>
</form>
