<?php
session_start();

$id = (int)$_POST['preke_id'];
$kiekis = (int)$_POST['kiekis'];
$dydis = $_POST['dydis'] ?? null;

if ($kiekis < 1) $kiekis = 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* KREPŠELYJE ATSKIRIu PAGAL DYDĮ */
$key = $id . '_' . $dydis;

if (isset($_SESSION['cart'][$key])) {
    $_SESSION['cart'][$key]['kiekis'] += $kiekis;
} else {
    $_SESSION['cart'][$key] = [
        'preke_id' => $id,
        'kiekis' => $kiekis,
        'dydis' => $dydis
    ];
}

$_SESSION['cart_msg'] = 'Prekė pridėta į krepšelį';

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../index.php'));
exit;
