<?php
session_start();
$isLoggedIn = false;
$nama_user = '';

if (isset($_SESSION['member_name'])) {
    $isLoggedIn = true;
    $nama_user = $_SESSION['member_name'];
} 
else if (isset($_SESSION['user_name'])) {
    $isLoggedIn = true;
    $nama_user = $_SESSION['user_name'];
}
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
    if ($isLoggedIn) :
    ?>
        <h1>Selamat datang kembali, <?php echo htmlspecialchars($nama_user); ?>!</h1>
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