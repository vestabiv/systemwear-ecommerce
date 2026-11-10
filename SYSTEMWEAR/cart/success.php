<?php
session_start();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Užsakymas pateiktas – SYSTEM:WEAR</title>

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
    align-items:center;
}

main {
    display:flex;
    justify-content:center;
    padding:60px 40px;
}

.product {
    background:#fff;
    padding:40px;
    max-width:480px;
    width:100%;
    text-align:center;
}

h1 {
    margin-top:0;
}

.success-icon {
    font-size:48px;
    margin-bottom:20px;
}

a.button {
    display:inline-block;
    margin-top:30px;
    padding:10px 20px;
    border:1px solid #000;
    text-decoration:none;
    color:#000;
}

a.button:hover {
    background:#000;
    color:#fff;
}
</style>
</head>

<body>

<header>
    <h2>SYSTEM:WEAR</h2>
    <a href="../index.php">← Grįžti į parduotuvę</a>
</header>

<main>
    <div class="product">
        <div class="success-icon">✅</div>
        <h1>Ačiū!</h1>
        <p>Jūsų užsakymas sėkmingai pateiktas.</p>
        <p>Patvirtinimas išsiųstas el. paštu.</p>

        <a href="../index.php" class="button">Tęsti apsipirkimą</a>
    </div>
</main>

</body>
</html>
