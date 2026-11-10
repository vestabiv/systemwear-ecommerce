<?php
session_start();
require 'config/db.php';

/* Kategorijos */
$kategorijos = $pdo->query("SELECT * FROM kategorijos")->fetchAll();

/* Atributai filtrams */
$stmt = $pdo->query("SELECT tipas, pavadinimas FROM atributai ORDER BY tipas, pavadinimas");
$atributai = $stmt->fetchAll();

$spalvos = [];
$medziagos = [];
$dydziai = [];

foreach ($atributai as $a) {
    if ($a['tipas'] === 'spalva') {
        $spalvos[] = $a;
    }
    if ($a['tipas'] === 'medziaga') {
        $medziagos[] = $a;
    }
    if ($a['tipas'] === 'dydis') {
        $dydziai[] = $a;
    }
}

/* Filtrai */
$kategorija_id = isset($_GET['kategorija']) ? (int)$_GET['kategorija'] : 0;
$q = isset($_GET['q']) ? trim($_GET['q']) : ''; //paieska

$filter_spalva = $_GET['spalva'] ?? [];
$filter_medziaga = $_GET['medziaga'] ?? [];
$filter_dydis = $_GET['dydis'] ?? [];


$sql = "SELECT * FROM prekes WHERE 1";
$params = [];

if ($kategorija_id > 0) {
    $sql .= " AND kategorija_id = ?";
    $params[] = $kategorija_id;
}

if ($q !== '') {
    $sql .= " AND (pavadinimas LIKE ? OR aprasymas LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
}

if (!empty($filter_spalva) || !empty($filter_medziaga) || !empty($filter_dydis)) {

    $sql = "
        SELECT p.id, p.pavadinimas, p.aprasymas, p.kaina, p.nuotrauka
        FROM prekes p
        JOIN prekes_atributai pa ON p.id = pa.preke_id
        JOIN atributai a ON pa.atributas_id = a.id
        WHERE 1
    ";
    $params = [];

    $conditions = [];
    $typesCount = 0;

    if (!empty($filter_spalva)) {
        $in = implode(',', array_fill(0, count($filter_spalva), '?'));
        $conditions[] = "(a.tipas = 'spalva' AND a.pavadinimas IN ($in))";
        $params = array_merge($params, $filter_spalva);
        $typesCount++;
    }

    if (!empty($filter_medziaga)) {
        $in = implode(',', array_fill(0, count($filter_medziaga), '?'));
        $conditions[] = "(a.tipas = 'medziaga' AND a.pavadinimas IN ($in))";
        $params = array_merge($params, $filter_medziaga);
        $typesCount++;
    }

    if (!empty($filter_dydis)) {
        $in = implode(',', array_fill(0, count($filter_dydis), '?'));
        $conditions[] = "(a.tipas = 'dydis' AND a.pavadinimas IN ($in))";
        $params = array_merge($params, $filter_dydis);
        $typesCount++;
    }

    $sql .= "
        AND (" . implode(" OR ", $conditions) . ")
        GROUP BY p.id
        HAVING COUNT(DISTINCT a.tipas) = $typesCount
    ";
}



$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prekes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>SYSTEM:WEAR</title>

<style>
:root {
    --bg:#f6f6f6;
    --card:#ffffff;
    --text:#0b0b0b;
    --muted:#8c8c8c;
    --line:#e2e2e2;
    --accent:#00f0ff;
}

* { box-sizing:border-box; }

body {
    margin:0;
    font-family: Arial, Helvetica, sans-serif;
    background:var(--bg);
    color:var(--text);
}

a { text-decoration:none; color:inherit; }

/* ===== HEADER ===== */
header {
    padding:30px 40px;
    border-bottom:1px solid var(--line);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header-left {
    display:flex;
    align-items:center;
    gap:30px;
}

.header-left h1 {
    margin:0;
    font-size:24px;
}

/* SEARCH */
.header-search input {
    padding:8px 12px;
    border:1px solid var(--line);
    font-size:14px;
    width:260px;
}

/* RIGHT */
.header-right {
    display:flex;
    align-items:center;
    gap:15px;
    font-size:14px;
}

/* ===== KATEGORIJOS ===== */
nav.categories {
    display:flex;
    gap:30px;
    padding:20px 40px;
    border-bottom:1px solid var(--line);
}

nav.categories a {
    font-size:14px;
    color:var(--muted);
}

nav.categories a:hover {
    color:var(--accent);
}

/* ===== PRODUCTS ===== */
main {
    padding:40px;
}

.products {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));
    gap:40px;
}

.product {
    background:var(--card);
}

.product img {
    width:100%;
    height:360px;
    object-fit:cover;
}

.product h4 {
    font-size:16px;
    margin:12px 0 6px;
}

.product p {
    font-size:13px;
    color:var(--muted);
}

.price {
    font-weight:600;
}

.product-link {
    display:block;
}

.product-link:hover .product {
    outline:1px solid var(--line);
}

