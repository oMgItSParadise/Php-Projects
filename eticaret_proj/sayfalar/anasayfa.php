<?php
include(__DIR__ . '/../db/config.php');

if (!$conn) {
    die("Veritabanı bağlantısı başarısız!");
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
    header("Location: anasayfa.php");
    exit();
}

$sql = "SELECT urunler.id, urunler.isim, urunler.fiyat, saticilar.satici_adi, kategoriler.kategori_adi 
        FROM urunler 
        LEFT JOIN saticilar ON urunler.satici_id = saticilar.id 
        LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    echo "Hiç ürün bulunamadı.";
}

?>

<h1>Ürünler</h1>
<?php if (!empty($rows)): ?>
<table border="1">
    <tr>
        <th>Ürün Adı</th>
        <th>Fiyat</th>
        <th>Satıcı</th>
        <th>Kategori</th>
        <th>İşlemler</th>
    </tr>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['isim']) ?></a></td>
            <td><?= htmlspecialchars($row['fiyat']) ?> TL</td>
            <td><?= htmlspecialchars($row['satici_adi']) ?></td>
            <td><?= htmlspecialchars($row['kategori_adi']) ?></td>
            <td>
                <form method="post" action="sayfalar/sepet.php">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit" name="add_to_cart">Sepete Ekle</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
