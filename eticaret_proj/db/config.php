<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "e_ticaret";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
