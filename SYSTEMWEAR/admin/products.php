<?php
session_start();
require '../config/db.php';

/* Tik admin */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die('Prieiga draudžiama');
}

/* Visos prekės su kategorijomis */
$stmt = $pdo->query("
    SELECT p.id, p.pavadinimas, p.kaina, p.nuotrauka, k.pavadinimas AS kategorija
    FROM prekes p
    JOIN kategorijos k ON p.kategorija_id = k.id
    ORDER BY p.id DESC
");
$prekes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Admin – Prekės</title>
<style>
body { font-family: Arial; background:#f6f6f6; padding:40px; }
table { width:100%; border-collapse:collapse; background:#fff; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:left; }
th { background:#eee; }
a { text-decoration:none; margin-right:10px; }
.add { display:inline-block; margin-bottom:20px; }
img { width:60px; }
</style>
</head>
<body>

<h1>🛠 Prekių valdymas (Admin)</h1>



<a class="add" href="product_add.php">➕ Pridėti naują prekę</a>

<br>
<a href="../index.php">← Grįžti į parduotuvę</a>
<br>
<a href="index.php">← Grįžti į administratoriaus aplinką</a>

<table>
<tr>
    <th>ID</th>
    <th>Nuotrauka</th>
    <th>Pavadinimas</th>
    <th>Kategorija</th>
    <th>Kaina (€)</th>
    <th>Veiksmai</th>
</tr>

<?php foreach ($prekes as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td>
        <img src="../public/images/products/<?= $p['nuotrauka'] ?>">
    </td>
    <td><?= htmlspecialchars($p['pavadinimas']) ?></td>
    <td><?= htmlspecialchars($p['kategorija']) ?></td>
    <td><?= number_format($p['kaina'], 2) ?></td>
    <td>
        <a href="product_edit.php?id=<?= $p['id'] ?>">✏️ Redaguoti</a>
        <a href="product_delete.php?id=<?= $p['id'] ?>" 
           onclick="return confirm('Ar tikrai trinti šią prekę?')">🗑️ Trinti</a>
    </td>
</tr>
<?php endforeach; ?>
</table>



</body>
</html>
