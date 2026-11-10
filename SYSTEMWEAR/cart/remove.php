<?php
session_start();

$key = $_POST['key'] ?? null;

if ($key && isset($_SESSION['cart'][$key])) {
    unset($_SESSION['cart'][$key]);
}

header('Location: view.php');
exit;
