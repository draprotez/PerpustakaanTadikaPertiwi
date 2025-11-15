<?php
// views/laporanViews.php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/laporanModels.php';

$search = $_GET['search'] ?? ''; 
$timeframe = $_GET['timeframe'] ?? 'all';

$limit = 10; 
$totalResults = countLaporan($conn, $search, $timeframe);
$totalPages = ceil($totalResults / $limit);

$page = (int)($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
} elseif ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}

$offset = ($page - 1) * $limit;
$laporan_list = getLaporan($conn, $search, $timeframe, $limit, $offset);

$searchParam = $search ? '&search=' . htmlspecialchars($search) : '';
$timeframeParam = $timeframe ? '&timeframe=' . htmlspecialchars($timeframe) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        button, select, input[type="text"] { 
            padding: 8px 12px; 
            margin: 0 5px; 
            border-radius: 3px; 
            border: 1px solid #ddd; 
            box-sizing: border-box; 
        }
        button { cursor: pointer; background-color: #008CBA; color: white; border-color: #008CBA; }
        button[type="button"] { background-color: #f0f0f0; color: #333; border-color: #ddd; }
        .filter-form { margin: 20px 0; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 5px; }
        .btn-logout { background-color: red; color: white; padding: 8px 15px; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { display: inline-block; padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; color: #008CBA; }
        .pagination span.current { background-color: #008CBA; color: white; border-color: #008CBA; }
        .pagination a.disabled { color: #999; pointer-events: none; background-color: #f5f5f5; }
    </style>
</head>
<body>

    <h1>Laporan Peminjaman (Buku Kembali)</h1>

    <div class="filter-form">
        <form action="laporanViews.php" method="GET">
            <label for="timeframe">Filter Waktu:</label>
            <select id="timeframe" name="timeframe">
                <option value="all" <?php if ($timeframe == 'all') echo 'selected'; ?>>Semua Waktu</option>
                <option value="harian" <?php if ($timeframe == 'harian') echo 'selected'; ?>>Harian (Hari Ini)</option>
                <option value="mingguan" <?php if ($timeframe == 'mingguan') echo 'selected'; ?>>Mingguan (7 Hari Terakhir)</option>
                <option value="bulanan" <?php if ($timeframe == 'bulanan') echo 'selected'; ?>>Bulanan (1 Bulan Terakhir)</option>
            </select>
            
            <label for="search">Cari (NISN, Nama, Judul):</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari...">
            
            <button type="submit">Filter/Cari</button>
            <a href="laporanViews.php"><button type="button">Reset</button></a>
        </form>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;"><b><?php echo htmlspecialchars($_GET['success']); ?></b></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><b><?php echo htmlspecialchars($_GET['error']); ?></b></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporan_list)): ?>
                <tr>
                    <td colspan="7" align="center">
                        <?php if ($search || $timeframe != 'all'): ?>
                            Data laporan tidak ditemukan dengan filter yang dipilih.
                        <?php else: ?>
                            Belum ada data buku yang dikembalikan.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = $offset + 1; ?> 
                <?php foreach ($laporan_list as $laporan): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($laporan['nisn'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($laporan['name']); ?></td>
                        <td><?php echo htmlspecialchars($laporan['judul_buku']); ?></td>
                        <td>1</td>
                        <td><?php echo date("d-m-Y", strtotime($laporan['tanggal_pinjam'])); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($laporan['tanggal_kembali'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $searchParam; ?><?php echo $timeframeParam; ?>"
               class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                &laquo; Previous
            </a>
            
            <span class="current">
                Halaman <?php echo $page; ?> dari <?php echo $totalPages; ?>
            </span>

            <a href="?page=<?php echo $page + 1; ?><?php echo $searchParam; ?><?php echo $timeframeParam; ?>"
               class="<?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                Next &raquo;
            </a>
        <?php endif; ?>
    </div>
    <br>
    
    <button class="btn-logout" onclick="window.location.href='../controller/logout.php'">Logout</button>
    
</body>
</html>