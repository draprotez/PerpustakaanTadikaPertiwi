<?php
//peminjamanViews.php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/peminjamanModels.php';
include '../header.php';

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
       
        
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { display: inline-block; padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; color: #008CBA; }
        .pagination span.current { background-color: #008CBA; color: white; border-color: #008CBA; }
        .pagination a.disabled { color: #999; pointer-events: none; background-color: #f5f5f5; }
    </style>
</head>
<body class="ml-[320px]">

    <?php include 'partials/sidebar.php'; ?>

    <p class="font-semibold text-xl py-5">Kelola Peminjaman Buku</p>

   

   
    <form action="peminjamanViews.php" method="GET">
        <label for="search">Cari (Nama Peminjam, Judul Buku, Kode Member):</label> <br>
      <div class="relative inline-block py-3" style="vertical-align: middle;">
    <input 
        type="text" 
        id="search" 
        name="search" 
        value="<?php echo htmlspecialchars($search); ?>" 
        placeholder="Cari peminjaman"
        class="rounded-full pr-10"
        style="padding:5px; padding-right:34px; border:1px solid #ccc;"
    >

    <img 
        src="../assets/images/icon/mingcute_search-line (1).png" 
        alt="" 
        aria-hidden="true" 
        class="absolute right-2 top-1/2" 
        style="transform: translateY(-50%); width:16px; height:16px; pointer-events: none; opacity:0.8;"
    />
</div>

        <a href="peminjamanViews.php"class="px-3 bg-red-500 py-3 rounded-3xl text-white font-semibold">Hapus Filter</a>
        <button type="button"
    class="btn-tambah font-semibold inline-flex items-center py-3 px-3 rounded-full bg-[#05AC48] text-white"
    onclick="openForm('createForm')">

    <p class="leading-none ">Buat Peminjaman Baru</p>

    <img src="../assets/images/icon/mdi_add-bold.png"
         alt="Tambah"
         class="w-4 h-4 ml-2">
</button>
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
                            <button type="button" 
                                    onclick="openReturnConfirm(this)"
                                    class="bg-yellow-500 rounded-full py-2 px-2 font-semibold text-black"
                                    data-url="../controller/peminjamanController.php?action=return&id=<?php echo $pinjam['peminjaman_id']; ?>&buku_id=<?php echo $pinjam['buku_id']; ?>"
                                    data-judul="<?php echo htmlspecialchars($pinjam['judul_buku']); ?>">
                                Edit! 
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
                    <button type="submit " class="bg-green-500  rounded-full py-1 px-2 font-semibold text-white">Pinjamkan</button> 
                    <button type="button" 
                    class="px-3 bg-red-500 py-2 rounded-3xl text-white font-semibold"
                    onclick="closeForm('createForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="returnConfirmModal" class="modal">
        <div class="modal-content">
            <p>Yakin ingin mengembalikan buku <b id="returnJudulBuku"></b>?</p>
            <div class="button-group">
                <button type="button" onclick="confirmReturn()" class="bg-green-500  rounded-full py-1 px-2 font-semibold text-white">Ya, Kembalikan</button>
                <button type="button" class="px-3 bg-red-500 py-2 rounded-3xl text-white font-semibold" onclick="closeForm('returnConfirmModal')">Batal</button>
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