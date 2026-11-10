<?php
session_start();
require '../config/db.php';

/* Registracijos logika */
$klaida = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vardas = trim($_POST['vardas']);
    $el_pastas = trim($_POST['el_pastas']);
    $slaptazodis = $_POST['slaptazodis'];

    if ($vardas && $el_pastas && $slaptazodis) {
        $hash = password_hash($slaptazodis, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO vartotojai (vardas, el_pastas, slaptazodis) VALUES (?, ?, ?)"
        );

        try {
        $stmt->execute([$vardas, $el_pastas, $hash]);

        $user_id = $pdo->lastInsertId();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $vardas;
        $_SESSION['user_role'] = 'user';

        header('Location: ../index.php');
        exit;
    }
 catch (PDOException $e) {
            $klaida = "Toks el. paštas jau egzistuoja";
        }
    } else {
        $klaida = "Užpildyk visus laukus";
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Registracija – SYSTEM:WEAR</title>

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

/* HEADER */
header {
    padding:30px 40px;
    border-bottom:1px solid var(--line);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

header h1 {
    font-size:24px;
}

input {
    width:100%;
    padding:10px;
    border:1px solid var(--line);
    font-size:14px;
}

button {
    padding:10px 20px;
    border:1px solid var(--text);
    background:none;
    cursor:pointer;
}

button:hover {
    background:var(--text);
    color:#fff;
}

.product {
    background:var(--card);
}
</style>
</head>

<body>

<!-- HEADER -->
<header>
    <h1>SYSTEM:WEAR</h1>
    <div>
        <a href="../index.php">← Grįžti į parduotuvę</a>
    </div>
</header>

<!-- REGISTER TURINYS -->
<main style="display:flex; justify-content:center;">
    <div class="product" style="padding:30px; max-width:420px; width:100%;">

        <h2>Registracija</h2>

        <?php if ($klaida): ?>
            <p style="color:red"><?= htmlspecialchars($klaida) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="vardas" placeholder="Vardas" required><br><br>
            <input type="email" name="el_pastas" placeholder="El. paštas" required><br><br>
            <input type="password" name="slaptazodis" placeholder="Slaptažodis" required><br><br>
            <button type="submit">Registruotis</button>
        </form>

        <p style="margin-top:20px; font-size:14px; color:var(--muted)">
            Jau turi paskyrą?
            <a href="login.php" style="
                color:var(--accent);
                font-weight:600;
                margin-left:5px;
            ">
                Prisijunk
            </a>
        </p>

    </div>
</main>

</body>
</html>
