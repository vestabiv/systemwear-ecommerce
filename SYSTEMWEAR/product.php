<?php
session_start();
require 'config/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

/* Prekė */
$stmt = $pdo->prepare("SELECT * FROM prekes WHERE id = ?");
$stmt->execute([$id]);
$preke = $stmt->fetch();

if (!$preke) {
    echo "Prekė nerasta";
    exit;
}

/* Atributai */
$stmt = $pdo->prepare("
    SELECT a.tipas, a.pavadinimas
    FROM prekes_atributai pa
    JOIN atributai a ON pa.atributas_id = a.id
    WHERE pa.preke_id = ?
");
$stmt->execute([$id]);
$atributai = $stmt->fetchAll();

$spalvos = [];
$medziagos = [];
$dydziai = [];

foreach ($atributai as $a) {
    if ($a['tipas'] === 'spalva') $spalvos[] = $a['pavadinimas'];
    if ($a['tipas'] === 'medziaga') $medziagos[] = $a['pavadinimas'];
    if ($a['tipas'] === 'dydis') $dydziai[] = $a['pavadinimas'];
}



/* Kategorijos */
$kategorijos = $pdo->query("SELECT * FROM kategorijos")->fetchAll();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($preke['pavadinimas']) ?> – SYSTEM:WEAR</title>

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

.header-search input {
    padding:8px 12px;
    border:1px solid var(--line);
    font-size:14px;
    width:260px;
}

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


main {
    max-width:1200px;
    margin:auto;
    padding:40px;
}


.product-page {
    display:grid;
    grid-template-columns: 1fr 1fr;
    gap:40px;
    background:var(--card);
    padding:40px;
}

.product-image img {
    width:100%;
    height:520px;
    object-fit:cover;
}

.product-info h2 {
    margin-top:0;
    font-size:24px;
}

.product-info p {
    font-size:14px;
    color:var(--muted);
}

.price {
    font-size:20px;
    font-weight:600;
    margin:20px 0;
}

.attr {
    margin:12px 0;
    font-size:14px;
}

.attr label {
    margin-right:15px;
    cursor:pointer;
}

button {
    margin-top:20px;
    padding:12px 24px;
    border:1px solid var(--text);
    background:none;
    cursor:pointer;
}

button:hover {
    background:var(--text);
    color:#fff;
}

.hint {
    margin-top:12px;
    font-size:13px;
    color:var(--muted);
}
.cart-toast{
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
    </div>

            <?php if (isset($_SESSION['cart_msg'])): ?>
        <div class="cart-toast">
            <?= htmlspecialchars($_SESSION['cart_msg']) ?>
        </div>
        <?php unset($_SESSION['cart_msg']); endif; ?>

</header>


<nav class="categories">
    <a href="index.php">Rodyti viską</a>
    <?php foreach ($kategorijos as $kat): ?>
        <a href="index.php?kategorija=<?= $kat['id'] ?>">
            <?= htmlspecialchars($kat['pavadinimas']) ?>
        </a>
    <?php endforeach; ?>
</nav>

<main>
    <div class="product-page">
    <div class="product-image">
        <img src="public/images/products/<?= htmlspecialchars($preke['nuotrauka']) ?>" alt="">
    </div>

    <div class="product-info">
        <h2><?= htmlspecialchars($preke['pavadinimas']) ?></h2>
        <p><?= htmlspecialchars($preke['aprasymas']) ?></p>

        <div class="price"><?= number_format($preke['kaina'], 2) ?> €</div>

        <?php if ($spalvos): ?>
            <div class="attr"><strong>Spalvos:</strong> <?= implode(', ', $spalvos) ?></div>
        <?php endif; ?>

        <?php if ($medziagos): ?>
            <div class="attr"><strong>Medžiaga:</strong> <?= implode(', ', $medziagos) ?></div>
        <?php endif; ?>

        <form method="post" action="cart/add.php">
            <input type="hidden" name="preke_id" value="<?= $preke['id'] ?>">
            <input type="number" name="kiekis" value="1" min="1">

            <?php if ($dydziai): ?>
                <div class="attr">
                    <strong>Dydis:</strong><br>
                    <?php foreach ($dydziai as $d): ?>
                        <label>
                            <input type="radio" name="dydis" value="<?= htmlspecialchars($d) ?>" required>
                            <?= htmlspecialchars($d) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <button type="submit">Pridėti į krepšelį</button>
        </form>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <p class="hint">
                Prisijunkite ir gaukite −10 % pirmajam užsakymui
            </p>
        <?php endif; ?>
    </div>
</div>

</main>

</body>
</html>
