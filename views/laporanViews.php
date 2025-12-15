<?php
// views/laporanViews.php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/laporanModels.php';
include '../header.php';

$search = $_GET['search'] ?? ''; 
$timeframe = $_GET['timeframe'] ?? 'all';
$status = $_GET['status'] ?? 'all'; // <-- Variabel Baru untuk Status

$limit = 10; 
// Kirim $status ke fungsi model
$totalResults = countLaporan($conn, $search, $timeframe, $status);
$totalPages = ceil($totalResults / $limit);

$page = (int)($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
} elseif ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}

$offset = ($page - 1) * $limit;
// Kirim $status ke fungsi model
$laporan_list = getLaporan($conn, $search, $timeframe, $status, $limit, $offset);

$searchParam = $search ? '&search=' . htmlspecialchars($search) : '';
$timeframeParam = $timeframe ? '&timeframe=' . htmlspecialchars($timeframe) : '';
$statusParam = $status ? '&status=' . htmlspecialchars($status) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perpustakaan</title>
    <link rel="website icon" type="png" href="../assets/images/logo/logo-smk.png" />
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
      
           .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { display: inline-block; padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; color: #008CBA; }
        .pagination span.current { background-color: #008CBA; color: white; border-color: #008CBA; }
        .pagination a.disabled { color: #999; pointer-events: none; background-color: #f5f5f5; }
        td {
    background-color: white !important;
}

        /* Style untuk status */
        .status-returned { color: green; font-weight: bold; }
        .status-borrowed { color: orange; font-weight: bold; }
        .status-overdue { color: red; font-weight: bold; }
    </style>
</head>
<body class="ml-[320px]  bg-[#EDF0F7]">

    <?php include 'partials/sidebar.php'; ?>

    <p class="font-semibold text-xl my-5 mt-2  bg-white rounded-xl shadow-md py-4 md:p-6">Laporan Peminjaman Buku</p>

    <div class="filter-form">
        <form action="laporanViews.php" method="GET">
            
            <label for="status">Status:</label>
            <select class="h-10 my-2 border-2 rounded-full px-2 w-[170px]" id="status" name="status">
                <option value="all" <?php if ($status == 'all') echo 'selected'; ?>>Semua Status</option>
                <option value="borrowed" <?php if ($status == 'borrowed') echo 'selected'; ?>>Sedang Dipinjam (Belum Kembali)</option>
                <option value="returned" <?php if ($status == 'returned') echo 'selected'; ?>>Sudah Dikembalikan (Riwayat)</option>
            </select>
<br>
            <label for="timeframe">Waktu:</label>
            <select class="my-2 h-10 px-2 rounded-full border-2" id="timeframe" name="timeframe">
                <option value="all" <?php if ($timeframe == 'all') echo 'selected'; ?>>Semua Waktu</option>
                <option value="harian" <?php if ($timeframe == 'harian') echo 'selected'; ?>>Harian (Hari Ini)</option>
                <option value="mingguan" <?php if ($timeframe == 'mingguan') echo 'selected'; ?>>Mingguan (7 Hari)</option>
                <option value="bulanan" <?php if ($timeframe == 'bulanan') echo 'selected'; ?>>Bulanan (1 Bulan)</option>
            </select>
            <br>
            <label class="ml-3" for="search">Cari:</label>
           <div class="relative inline-block py-3 mx-2" style="vertical-align: middle;">
    <input 
        type="text" 
        id="search" 
        name="search" 
        value="<?php echo htmlspecialchars($search); ?>" 
        placeholder="NISN, Nama, atau Judul..."
        class="rounded-full pr-10 pl-2 border-2 border-gray-300 h-10"
        style="padding:5px; padding-right:34px; ;"
    >

    <img 
        src="../assets/images/icon/mingcute_search-line (1).png" 
        alt="" 
        aria-hidden="true" 
        class="absolute right-2 top-1/2" 
        style="transform: translateY(-50%); width:16px; height:16px; pointer-events: none; opacity:0.8;"
    />
</div>

            <button class="bg-blue-500 text-white font-semibold rounded-full px-5 py-2" type="submit">Cari</button>
            <a class="bg-red-500 text-white font-semibold rounded-full px-5 py-3" href="laporanViews.php"><button type="button">Reset</button></a>
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
                <th class="bg-[#73A7DB]">No</th>
                <th class="bg-[#73A7DB]">NISN</th>
                <th class="bg-[#73A7DB]">Nama Peminjam</th>
                <th class="bg-[#73A7DB]">Judul Buku</th>
                <th class="bg-[#73A7DB]">Tgl Pinjam</th>
                <th class="bg-[#73A7DB]">Tenggat Waktu</th>
                <th class="bg-[#73A7DB]">Tgl Kembali</th>
                <th class="bg-[#73A7DB]">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporan_list)): ?>
                <tr>
                    <td colspan="8" align="center">
                        Data laporan tidak ditemukan dengan filter yang dipilih.
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
                        <td><?php echo date("d-m-Y", strtotime($laporan['tanggal_pinjam'])); ?></td>
                        
                        <td><?php echo date("d-m-Y", strtotime($laporan['tenggat_waktu'])); ?></td>
                        
                        <td>
                            <?php 
                            if ($laporan['tanggal_kembali']) {
                                echo date("d-m-Y", strtotime($laporan['tanggal_kembali']));
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        
                        <td>
                            <?php 
                            if ($laporan['status'] == 'returned') {
                                echo '<span class="status-returned">Kembali</span>';
                            } elseif ($laporan['status'] == 'overdue') {
                                echo '<span class="status-overdue">Telat</span>';
                            } else {
                                echo '<span class="status-borrowed">Dipinjam</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $searchParam; ?><?php echo $timeframeParam; ?><?php echo $statusParam; ?>"
               class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                &laquo; Previous
            </a>
            
            <span class="current">
                Halaman <?php echo $page; ?> dari <?php echo $totalPages; ?>
            </span>

            <a href="?page=<?php echo $page + 1; ?><?php echo $searchParam; ?><?php echo $timeframeParam; ?><?php echo $statusParam; ?>"
               class="<?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                Next &raquo;
            </a>
        <?php endif; ?>
    </div>
    <br>
    
  
</body>
</html>