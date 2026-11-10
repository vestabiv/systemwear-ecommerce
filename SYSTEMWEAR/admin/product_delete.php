<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die('Prieiga draudžiama');
}

if (!isset($_GET['id'])) {
    die('Nenurodytas ID');
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("DELETE FROM prekes WHERE id = ?");
$stmt->execute([$id]);

header('Location: products.php');
exit;