/* ===== TOAST ===== */
.cart-toast {
    position:fixed;
    top:20px;
    right:20px;
    background:#000;
    color:#fff;
    padding:12px 18px;
    font-size:14px;
    z-index:999;
}
</style>
</head>

<body>

<?php
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['kiekis'];
    }
}
?>
<header>
    <div class="header-left">
        <h1><a href="index.php">SYSTEM:WEAR</a></h1>

        <!-- PAIEŠKA -->
        <form method="get" action="index.php" class="header-search">
            <input
                type="text"
                name="q"
                placeholder="Ieškoti prekių"
                value="<?= htmlspecialchars($q ?? '') ?>"
            >
        </form>
    </div>

    <div class="header-right">
        <!-- KREPŠELIS -->
        <a href="cart/view.php">
            Krepšelis<?= $cartCount ? " ($cartCount)" : '' ?>
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- PRISIJUNGĘS -->
            <?= htmlspecialchars($_SESSION['user_name']) ?> |
            <a href="auth/change_password.php">Keisti slaptažodį</a> |
            <a href="auth/logout.php">Atsijungti</a>
        <?php else: ?>
            <!-- SVEČIAS -->
            <a href="auth/login.php">Prisijungti</a> |
            <a href="auth/register.php">Registruotis</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="admin/index.php" style="margin-left:20px;">
            🛠 Eiti į administratoriaus aplinką
        </a>
<?php endif; ?>
    </div>
</header>


<?php if (isset($_SESSION['cart_msg'])): ?>
<div class="cart-toast">
    <?= htmlspecialchars($_SESSION['cart_msg']) ?>
</div>
<?php unset($_SESSION['cart_msg']); endif; ?>

<!-- KATEGORIJOS -->
<nav class="categories">
    <a href="index.php">Rodyti viską</a>
    <?php foreach ($kategorijos as $kat): ?>
        <a href="index.php?kategorija=<?= $kat['id'] ?>">
            <?= htmlspecialchars($kat['pavadinimas']) ?>
        </a>
    <?php endforeach; ?>

<!--  FILTRAI (DEŠINĖJE) -->
    <div style="margin-left:auto; position:relative;">
        <button type="button" onclick="toggleFilters()"
            style="background:none;border:none;cursor:pointer;font-size:14px;color:#8c8c8c;">
            Filtrai ▾
        </button>

        <form id="filters"
              method="get"
              style="display:none; position:absolute; right:0; top:30px;
                     background:#fff; border:1px solid #e2e2e2;
                     padding:15px; width:220px; z-index:10;">

            <!-- IŠSAUGOM KATEGORIJĄ + PAIEŠKĄ -->
            <input type="hidden" name="kategorija" value="<?= $kategorija_id ?>">
            <input type="hidden" name="q" value="<?= htmlspecialchars($q) ?>">

            <strong>Spalva</strong><br>
            <?php foreach ($spalvos as $s): ?>
                <label style="font-size:13px;">
                    <input type="checkbox" name="spalva[]" value="<?= $s['pavadinimas'] ?>">
                    <?= $s['pavadinimas'] ?>
                </label><br>
            <?php endforeach; ?>

            <br><strong>Medžiaga</strong><br>
            <?php foreach ($medziagos as $m): ?>
                <label style="font-size:13px;">
                    <input type="checkbox" name="medziaga[]" value="<?= $m['pavadinimas'] ?>">
                    <?= $m['pavadinimas'] ?>
                </label><br>
            <?php endforeach; ?>

            <br><strong>Dydis</strong><br>
            <?php foreach ($dydziai as $d): ?>
                <label style="font-size:13px;">
                    <input type="checkbox" name="dydis[]" value="<?= $d['pavadinimas'] ?>">
                    <?= $d['pavadinimas'] ?>
                </label><br>
            <?php endforeach; ?>

            <br>
            <button type="submit"
                style="margin-top:10px;padding:6px 12px;border:1px solid #000;background:none;">
                Filtruoti
            </button>
        </form>
    </div>
</nav>
<!-- PREKĖS -->
<main>

<?php if (!$prekes): ?>
    <p style="color:#8c8c8c;">Pagal jūsų paiešką nieko nerasta.</p>
<?php endif; ?>

<div class="products">
    <?php foreach ($prekes as $p): ?>
        <a href="product.php?id=<?= $p['id'] ?>" class="product-link">
            <div class="product">
                <img src="public/images/products/<?= $p['nuotrauka'] ?>" alt="">
                <h4><?= htmlspecialchars($p['pavadinimas']) ?></h4>
                <p><?= htmlspecialchars($p['aprasymas']) ?></p>
                <div class="price"><?= number_format($p['kaina'], 2) ?> €</div>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<script>
function toggleFilters() {
    const f = document.getElementById('filters');
    f.style.display = f.style.display === 'block' ? 'none' : 'block';
}
</script>


</main>

</body>
</html>
