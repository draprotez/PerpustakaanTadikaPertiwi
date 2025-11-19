<?php
//peminjamanViews.php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/peminjamanModels.php';

$search = $_GET['search'] ?? ''; 
$limit = 10; 
$totalResults = countAllPeminjamanAktif($conn, $search);
$totalPages = ceil($totalResults / $limit);
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
} elseif ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}
$offset = ($page - 1) * $limit;
$peminjaman_list = getAllPeminjamanAktif($conn, $search, $limit, $offset);
$searchParam = $search ? '&search=' . htmlspecialchars($search) : '';

$member_list = getAllMembers($conn);
$buku_list_available = getAllAvailableBuku($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman Buku</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background-color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; width: 400px; max-height: 80vh; overflow-y: auto; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 3px; }
        .button-group { margin-top: 20px; text-align: right; }
        button { padding: 8px 15px; cursor: pointer; border: none; border-radius: 3px; margin-left: 5px; }
        button[type="submit"] { background-color: #4CAF50; color: white; }
        button.btn-return { background-color: #2196F3; color: white; }
        button[type="button"] { background-color: #f44336; color: white; }
        .btn-tambah { background-color: #008CBA; color: white; padding: 10px 15px; margin-bottom: 15px; }
        .btn-logout { background-color: red; color: white; padding: 8px 15px; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { display: inline-block; padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; color: #008CBA; }
        .pagination span.current { background-color: #008CBA; color: white; border-color: #008CBA; }
        .pagination a.disabled { color: #999; pointer-events: none; background-color: #f5f5f5; }
    </style>
</head>
<body>

    <h1>Kelola Peminjaman Buku</h1>

    <button class="btn-tambah" onclick="openForm('createForm')">Buat Peminjaman Baru</button>

    <hr>
    <form action="peminjamanViews.php" method="GET">
        <label for="search">Cari (Nama Peminjam, Judul Buku, Kode Member):</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
        <a href="peminjamanViews.php">Hapus Filter</a>
    </form>
    <hr>
    
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;"><b><?php echo htmlspecialchars($_GET['success']); ?></b></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><b><?php echo htmlspecialchars($_GET['error']); ?></b></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID Pinjam</th>
                <th>Judul Buku (Kode)</th>
                <th>Peminjam (Kode)</th>
                <th>Tgl Pinjam</th>
                <th>Tenggat Waktu</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($peminjaman_list)): ?>
                <tr>
                    <td colspan="7" align="center">
                        <?php if ($search): ?>
                            Data peminjaman "<?php echo htmlspecialchars($search); ?>" tidak ditemukan.
                        <?php else: ?>
                            Belum ada data peminjaman aktif.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($peminjaman_list as $pinjam): ?>
                    <tr style="<?php if($pinjam['status'] == 'overdue') echo 'background-color: #ffebee;'; ?>">
                        <td><?php echo $pinjam['peminjaman_id']; ?></td>
                        <td><?php echo htmlspecialchars($pinjam['judul_buku']); ?> (<?php echo htmlspecialchars($pinjam['kode_buku']); ?>)</td>
                        <td><?php echo htmlspecialchars($pinjam['nama_member']); ?> (<?php echo htmlspecialchars($pinjam['kode_member']); ?>)</td>
                        <td><?php echo date("d-m-Y", strtotime($pinjam['tanggal_pinjam'])); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($pinjam['tenggat_waktu'])); ?></td>
                        <td><?php echo htmlspecialchars($pinjam['status']); ?></td>
                        <td>
                            <button type="button" class="btn-return"
                                    onclick="openReturnConfirm(this)"
                                    data-url="../controller/peminjamanController.php?action=return&id=<?php echo $pinjam['peminjaman_id']; ?>&buku_id=<?php echo $pinjam['buku_id']; ?>"
                                    data-judul="<?php echo htmlspecialchars($pinjam['judul_buku']); ?>">
                                Kembalikan
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($totalPages > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $searchParam; ?>"
               class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                &laquo; Previous
            </a>
            <span class="current">
                Halaman <?php echo $page; ?> dari <?php echo $totalPages; ?>
            </span>
            <a href="?page=<?php echo $page + 1; ?><?php echo $searchParam; ?>"
               class="<?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                Next &raquo;
            </a>
        <?php endif; ?>
    </div>
    <br>
    <button class="btn" type="button" onclick="window.location.href='dashboardAdmin.php'">Kembali</button>
    <button class="btn-logout" onclick="window.location.href='../logout.php'">Logout</button>
    
    <div id="createForm" class="modal">
        <div class="modal-content">
            <h2>Form Peminjaman Baru</h2>
            <form action="../controller/peminjamanController.php" method="POST">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label>Pilih Member:</label>
                    <select name="member_id" required>
                        <option value="">-- Cari Member --</option>
                        <?php foreach ($member_list as $member): ?>
                            <option value="<?php echo $member['id']; ?>">
                                <?php echo htmlspecialchars($member['name']) . ' (' . htmlspecialchars($member['kode_member']) . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Pilih Buku (Hanya buku yang tersedia):</label>
                    <select name="buku_id" required>
                        <option value="">-- Cari Judul Buku / Kode Buku --</option>
                        <?php foreach ($buku_list_available as $buku): ?>
                            <option value="<?php echo $buku['id']; ?>">
                                <?php echo htmlspecialchars($buku['judul_buku']) . ' (' . htmlspecialchars($buku['kode_buku']) . ')'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tenggat Waktu:</label>
                    <input type="date" name="tenggat_waktu" value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
                </div>
                
                <div class="button-group">
                    <button type="submit">Pinjamkan</button> 
                    <button type="button" onclick="closeForm('createForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="returnConfirmModal" class="modal">
        <div class="modal-content">
            <p>Yakin ingin mengembalikan buku <b id="returnJudulBuku"></b>?</p>
            <div class="button-group">
                <button type="button" onclick="confirmReturn()" class="btn-return">Ya, Kembalikan</button>
                <button type="button" onclick="closeForm('returnConfirmModal')">Batal</button>
            </div>
            <input type="hidden" id="returnUrlInput">
        </div>
    </div>

    <script>
        function openForm(modalId) {
            document.getElementById(modalId).style.display = 'block'; 
        }

        function closeForm(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openReturnConfirm(buttonElement) {
            const returnUrl = buttonElement.getAttribute('data-url');
            const judul = buttonElement.getAttribute('data-judul');
            
            document.getElementById('returnUrlInput').value = returnUrl;
            document.getElementById('returnJudulBuku').textContent = judul;
            openForm('returnConfirmModal');
        }

        function confirmReturn() {
            const returnUrl = document.getElementById('returnUrlInput').value;
            if (returnUrl) {
                window.location.href = returnUrl;
            } else {
                alert('Error: URL Pengembalian tidak ditemukan!');
            }
        }

        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>