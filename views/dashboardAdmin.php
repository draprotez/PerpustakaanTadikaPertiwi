<?php
//dashboardAdmin.php
session_start();
include '../config/database.php';

$isLoggedIn = false;
$isAdmin = false;
$nama_user = '';
$total_buku = 0;
$total_member = 0;
$total_overdue = 0;
$active_loans = [];
$search = $_GET['search'] ?? '';

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
    
    $sql_total_overdue = "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'overdue'";
    $stmt_overdue = $conn->prepare($sql_total_overdue);
    $stmt_overdue->execute();
    $total_overdue = $stmt_overdue->get_result()->fetch_assoc()['total'];

    $sql_active_loans = "
        SELECT 
            p.status,
            b.judul_buku,
            m.name,
            m.nisn
        FROM peminjaman p
        JOIN buku b ON p.buku_id = b.id
        JOIN members m ON p.member_id = m.id
        WHERE p.status != 'returned'
    ";
    
    $searchTerm = "%" . $search . "%";
    if (!empty($search)) {
        $sql_active_loans .= " AND (b.judul_buku LIKE ? OR m.name LIKE ? OR m.nisn LIKE ?)";
    }
    
    $sql_active_loans .= "
        ORDER BY p.tenggat_waktu ASC
        LIMIT 10";
    
    $stmt_loans = $conn->prepare($sql_active_loans);
    
    if (!empty($search)) {
        $stmt_loans->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    }
    
    $stmt_loans->execute();
    $active_loans_result = $stmt_loans->get_result();
    while ($row = $active_loans_result->fetch_assoc()) {
        $active_loans[] = $row;
    }
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
        body { font-family: Arial, sans-serif; margin: 20px; }
        .dashboard-container { display: flex; flex-wrap: wrap; gap: 20px; margin: 20px 0; }
        .dashboard-box { border: 1px solid #ddd; background-color: #f9f9f9; padding: 20px; width: 250px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .dashboard-box h3 { margin-top: 0; color: #333; }
        .dashboard-box .count { font-size: 2.5em; font-weight: bold; color: #008CBA; }
        .menu-links { margin-top: 30px; }
        .menu-links a { display: inline-block; padding: 10px 15px; margin: 5px; background-color: #f0f0f0; text-decoration: none; color: #333; border-radius: 4px; border: 1px solid #ddd; }
        .menu-links a:hover { background-color: #e0e0e0; }
        button { padding: 10px 15px; margin: 5px; cursor: pointer; border: 1px solid #ddd; border-radius: 4px; background-color: #f0f0f0; }
        button:hover { background-color: #e0e0e0; }
        .loan-table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 20px; }
        .loan-table th, .loan-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .loan-table th { background-color: #f2f2f2; }
        .status-overdue { color: #D32F2F; font-weight: bold; }
        .status-borrowed { color: #388E3C; }
        .dashboard-box.overdue .count { color: #D32F2F; }
        .access-denied { text-align: center; margin: 50px; padding: 20px; background-color: #ffebee; border: 1px solid #f44336; border-radius: 5px; }
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
                <a href="bukuViews.php">Kelola Buku &raquo;</a>
            </div>
            
            <div class="dashboard-box">
                <h3>Total Anggota</h3>
                <p class="count"><?php echo $total_member; ?></p>
                <a href="memberViews.php">Kelola Anggota &raquo;</a>
            </div>

            <div class="dashboard-box <?php echo ($total_overdue > 0) ? 'overdue' : ''; ?>">
                <h3>Buku Kadaluarsa</h3>
                <p class="count"><?php echo $total_overdue; ?></p>
                <a href="kelolaPeminjamanViews.php">Kelola Peminjaman &raquo;</a>
            </div>
        </div>

        <div class="menu-links">
            <a href="#">Dashboard</a>
            <a href="bukuViews.php">Manajemen Buku</a>
            <a href="peminjamanViews.php">Peminjaman</a>
            <a href="memberViews.php">Anggota</a>
            <a href="carouselViews.php"><strong>Kelola Carousel</strong></a>
            <a href="laporanViews.php">Laporan</a>
            <a href="adminProfilViews.php">Pengaturan</a>
            <a href="../logout.php">Logout</a>
        </div>
        <hr style="margin-top: 20px;">

        <form action="" method="GET" style="margin: 20px 0;">
            <label for="search"><b>Cari Peminjaman Aktif (Judul, Nama, NISN):</b></label><br>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" style="padding: 9px; min-width: 300px;">
            <button type="submit" style="background-color: #008CBA; color: white;">Cari</button>
            <a href="dashboardAdmin.php"><button type="button" style="background-color: #f0f0f0; color: #333;">Reset</button></a>
        </form>
        <h2>Peminjaman Aktif (Hasil Pencarian / 10 Terbaru)</h2>
        <table class="loan-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Jumlah</th>
                    <th>NISN</th>
                    <th>Nama Peminjam</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($active_loans)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            <?php if (!empty($search)): ?>
                                Tidak ditemukan peminjaman aktif dengan kata kunci "<?php echo htmlspecialchars($search); ?>".
                            <?php else: ?>
                                Tidak ada data peminjaman aktif.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <?php foreach ($active_loans as $loan): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($loan['judul_buku']); ?></td>
                            <td>1</td>
                            <td><?php echo htmlspecialchars($loan['nisn'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($loan['name']); ?></td>
                            <td>
                                <?php
                                if ($loan['status'] == 'overdue') {
                                    echo '<span class="status-overdue">Kadaluarsa</span>';
                                } elseif ($loan['status'] == 'borrowed') {
                                    echo '<span class="status-borrowed">Dipinjam</span>';
                                } else {
                                    echo htmlspecialchars($loan['status']);
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <hr>
        <a href="../logout.php"><button type="button">Keluar</button></a>
    
    <?php else : ?>
        <div class="access-denied">
            <h2>Akses Ditolak</h2>
            <p>Halaman ini hanya dapat diakses oleh petugas perpustakaan.</p>
            <p>Silakan <a href="../login.php">login sebagai petugas</a> untuk mengakses dashboard.</p>
            <a href="dashboardAdmin.php"><button type="button">Kembali ke Halaman Utama</button></a>
        </div>
    <?php endif; ?>
</body>
</html>