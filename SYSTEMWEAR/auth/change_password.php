<?php
session_start();
require '../config/db.php';

/* Tik prisijungusiam */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$klaida = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senas = $_POST['old_password'] ?? '';
    $naujas = $_POST['new_password'] ?? '';

    if (!$senas || !$naujas) {
        $klaida = 'Užpildykite visus laukus';
    } else {
        /* Paimam dabartinį slaptažodį */
        $stmt = $pdo->prepare("SELECT slaptazodis FROM vartotojai WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($senas, $user['slaptazodis'])) {
            $klaida = 'Senas slaptažodis neteisingas';
        } else {
            $hash = password_hash($naujas, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "UPDATE vartotojai SET slaptazodis = ? WHERE id = ?"
            );
            $stmt->execute([$hash, $_SESSION['user_id']]);

            $success = 'Slaptažodis sėkmingai pakeistas';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Keisti slaptažodį – SYSTEM:WEAR</title>

<style>
:root {
    --bg:#f6f6f6;
    --card:#ffffff;
    --text:#0b0b0b;
    --muted:#8c8c8c;
    --line:#e2e2e2;
    --accent:#00f0ff;
}

body {
    margin:0;
    font-family: Arial, Helvetica, sans-serif;
    background:var(--bg);
    color:var(--text);
}

header {
    padding:30px 40px;
    border-bottom:1px solid var(--line);
}

main {
    display:flex;
    justify-content:center;
    padding:60px 20px;
}

.box {
    background:var(--card);
    padding:30px;
    width:100%;
    max-width:420px;
}

input {
    width:100%;
    padding:10px;
    border:1px solid var(--line);
}

button {
    margin-top:10px;
    padding:10px 20px;
    border:1px solid var(--text);
    background:none;
    cursor:pointer;
}

button:hover {
    background:var(--text);
    color:#fff;
}

.msg-error {
    color:red;
    margin-bottom:10px;
}

.msg-success {
    color:green;
    margin-bottom:10px;
}
</style>
</head>

<body>

<header>
    <a href="../index.php">← Grįžti į parduotuvę</a>
</header>

<main>
    <div class="box">
        <h2>Keisti slaptažodį</h2>

        <?php if ($klaida): ?>
            <div class="msg-error"><?= htmlspecialchars($klaida) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="msg-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="password" name="old_password" placeholder="Senas slaptažodis" required><br><br>
            <input type="password" name="new_password" placeholder="Naujas slaptažodis" required><br><br>

            <button type="submit">Keisti slaptažodį</button>
        </form>
    </div>
</main>

</body>
</html>
