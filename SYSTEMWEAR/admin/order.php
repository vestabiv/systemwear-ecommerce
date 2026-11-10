<?php
session_start();
require '../config/db.php';

/* Tik admin */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die('Prieiga draudžiama');
}

/* Patikrinam ID */
if (!isset($_GET['id'])) {
    die('Nenurodytas užsakymo ID');
}

$id = (int)$_GET['id'];

/* Būsenos atnaujinimas */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['busena'])) {
    $nauja_busena = $_POST['busena'];

    $stmt = $pdo->prepare("UPDATE uzsakymai SET busena = ? WHERE id = ?");
    $stmt->execute([$nauja_busena, $id]);

    header("Location: order.php?id=$id");
    exit;
}

/* Užsakymas */
$stmt = $pdo->prepare("
    SELECT u.*, v.vardas
    FROM uzsakymai u
    JOIN vartotojai v ON u.vartotojas_id = v.id
    WHERE u.id = ?
");
$stmt->execute([$id]);
$uzsakymas = $stmt->fetch();

/* Jei nerastas */
if (!$uzsakymas) {
    die('Užsakymas nerastas');
}

/* Prekės */
$stmt = $pdo->prepare("
    SELECT p.pavadinimas, up.kiekis, up.kaina
    FROM uzsakymo_prekes up
    JOIN prekes p ON up.preke_id = p.id
    WHERE up.uzsakymas_id = ?
");
$stmt->execute([$id]);
$prekes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Užsakymas #<?= $id ?></title>
</head>
<body>

<h1>Užsakymas #<?= $id ?></h1>

<p><strong>Vartotojas:</strong> <?= htmlspecialchars($uzsakymas['vardas']) ?></p>
<p><strong>Adresas:</strong> <?= htmlspecialchars($uzsakymas['adresas']) ?></p>

<form method="post">
    <p>
        <strong>Būsena:</strong>
        <select name="busena">
            <option value="naujas" <?= $uzsakymas['busena']=='naujas'?'selected':'' ?>>Naujas</option>
            <option value="patvirtintas" <?= $uzsakymas['busena']=='patvirtintas'?'selected':'' ?>>Patvirtintas</option>
            <option value="atsauktas" <?= $uzsakymas['busena']=='atsauktas'?'selected':'' ?>>Atšauktas</option>
        </select>
    </p>
    <button type="submit">Atnaujinti būseną</button>
</form>

<h3>Prekės:</h3>
<ul>
<?php foreach ($prekes as $p): ?>
    <li><?= htmlspecialchars($p['pavadinimas']) ?> × <?= $p['kiekis'] ?></li>
<?php endforeach; ?>
</ul>

<a href="orders.php">← Atgal</a>

</body>
</html>
