<?php
session_start();
require '../config/db.php';

/* Tik admin */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die('Prieiga draudžiama');
}

/* Kategorijos dropdownui */
$kategorijos = $pdo->query("SELECT * FROM kategorijos")->fetchAll();

$klaida = '';

/* Visi atributai */
$atributai = $pdo->query("
    SELECT * FROM atributai
    ORDER BY tipas, pavadinimas
")->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pavadinimas = trim($_POST['pavadinimas']);
    $aprasymas = trim($_POST['aprasymas']);
    $kaina = (float)$_POST['kaina'];
    $kategorija_id = (int)$_POST['kategorija_id'];
    $nuotrauka = trim($_POST['nuotrauka']);

    if (!$pavadinimas || !$kaina || !$kategorija_id || !$nuotrauka) {
        $klaida = 'Užpildykite visus laukus';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO prekes (pavadinimas, aprasymas, kaina, kategorija_id, nuotrauka)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $pavadinimas,
            $aprasymas,
            $kaina,
            $kategorija_id,
            $nuotrauka
        ]);

        $preke_id = $pdo->lastInsertId();

        /* Atributų priskyrimas */
        if (!empty($_POST['atributai'])) {
            $stmt = $pdo->prepare("
                INSERT INTO prekes_atributai (preke_id, atributas_id)
                VALUES (?, ?)
            ");
            foreach ($_POST['atributai'] as $atributas_id) {
                $stmt->execute([$preke_id, (int)$atributas_id]);
            }
        }

        header('Location: products.php');
        exit;

            }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Pridėti prekę</title>
<style>
body { font-family: Arial; background:#f6f6f6; padding:40px; }
form { background:#fff; padding:30px; max-width:500px; }
label { display:block; margin-top:15px; }
input, textarea, select { width:100%; padding:8px; }
button { margin-top:20px; padding:10px 20px; }
.error { color:red; margin-bottom:10px; }
</style>
</head>
<body>

<h1>➕ Pridėti naują prekę</h1>

<?php if ($klaida): ?>
    <div class="error"><?= $klaida ?></div>
<?php endif; ?>

<form method="post">
    <label>Pavadinimas</label>
    <input type="text" name="pavadinimas">

    <label>Aprašymas</label>
    <textarea name="aprasymas" rows="4"></textarea>

    <label>Kaina (€)</label>
    <input type="number" step="0.01" name="kaina">

    <label>Kategorija</label>
    <select name="kategorija_id">
        <option value="">-- pasirinkti --</option>
        <?php foreach ($kategorijos as $k): ?>
            <option value="<?= $k['id'] ?>">
                <?= htmlspecialchars($k['pavadinimas']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Nuotrauka (pvz. jackets/jacket1.jfif)</label>
    <input type="text" name="nuotrauka">

    <label>Atributai</label>

    <?php
    $currentType = '';
    foreach ($atributai as $a):
        if ($a['tipas'] !== $currentType):
            echo "<strong>" . htmlspecialchars($a['tipas']) . "</strong><br>";
            $currentType = $a['tipas'];
        endif;
    ?>
        <label style="margin-left:15px;">
            <input type="checkbox"
                name="atributai[]"
                value="<?= $a['id'] ?>">
            <?= htmlspecialchars($a['pavadinimas']) ?>
        </label><br>
    <?php endforeach; ?>


    <button type="submit">Išsaugoti</button>
</form>

<br>
<a href="products.php">← Grįžti</a>

</body>
</html>
