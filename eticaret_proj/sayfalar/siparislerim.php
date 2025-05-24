<?php
session_start();
include(__DIR__ . '/../db/config.php');

if (!$conn) {
    die("Veritabanı bağlantısı başarısız!");
}

$user_id = 1;

$sql = "SELECT s.id as siparis_id, s.olusturma_tarihi as siparis_tarihi, u.id as urun_id, u.isim, u.fiyat, so.miktar
        FROM siparisler s
        JOIN siparis_ogeleri so ON s.id = so.siparis_id
        JOIN urunler u ON so.urun_id = u.id
        WHERE s.kullanici_id = ?
        ORDER BY s.olusturma_tarihi DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['siparis_id']]['date'] = $row['siparis_tarihi'];
    $orders[$row['siparis_id']]['items'][] = [
        'urun_id' => $row['urun_id'],
        'isim' => $row['isim'],
        'fiyat' => $row['fiyat'],
        'miktar' => $row['miktar']
    ];
}
?>

<h1>Siparişlerim</h1>

<?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order_id => $order): ?>
        <h2>Sipariş ID: <?= $order_id ?> - Tarih: <?= $order['date'] ?></h2>
        <table border="1">
            <tr>
                <th>Ürün Adı</th>
                <th>Fiyat</th>
                <th>Adet</th>
                <th>Toplam</th>
            </tr>
            <?php 
            $order_total = 0;
            foreach ($order['items'] as $item):
                $line_total = $item['fiyat'] * $item['miktar'];
                $order_total += $line_total;
            ?>
            <tr>
                <td><a href="urundetaylari.php?id=<?= $item['urun_id'] ?>"><?= htmlspecialchars($item['isim']) ?></a></td>
                <td><?= htmlspecialchars($item['fiyat']) ?> TL</td>
                <td><?= $item['miktar'] ?></td>
                <td><?= $line_total ?> TL</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>Genel Toplam</strong></td>
                <td><strong><?= $order_total ?> TL</strong></td>
            </tr>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    <p>Henüz siparişiniz bulunmamaktadır.</p>
<?php endif; ?>
