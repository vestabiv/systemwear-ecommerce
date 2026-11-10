<?php 
session_start();
require '../config/db.php';

/* Tik admin */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die('Prieiga draudžiama');
}

$klaida = '';

/* Kategorijos pridėjimas */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pavadinimas = trim($_POST['pavadinimas']);

    if (!$pavadinimas) {
        $klaida = 'Įveskite kategorijos pavadinimą';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO kategorijos (pavadinimas)
            VALUES (?)
        ");
        $stmt->execute([$pavadinimas]);

        header('Location: categories.php');
        exit;
    }
}

/* Kategorijos trynimas */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    /* Tikrinam ar kategorija turi prekių */
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM prekes 
        WHERE kategorija_id = ?
    ");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $klaida = 'Negalima trinti kategorijos, kuri turi priskirtų prekių';
    } else {
        $stmt = $pdo->prepare("
            DELETE FROM kategorijos 
            WHERE id = ?
        ");
        $stmt->execute([$id]);

        header('Location: categories.php');
        exit;
    }
}

/* Visos kategorijos */
$kategorijos = $pdo->query("
    SELECT * 
    FROM kategorijos 
    ORDER BY pavadinimas
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Kategorijos</title>
<style>
body { font-family: Arial; background:#f6f6f6; padding:40px; }
form { background:#fff; padding:20px; max-width:400px; margin-bottom:30px; }
label { display:block; margin-top:10px; }
input { width:100%; padding:8px; }
button { margin-top:15px; padding:8px 15px; }
table { background:#fff; border-collapse:collapse; }
th, td { padding:10px 15px; border:1px solid #ddd; }
.error { color:red; margin-bottom:10px; }
</style>
</head>
<body>

<h1>📂 Kategorijų valdymas</h1>

<?php if ($klaida): ?>
    <div class="error"><?= htmlspecialchars($klaida) ?></div>
<?php endif; ?>

<form method="post">
    <label>Nauja kategorija</label>
    <input type="text" name="pavadinimas">
    <button type="submit">Pridėti</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Pavadinimas</th>
    <th>Veiksmai</th>
</tr>

<?php foreach ($kategorijos as $k): ?>
<tr>
    <td><?= $k['id'] ?></td>
    <td><?= htmlspecialchars($k['pavadinimas']) ?></td>
    <td>
        <a href="categories.php?delete=<?= $k['id'] ?>"
           onclick="return confirm('Ar tikrai norite ištrinti šią kategoriją?')">
           🗑 Trinti
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

<br>
<a href="index.php">← Grįžti į administratoriaus aplinką</a>

</body>
</html>
