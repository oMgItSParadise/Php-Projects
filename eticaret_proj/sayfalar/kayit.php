<?php
include(__DIR__ . '/../db/config.php');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="../kaynak/css/main.css">
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tam_ad = trim($_POST['tam_ad']);
    $eposta = trim($_POST['eposta']);
    $cinsiyet = $_POST['cinsiyet'];
    $dogum_tarihi = $_POST['dogum_tarihi'];
    $ulke_kodu = $_POST['ulke_kodu'];
    $sifre = $_POST['sifre'];
    $olusturma_tarihi = date("Y-m-d H:i:s");

    if (empty($tam_ad) || empty($eposta) || empty($cinsiyet) || empty($dogum_tarihi) || empty($ulke_kodu) || empty($sifre)) { // Alanlar boş mu kontrol
        $error = "Lütfen tüm alanları doldurun."; 
    } elseif (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) { // E-posta formatı doğrula
        $error = "Geçersiz e-posta formatı."; 
    } else {
        $stmt = $conn->prepare("SELECT id FROM kullanicilar WHERE eposta = ? LIMIT 1"); // E-posta var mı sorgusu hazırla
        $stmt->bind_param("s", $eposta); // Parametre bağla
        $stmt->execute(); // Sorgu çalıştır
        $stmt->store_result(); // Sonuç sakla

        if ($stmt->num_rows > 0) { // E-posta zaten kayıtlı mı kontrol
            $error = "Bu e-posta zaten kayıtlı."; 
        } else {
            $hashed_password = password_hash($sifre, PASSWORD_DEFAULT); // Şifre encrptlenen yer

            $insert_stmt = $conn->prepare("INSERT INTO kullanicilar (tam_ad, eposta, cinsiyet, dogum_tarihi, olusturma_tarihi, ulke_kodu, sifre) VALUES (?, ?, ?, ?, ?, ?, ?)"); // Kayıt ekleme sorgusu
            $insert_stmt->bind_param("sssssis", $tam_ad, $eposta, $cinsiyet, $dogum_tarihi, $olusturma_tarihi, $ulke_kodu, $hashed_password); // Parametreler bağla

            if ($insert_stmt->execute()) { // Kayıt başarılı mı kontrol et
                echo "<p class='success'>Kayıt başarıyla oluşturuldu! Yönlendiriliyorsunuz...</p>"; // Başarı mesajı göster
                header("Refresh: 3; url=giris.php"); // 3 saniye sonra giriş sayfasına yönlendir
                exit();
            } else {
                $error = "Kayıt oluşturulurken hata oluştu: " . htmlspecialchars($conn->error); 
            }
        }
    }
}
?>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label for="tam_ad">Tam Ad:</label>
    <input type="text" name="tam_ad" required><br>

    <label for="eposta">E-posta:</label>
    <input type="email" name="eposta" required><br>

    <label for="cinsiyet">Cinsiyet:</label>
    <select name="cinsiyet">
        <option value="Erkek">Erkek</option>
        <option value="Kadin">Kadın</option>
    </select><br>

    <label for="dogum_tarihi">Doğum Tarihi:</label>
    <input type="date" name="dogum_tarihi" required><br>

    <label for="ulke_kodu">Ülke:</label>
    <input type="number" name="ulke_kodu" required><br>

    <label for="sifre">Şifre:</label>
    <input type="password" name="sifre" required><br>

    <button type="submit">Kayıt Ol</button>
</form>
<p class="have-account"><a href="giris.php">Zaten hesabım var</a></p>
</body>
</html>
