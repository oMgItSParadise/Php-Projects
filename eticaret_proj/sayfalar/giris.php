<?php
include('../db/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eposta = trim($_POST['eposta']);
    $sifre = $_POST['sifre'];

    if (filter_var($eposta, FILTER_VALIDATE_EMAIL) === false) { // E-posta formatı doğrulama
        $error = "Geçersiz e-posta formatı.";
    } else {
        $stmt = $conn->prepare("SELECT id, tam_ad, sifre FROM kullanicilar WHERE eposta = ? LIMIT 1"); // Kullanıcı sorgusu hazırla
        $stmt->bind_param("s", $eposta); // E-posta parametresi bağlan
        $stmt->execute(); // Sorgu çalıştır
        $result = $stmt->get_result(); // Sonuç al

        if ($result && $result->num_rows == 1) { // Kullanıcı bulunursa
            $user = $result->fetch_assoc(); // Kullanıcı verisi al
            $sifre_encrypt = $user['sifre']; // Şifre verisi al

            if (password_verify($sifre, $sifre_encrypt)) { // Encryptlenmiş şifre doğrula
                $_SESSION['user_id'] = $user['id']; // Oturuma kullanıcı ID ata
                $_SESSION['user_name'] = $user['tam_ad']; // Oturuma kullanıcı adı ata
                header("Location: ../index.php"); // Ana sayfaya yönlendirme
                exit();
            } elseif ($sifre === $sifre_encrypt) { // Eğer şifre encryptli değilse eski şifre kontrolü
                $yeni_hash = password_hash($sifre, PASSWORD_DEFAULT); // Şifreyi encryptle
                $update_stmt = $conn->prepare("UPDATE kullanicilar SET sifre = ? WHERE id = ?"); // Şifre güncelleme sorgusu
                $update_stmt->bind_param("si", $yeni_hash, $user['id']); // Parametreler bağla
                $update_stmt->execute(); // Sorgu çalıştır

                $_SESSION['user_id'] = $user['id']; // Oturuma kullanıcı ID'si ata
                $_SESSION['user_name'] = $user['tam_ad']; // Oturuma kullanıcı adı ata
                header("Location: ../index.php"); // Ana sayfaya yönlendirme
                exit();
            } else {
                $error = "Geçersiz e-posta veya şifre."; nır
            }
        } else {
            $error = "Geçersiz e-posta veya şifre."; nır
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="../kaynak/css/main.css">
</head>
<body>
    <h2>Giriş Yap</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="eposta">E-posta:</label>
        <input type="email" name="eposta" required><br>

        <label for="sifre">Şifre:</label>
        <input type="password" name="sifre" required><br>

    <button type="submit">Giriş Yap</button>
</form>
<p class="no-account"><a href="kayit.php">Hesabım yok</a></p>
</body>
</html>
