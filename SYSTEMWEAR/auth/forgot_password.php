<?php
session_start();
require '../config/db.php';

$klaida = '';
$success = '';

function generatePassword($length = 8) {
    return substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['el_pastas']);

    if (!$email) {
        $klaida = 'Įveskite el. paštą';
    } else {
        $stmt = $pdo->prepare("SELECT id, vardas FROM vartotojai WHERE el_pastas = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $klaida = 'Vartotojas su tokiu el. paštu nerastas';
        } else {
            $newPassword = generatePassword();
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "UPDATE vartotojai SET slaptazodis = ? WHERE id = ?"
            );
            $stmt->execute([$hash, $user['id']]);

            /* EL. LAIŠKAS */
            $subject = "Slaptažodžio atkūrimas – SYSTEM:WEAR";
            $message = "
Sveiki {$user['vardas']},

Jūsų naujas laikinas slaptažodis:

$newPassword

Prisijungę rekomenduojame jį pasikeisti.

--
SYSTEM:WEAR
";
            $headers = "From: no-reply@systemwear.lt";

            mail($email, $subject, $message, $headers);

            $success = 'Naujas slaptažodis išsiųstas į jūsų el. paštą';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Pamiršau slaptažodį – SYSTEM:WEAR</title>

<style>
:root {
    --bg:#f6f6f6;
    --card:#ffffff;
    --text:#0b0b0b;
    --muted:#8c8c8c;
    --line:#e2e2e2;
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
    <a href="login.php">← Grįžti į prisijungimą</a>
</header>

<main>
    <div class="box">
        <h2>Pamiršau slaptažodį</h2>

        <?php if ($klaida): ?>
            <div class="msg-error"><?= htmlspecialchars($klaida) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="msg-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="el_pastas" placeholder="El. paštas" required><br><br>
            <button type="submit">Atkurti slaptažodį</button>
        </form>
    </div>
</main>

</body>
</html>
