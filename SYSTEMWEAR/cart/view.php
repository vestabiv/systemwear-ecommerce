<?php
session_start();
require '../config/db.php';

$cart = $_SESSION['cart'] ?? [];

/* Kategorijos NAV  */
$kategorijos = $pdo->query("SELECT * FROM kategorijos")->fetchAll();

/* Krepšelio kiekis (sumuojam kiekį per visus item) */
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $it) {
        $cartCount += (int)$it['kiekis'];
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Krepšelis – SYSTEM:WEAR</title>

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

/* ===== MAIN ===== */
main {
    max-width:1200px;
    margin:auto;
    padding:40px;
}

h1 { margin-top:0; }

/* ===== CART LIST ===== */
.cart-list {
    display:flex;
    flex-direction:column;
    gap:16px;
}

.cart-item {
    background:var(--card);
    border:1px solid var(--line);
    padding:18px;
    display:grid;
    grid-template-columns: 1fr 260px 140px;
    gap:18px;
    align-items:center;
}

.cart-title {
    font-weight:700;
    margin-bottom:6px;
}

.cart-meta {
    color:var(--muted);
    font-size:14px;
    line-height:1.4;
}

.cart-price {
    margin-top:8px;
    font-size:14px;
}

.cart-actions {
    display:flex;
    justify-content:flex-end;
    gap:10px;
    flex-wrap:wrap;
}

.qty-form {
    display:flex;
    gap:8px;
    align-items:center;
}

.qty-form input[type="number"]{
    width:70px;
    padding:8px 10px;
    border:1px solid var(--line);
    font-size:14px;
}

.btn {
    padding:8px 12px;
    border:1px solid var(--text);
    background:none;
    cursor:pointer;
    font-size:14px;
}

.btn:hover {
    background:var(--text);
    color:#fff;
}

.btn-danger {
    border-color:#bbb;
}

.btn-danger:hover {
    background:#111;
    color:#fff;
}

.cart-sum {
    text-align:right;
    font-weight:700;
    font-size:16px;
}

.cart-total {
    margin-top:22px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:var(--card);
    border:1px solid var(--line);
    padding:18px;
}

.checkout-btn {
    display:inline-block;
    padding:10px 16px;
    border:1px solid var(--text);
}

.checkout-btn:hover {
    background:var(--text);
    color:#fff;
}

.empty {
    color:var(--muted);
}
</style>
</head>

<body>

<header>
    <div class="header-left">
        <h1><a href="../index.php">SYSTEM:WEAR</a></h1>

        <!-- paieška paliekam, kad headeris būtų toks pats -->
        <form method="get" action="../index.php" class="header-search">
            <input type="text" name="q" placeholder="Ieškoti prekių">
        </form>
    </div>

    <div class="header-right">
        <a href="view.php">Krepšelis<?= $cartCount ? " ($cartCount)" : '' ?></a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?= htmlspecialchars($_SESSION['user_name']) ?> |
            <a href="../auth/change_password.php">Keisti slaptažodį</a> |
            <a href="../auth/logout.php">Atsijungti</a>
        <?php else: ?>
            <a href="../auth/login.php">Prisijungti</a> |
            <a href="../auth/register.php">Registruotis</a>
        <?php endif; ?>
    </div>
</header>

<nav class="categories">
    <a href="../index.php">Rodyti viską</a>
    <?php foreach ($kategorijos as $kat): ?>
        <a href="../index.php?kategorija=<?= $kat['id'] ?>">
            <?= htmlspecialchars($kat['pavadinimas']) ?>
        </a>
    <?php endforeach; ?>
</nav>

<main>
    <h1>Krepšelis</h1>

    <?php if (!$cart): ?>
        <p class="empty">Krepšelis tuščias</p>
    <?php else: ?>

        <div class="cart-list">
        <?php
        $total = 0;

        foreach ($cart as $key => $item):
            $preke_id = (int)$item['preke_id'];
            $kiekis   = (int)$item['kiekis'];
            $dydis    = $item['dydis'];

            $stmt = $pdo->prepare("SELECT * FROM prekes WHERE id = ?");
            $stmt->execute([$preke_id]);
            $p = $stmt->fetch();

            if (!$p) continue;

            $sum = (float)$p['kaina'] * $kiekis;
            $total += $sum;
        ?>
            <div class="cart-item">
                <div>
                    <div class="cart-title"><?= htmlspecialchars($p['pavadinimas']) ?></div>
                    <div class="cart-meta">
                        <?php if ($dydis): ?>Dydis: <?= htmlspecialchars($dydis) ?><br><?php endif; ?>
                        <?= number_format((float)$p['kaina'], 2) ?> € / vnt
                    </div>
                </div>

                <div class="cart-actions">
                    <form class="qty-form" method="post" action="update.php">
                        <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                        <input type="number" name="kiekis" value="<?= $kiekis ?>" min="1">
                        <button class="btn" type="submit">Atnaujinti</button>
                    </form>

                    <form method="post" action="remove.php"
                        onsubmit="return confirm('Ar tikrai norite pašalinti šią prekę iš krepšelio?');">
                        <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
                        <button class="btn btn-danger" type="submit">✕</button>
                    </form>
                </div>

                <div class="cart-sum">
                    <?= number_format($sum, 2) ?> €
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <div class="cart-total">
            <strong>Iš viso: <?= number_format($total, 2) ?> €</strong>
            <a class="checkout-btn" href="checkout.php">Pereiti prie apmokėjimo</a>
        </div>

    <?php endif; ?>
</main>

</body>
</html>
