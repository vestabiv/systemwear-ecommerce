<?php
session_start();
require '../config/db.php';

$cart = $_SESSION['cart'] ?? [];

$total = 0;


foreach ($cart as $item) {
    $stmt = $pdo->prepare("SELECT kaina FROM prekes WHERE id = ?");
    $stmt->execute([$item['preke_id']]);
    $p = $stmt->fetch();

    if ($p) {
        $total += $p['kaina'] * $item['kiekis'];
    }
}


/* === 10 % NUOLAIDA PIRMAJAM PIRKIMUI === */
$nuolaida = 0;
$pirmas_pirkimas = false;

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM uzsakymai WHERE vartotojas_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $order_count = $stmt->fetchColumn();

    if ($order_count == 0) {
        $pirmas_pirkimas = true;
        $nuolaida = $total * 0.10;
    }
}

$galutine_suma = $total - $nuolaida;


if (!$cart) {
    header('Location: view.php');
    exit;
}

$klaida = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $adresas = trim($_POST['adresas']);

    if (!$adresas) {
        $klaida = 'Įveskite pristatymo adresą';
    } else {

        /* === VARTOTOJO / SVEČIO LOGIKA === */
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT vardas, el_pastas FROM vartotojai WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $u = $stmt->fetch();

            $vardas = $u['vardas'];
            $email = $u['el_pastas'];
            $vartotojas_id = $_SESSION['user_id'];

        } else {
            $vardas = trim($_POST['vardas']);
            $email = trim($_POST['email']);
            $vartotojas_id = null;

            if (!$vardas || !$email) {
                $klaida = 'Įveskite vardą ir el. paštą';
            }
        }

        if (!$klaida) {

            $stmt = $pdo->prepare("
                INSERT INTO uzsakymai (vartotojas_id, vardas, el_pastas, adresas, busena, suma)
                VALUES (?, ?, ?, ?, 'Laukiama mokejimo', ?)
            ");
            $stmt->execute([
                $vartotojas_id,
                $vardas,
                $email,
                $adresas,
                $galutine_suma
            ]);


            $uzsakymas_id = $pdo->lastInsertId();

            foreach ($cart as $item) {

                $stmt = $pdo->prepare("SELECT kaina FROM prekes WHERE id = ?");
                $stmt->execute([$item['preke_id']]);
                $p = $stmt->fetch();

                $stmt = $pdo->prepare("
                    INSERT INTO uzsakymo_prekes 
                    (uzsakymas_id, preke_id, kiekis, kaina)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $uzsakymas_id,
                    $item['preke_id'],
                    $item['kiekis'],
                    $p['kaina']
                ]);
            }


            $subject = "Užsakymas gautas – SYSTEM:WEAR";
            $message = "Sveiki $vardas,\n\nJūsų užsakymas #$uzsakymas_id sėkmingai gautas.\n\nPristatymo adresas:\n$adresas\n\nSYSTEM:WEAR";
            $headers = "From: no-reply@systemwear.lt";

            mail($email, $subject, $message, $headers);

            unset($_SESSION['cart']);
            header('Location: success.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Checkout – SYSTEM:WEAR</title>

<style>

body {
    margin:0;
    font-family: Arial, Helvetica, sans-serif;
    background:#f6f6f6;
}

header {
    padding:30px 40px;
    border-bottom:1px solid #e2e2e2;
    display:flex;
    justify-content:space-between;
}

main {
    display:flex;
    justify-content:center;
    padding:40px;
}

.product {
    background:#fff;
    padding:30px;
    max-width:480px;
    width:100%;
}

input, textarea {
    width:100%;
    padding:10px;
    border:1px solid #e2e2e2;
    font-size:14px;
}

button {
    padding:10px 20px;
    border:1px solid #000;
    background:none;
    cursor:pointer;
}

button:hover {
    background:#000;
    color:#fff;
}
</style>
</head>

<body>

<header>
    <h2>SYSTEM:WEAR</h2>
    <a href="view.php">← Grįžti į krepšelį</a>
</header>

<main>
    <div class="product">
        <h2>Užsakymo pateikimas</h2>

        <?php if ($klaida): ?>
            <p style="color:red"><?= htmlspecialchars($klaida) ?></p>
        <?php endif; ?>

        <form method="post">

        <?php if (!isset($_SESSION['user_id'])): ?>
            <input type="text" name="vardas" placeholder="Vardas" required><br><br>
            <input type="email" name="email" placeholder="El. paštas" required><br><br>
        <?php endif; ?>

            <textarea name="adresas" rows="4" placeholder="Pristatymo adresas" required></textarea><br><br>
            <p><strong>Suma:</strong> <?= number_format($total, 2) ?> €</p>

            <?php if ($pirmas_pirkimas): ?>
            <p style="color:green">
                🎉 10 % nuolaida pirmajam pirkimui:
                −<?= number_format($nuolaida, 2) ?> €
            </p>
            <?php endif; ?>

            <p>
                <strong>Mokėtina suma:</strong>
                <?= number_format($galutine_suma, 2) ?> €
            </p>

            <button type="submit">Pateikti užsakymą</button>
        </form>
    </div>
</main>

</body>
</html>
