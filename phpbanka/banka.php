<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: giris.php");
    exit;
}

if (!isset($_SESSION['hesap_para'])) {
    $_SESSION['hesap_para'] = 1000;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['islem'])) {
        $islem = $_POST['islem'];
        $tutar = isset($_POST['tutar']) ? floatval($_POST['tutar']) : 0;

        switch ($islem) {
            case 'cek':
                if ($tutar > 0 && $tutar <= $_SESSION['hesap_para']) {
                    $_SESSION['hesap_para'] -= $tutar;
                    $mesaj = "Başarıyla $tutar TL çekildi.";
                } else {
                    $mesaj = "Geçersiz tutar veya bakiyeniz yetersiz!";
                }
                break;

            case 'yatir':
                if ($tutar > 0) {
                    $_SESSION['hesap_para'] += $tutar;
                    $mesaj = "Başarıyla $tutar TL yatırıldı.";
                } else {
                    $mesaj = "Geçersiz tutar!";
                }
                break;

            case 'sorgula':
                $mesaj = "Mevcut bakiyeniz: " . $_SESSION['hesap_para'] . " TL.";
                break;

            case 'cikis':
                session_destroy();
                header("Location: giris.php");
                exit;

            default:
                $mesaj = "Geçersiz işlem!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İsa Banka</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        .div {
            max-width: 400px;
            margin: 80px auto;
            background-color: #E7E5E5FF;
            padding: 30px;
            text-align: center;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 40px;
            text-align: center;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #3498db;
        }

        p.mesaj {
            color: red;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>İsa Banka'ya Hoşgeldiniz</h1>
    <div class="div">
        <form method="POST">
            <p>Yapmak istediğiniz işlemi seçiniz:</p>
            <label><input type="radio" name="islem" value="cek" required> 1 - Para Çekme</label><br>
            <label><input type="radio" name="islem" value="yatir"> 2 - Para Yatırma</label><br>
            <label><input type="radio" name="islem" value="sorgula"> 3 - Bakiye Sorgula</label><br>
            <label><input type="radio" name="islem" value="cikis"> 4 - Çıkış</label><br><br>

            <label for="tutar">Tutar (Sadece Çekme/Yatırma için):</label>
            <input type="number" step="0.01" name="tutar" id="tutar"><br><br>

            <button type="submit">Gönder</button>
        </form>

        <?php if (isset($mesaj)): ?>
            <p class="mesaj"><strong><?php echo $mesaj; ?></strong></p>
        <?php endif; ?>
    </div>
</body>
</html>
