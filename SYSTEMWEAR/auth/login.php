<?php
session_start();
require '../config/db.php';

/* Login logika */
$klaida = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $el_pastas = trim($_POST['el_pastas']);
    $slaptazodis = $_POST['slaptazodis'];

    if ($el_pastas && $slaptazodis) {
        $stmt = $pdo->prepare("SELECT * FROM vartotojai WHERE el_pastas = ?");
        $stmt->execute([$el_pastas]);
        $vartotojas = $stmt->fetch();

        if ($vartotojas && password_verify($slaptazodis, $vartotojas['slaptazodis'])) {
            $_SESSION['user_id'] = $vartotojas['id'];
            $_SESSION['user_name'] = $vartotojas['vardas'];
            $_SESSION['user_role'] = $vartotojas['role'];

            header('Location: ../index.php');
            exit;
        } else {
            $klaida = "Neteisingas el. paštas arba slaptažodis";
        }
    } else {
        $klaida = "Užpildyk visus laukus";
    }
}

/* Kategorijos NAV’ui */
$kategorijos = $pdo->query("SELECT * FROM kategorijos")->fetchAll();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Prisijungimas – SYSTEM:WEAR</title>

<!-- 👇 TAS PATS CSS KAIP index.php -->
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


<!-- LOGIN TURINYS -->
<main style="display:flex; justify-content:center;">
    <div class="product" style="padding:30px;">

        <h2>Prisijungimas</h2>

        <?php if ($klaida): ?>
            <p style="color:red"><?= htmlspecialchars($klaida) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="el_pastas" placeholder="El. paštas" required><br><br>
            <input type="password" name="slaptazodis" placeholder="Slaptažodis" required><br><br>
            <button type="submit">Prisijungti</button>
        </form>

        <p style="margin-top:20px; font-size:14px; color:var(--muted)">
            Neturi paskyros?
            <a href="register.php" style="
                color:var(--accent);
                font-weight:600;
                margin-left:5px;
            ">
        Registruokis
        <br><br>
        <a href="forgot_password.php" style="font-size:14px; color:#8c8c8c;">
            Pamiršai slaptažodį?
        </a>
    </a>
</p>
    </div>
</main>

</body>
</html>
