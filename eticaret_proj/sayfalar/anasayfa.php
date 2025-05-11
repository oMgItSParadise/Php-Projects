<?php
include(__DIR__ . '/../db/config.php');

if (!$conn) {
    die("Veritabanı bağlantısı başarısız!");
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
    </tr>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['isim']) ?></td>
            <td><?= htmlspecialchars($row['fiyat']) ?> TL</td>
            <td><?= htmlspecialchars($row['satici_adi']) ?></td>
            <td><?= htmlspecialchars($row['kategori_adi']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
