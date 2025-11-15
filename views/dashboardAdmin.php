<?php
session_start();
include '../config/database.php';

$isLoggedIn = false;
$isAdmin = false;
$nama_user = '';
$total_buku = 0;
$total_member = 0;

if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    $isAdmin = true;
    $nama_user = $_SESSION['user_name'];
    $role_user = $_SESSION['user_role'];

    $sql_total_buku = "SELECT COUNT(*) as total FROM buku";
    $stmt = $conn->prepare($sql_total_buku);
    $stmt->execute();
    $total_buku = $stmt->get_result()->fetch_assoc()['total'];

    $sql_total_member = "SELECT COUNT(*) as total FROM members";
    $stmt_member = $conn->prepare($sql_total_member);
    $stmt_member->execute();
    $total_member = $stmt_member->get_result()->fetch_assoc()['total'];
} 
else if (isset($_SESSION['member_id'])) {
    $isLoggedIn = true;
    $isAdmin = false;
    $nama_user = $_SESSION['member_name'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Tadika Pertiwi</title>
    <style>
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
        }
        .dashboard-box {
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            padding: 20px;
            width: 250px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .dashboard-box h3 {
            margin-top: 0;
            color: #333;
        }
        .dashboard-box .count {
            font-size: 2.5em;
            font-weight: bold;
            color: #008CBA;
        }
        .menu-links {
            margin-top: 30px;
        }
        .menu-links a {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            background-color: #f0f0f0;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .menu-links a:hover {
            background-color: #e0e0e0;
        }
        button {
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f0f0f0;
        }
        button:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

    <?php if ($isLoggedIn && $isAdmin) : ?>
        <h1>Dashboard Petugas (<?php echo htmlspecialchars($role_user); ?>)</h1>
        <p>Selamat datang, <?php echo htmlspecialchars($nama_user); ?>!</p>
        
        <div class="dashboard-container">
            <div class="dashboard-box">
                <h3>Total Judul Buku</h3>
                <p class="count"><?php echo $total_buku; ?></p>
                <a href="views/bukuViews.php">Kelola Buku &raquo;</a>
            </div>
            
            <div class="dashboard-box">
                <h3>Total Anggota</h3>
                <p class="count"><?php echo $total_member; ?></p>
                <a href="views/memberViews.php">Kelola Anggota &raquo;</a>
            </div>
        </div>

        <div class="menu-links">
            <a href="views/bukuViews.php">Kelola Buku</a>
            <a href="views/peminjamanViews.php">Kelola Peminjaman</a>
            <a href="views/memberViews.php">Kelola Anggota</a>
            <a href="views/laporanViews.php">Laporan</a>
        </div>

        <hr>
        <a href="logout.php"><button type="button">Keluar</button></a>

    <?php elseif ($isLoggedIn && !$isAdmin) : ?>
        <h1>Selamat datang kembali, <?php echo htmlspecialchars($nama_user); ?>!</h1>
        <p>Anda sekarang sudah masuk ke sistem Perpustakaan Tadika Pertiwi.</p>
        <a href="profil_member.php"><button type="button">Lihat Profil</button></a>
        <a href="logout.php"><button type="button">Keluar</button></a>

    <?php else : ?>
        <h1>Selamat datang di perpustakaan tadika Pertiwi</h1>
        <a href="login.php"><button type="button">Masuk</button></a>
        <a href="register.php"><button type="button">Daftar</button></a>
    <?php endif; ?>
</body>
</html>