<?php
session_start();
require '../config/db.php';

/* Tik admin */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die('Prieiga draudžiama');
}

/* Visi užsakymai */
$stmt = $pdo->query("
    SELECT u.id, u.sukurta, u.busena, u.adresas, v.vardas
    FROM uzsakymai u
    JOIN vartotojai v ON u.vartotojas_id = v.id
    ORDER BY u.sukurta DESC
");

$uzsakymai = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Admin – Užsakymai</title>
</head>
<body>

<h1>📦 Užsakymai (Admin)</h1>

<br>



<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Vartotojas</th>
    <th>Data</th>
    <th>Būsena</th>
    <th>Veiksmai</th>
</tr>

<?php foreach ($uzsakymai as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['vardas']) ?></td>
    <td><?= $u['sukurta'] ?></td>
    <td><?= $u['busena'] ?></td>
    <td>
        <a href="order.php?id=<?= $u['id'] ?>">Peržiūrėti</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
<br>
<a href="../index.php">← Grįžti į parduotuvę</a>
<br> <br>
<a href="index.php">← Grįžti į administratoriaus aplinką</a>

</body>
</html>
