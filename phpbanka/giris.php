<?php
session_start();

$hesapno = "admin";
$sifre = "admin";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $g_hesap = $_POST['hesapno'];
    $g_sifre = $_POST['sifre'];

    if ($g_hesap === $hesapno && $g_sifre === $sifre) {
        $_SESSION['loggedin'] = true;
        header("Location: banka.php");
        exit;
    } else {
        $mesaj = "Geçersiz hesap numarası veya şifre!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İsa Banka - Giriş</title>
    <style>
        body {
            background-color: #FFFFFFFF;
        }

        .div {
            max-width: 400px;
            margin: 80px auto;
            background-color: #D1D1D1FF;
            padding: 30px;
            text-align: center;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #34495e;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
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
    <div class="div">
        <h1>İsa Banka'ya Hoşgeldiniz</h1>
        <form method="POST">
            <label for="hesapnolabel">Hesap Numarası:</label>
            <input type="text" name="hesapno" id="hesapno" required>

            <label for="sifrelabel">Şifre:</label>
            <input type="password" name="sifre" id="sifre" required>

            <button type="submit">Giriş Yap</button>
        </form>

        <?php if (isset($mesaj)): ?>
            <p class="mesaj"><strong><?php echo $mesaj; ?></strong></p>
        <?php endif; ?>
    </div>
</body>
</html>
