<?php
session_start();

$key = $_POST['key'] ?? null;
$kiekis = (int)($_POST['kiekis'] ?? 1);

if ($kiekis < 1) {
    $kiekis = 1;
}

if ($key && isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['kiekis'] = $kiekis;
}

header('Location: view.php');
exit;
