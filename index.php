<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Tadika Pertiwi</title>
</head>
<body>
    <?php
    if (isset($_SESSION['name'])) :
    ?>
        <h1>Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
        <p>Anda sekarang sudah masuk ke sistem Perpustakaan Tadika Pertiwi.</p>
        <a href="dashboard.php"><button type="button">Pergi ke Dashboard</button></a>
        <a href="logout.php"><button type="button">Keluar</button></a>
    <?php
    else :
    ?>
        <h1>Selamat datang di perpustakaan tadika Pertiwi</h1>
        <a href="login.php"><button type="button">Masuk</button></a>
        <a href="register.php"><button type="button">Daftar</button></a>
    <?php
    endif;
    ?>
</body>
</html>