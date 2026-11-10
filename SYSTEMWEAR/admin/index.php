
<?php
session_start();

/* Tik admin */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die('Prieiga draudžiama');
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Admin panelė</title>
<style>
body { font-family: Arial; background:#f6f6f6; padding:40px; }
a { display:block; margin:15px 0; font-size:18px; }
.box { background:#fff; padding:30px; max-width:400px; }
</style>
</head>
<body>

<h1>🛠 Administratoriaus aplinka</h1>

<div class="box">
    <a href="products.php">Valdyti prekes</a>
    <a href="categories.php">Valdyti kategorijas</a>
    <a href="orders.php">Peržiūrėti užsakymus</a>
    <a href="../auth/logout.php">Atsijungti</a>
</div>

</body>
</html>
