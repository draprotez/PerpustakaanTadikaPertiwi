<?php
// views/bukuViews.php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['member_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/bukuModels.php';

$search = $_GET['search'] ?? ''; 
$limit = 10; 
$totalResults = countAllBuku($conn, $search); 
$totalPages = ceil($totalResults / $limit); 
$page = (int)($_GET['page'] ?? 1); 
if ($page < 1) { 
    $page = 1; 
} elseif ($page > $totalPages && $totalPages > 0) { 
    $page = $totalPages; 
}
$offset = ($page - 1) * $limit; 
$buku_list = getAllBuku($conn, $search, $limit, $offset); 
$searchParam = $search ? '&search=' . htmlspecialchars($search) : ''; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Buku</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; } 
        table { width: 100%; border-collapse: collapse; margin-top: 10px; } 
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } 
        th { background-color: #f2f2f2; } 
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; } 
        .modal-content { background-color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; width: 400px; max-height: 80vh; overflow-y: auto; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); } 
        .form-group { margin-bottom: 15px; } 
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; } 
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 3px; } 
        .button-group { margin-top: 20px; text-align: right; } 
        button { padding: 8px 15px; cursor: pointer; border: none; border-radius: 3px; margin-left: 5px; } 
        button[type="submit"] { background-color: #4CAF50; color: white; } 
        button[type="button"] { background-color: #f44336; color: white; } 
        .btn-tambah { background-color: #008CBA; color: white; padding: 10px 15px; margin-bottom: 15px; } 
        .btn-logout { background-color: red; color: white; padding: 8px 15px; } 
        .pagination { margin-top: 20px; text-align: center; } 
        .pagination a, .pagination span { display: inline-block; padding: 8px 12px; margin: 0 2px; border: 1px solid #ddd; text-decoration: none; color: #008CBA; } 
        .pagination span.current { background-color: #008CBA; color: white; border-color: #008CBA; } 
        .pagination a.disabled { color: #999; pointer-events: none; background-color: #f5f5f5; } 
        .cover-image {
            width: 60px;
            height: 80px;
            object-fit: cover;
        }
        .current-cover-preview {
            max-width: 100px;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>Kelola Data Buku</h1>

    <button class="btn-tambah" onclick="openForm('createForm')">Tambah Buku Baru</button>

    <hr>
    <form action="bukuViews.php" method="GET">
        <label for="search">Cari (Judul, Kode, Penulis, Penerbit):</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
        <a href="bukuViews.php">Hapus Filter</a>
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
                <th>No</th>
                <th>Cover</th>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Total</th>
                <th>Tersedia</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($buku_list)): ?>
                <tr>
                    <td colspan="10" align="center">
                        <?php if ($search): ?>
                            Data buku "<?php echo htmlspecialchars($search); ?>" tidak ditemukan.
                        <?php else: ?>
                            Belum ada data buku.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = $offset + 1; ?>
                <?php foreach ($buku_list as $buku): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <?php if (!empty($buku['gambar'])): ?>
                                <img src="../assets/images/buku/<?php echo htmlspecialchars($buku['gambar']); ?>" 
                                     alt="Cover" class="cover-image">
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($buku['kode_buku']); ?></td>
                        <td><?php echo htmlspecialchars($buku['judul_buku']); ?></td>
                        <td><?php echo htmlspecialchars($buku['penulis']); ?></td>
                        <td><?php echo htmlspecialchars($buku['penerbit']); ?></td>
                        <td><?php echo htmlspecialchars($buku['tahun_terbit']); ?></td>
                        <td><?php echo htmlspecialchars($buku['total_copy']); ?></td>
                        <td><?php echo htmlspecialchars($buku['salinan_tersedia']); ?></td>
                        <td>
                            <button type="button" 
                                    onclick="openEditBukuForm(this)"
                                    data-id="<?php echo $buku['id']; ?>"
                                    data-kode="<?php echo htmlspecialchars($buku['kode_buku']); ?>"
                                    data-judul="<?php echo htmlspecialchars($buku['judul_buku']); ?>"
                                    data-penulis="<?php echo htmlspecialchars($buku['penulis']); ?>"
                                    data-isbn="<?php echo htmlspecialchars($buku['isbn'] ?? ''); ?>"
                                    data-penerbit="<?php echo htmlspecialchars($buku['penerbit'] ?? ''); ?>"
                                    data-tahun="<?php echo htmlspecialchars($buku['tahun_terbit'] ?? ''); ?>"
                                    data-total="<?php echo htmlspecialchars($buku['total_copy']); ?>"
                                    data-tersedia="<?php echo htmlspecialchars($buku['salinan_tersedia']); ?>"
                                    data-gambar="<?php echo htmlspecialchars($buku['gambar'] ?? ''); ?>">
                                Edit
                            </button>
                            <button type="button" 
                                    onclick="openDeleteConfirm(this)"
                                    data-url="../controller/bukuController.php?action=delete&id=<?php echo $buku['id']; ?>">
                                Delete
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
    
    <button class="btn-logout" onclick="window.location.href='../logout.php'">Logout</button>
    
    <div id="createForm" class="modal">
        <div class="modal-content">
            <h2>Form Tambah Buku Baru</h2>
            <form action="../controller/bukuController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                
                <div class="form-group">
                    <label>Kode Buku:</label>
                    <input type="text" name="kode_buku" required>
                </div>
                
                <div class="form-group">
                    <label>Judul Buku:</label>
                    <input type="text" name="judul_buku" required>
                </div>
                
                <div class="form-group">
                    <label>Penulis:</label>
                    <input type="text" name="penulis" required>
                </div>
                
                <div class="form-group">
                    <label>ISBN:</label>
                    <input type="text" name="isbn">
                </div>
                
                <div class="form-group">
                    <label>Penerbit:</label>
                    <input type="text" name="penerbit">
                </div>
                
                <div class="form-group">
                    <label>Tahun Terbit:</label>
                    <input type="number" name="tahun_terbit" min="1900" max="2099">
                </div>
                
                <div class="form-group">
                    <label>Total Copy:</label>
                    <input type="number" name="total_copy" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Cover Buku:</label>
                    <input type="file" name="gambar" accept="image/*">
                </div>
                
                <div class="button-group">
                    <button type="submit">Simpan</button> 
                    <button type="button" onclick="closeForm('createForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="editForm" class="modal">
        <div class="modal-content">
            <h2>Form Edit Buku</h2>
            <form action="../controller/bukuController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="gambar_lama" id="edit_gambar_lama">
                
                <div class="form-group">
                    <label>Kode Buku:</label>
                    <input type="text" id="edit_kode_buku" name="kode_buku" required>
                </div>
                
                <div class="form-group">
                    <label>Judul Buku:</label>
                    <input type="text" id="edit_judul_buku" name="judul_buku" required>
                </div>
                
                <div class="form-group">
                    <label>Penulis:</label>
                    <input type="text" id="edit_penulis" name="penulis" required>
                </div>
                
                <div class="form-group">
                    <label>ISBN:</label>
                    <input type="text" id="edit_isbn" name="isbn">
                </div>
                
                <div class="form-group">
                    <label>Penerbit:</label>
                    <input type="text" id="edit_penerbit" name="penerbit">
                </div>
                
                <div class="form-group">
                    <label>Tahun Terbit:</label>
                    <input type="number" id="edit_tahun_terbit" name="tahun_terbit" min="1900" max="2099">
                </div>
                
                <div class="form-group">
                    <label>Total Copy:</label>
                    <input type="number" id="edit_total_copy" name="total_copy" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Salinan Tersedia:</label>
                    <input type="number" id="edit_salinan_tersedia" name="salinan_tersedia" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Cover Buku:</label>
                    <div id="current-cover-container"></div>
                    <input type="file" name="gambar" accept="image/*">
                </div>
                
                <div class="button-group">
                    <button type="submit">Update</button> 
                    <button type="button" onclick="closeForm('editForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content">
            <p>Yakin ingin hapus buku ini?</p>
            <div class="button-group">
                <button type="button" onclick="confirmDelete()">Ya, Hapus</button>
                <button type="button" onclick="closeForm('deleteConfirmModal')">Batal</button>
            </div>
            <input type="hidden" id="deleteUrlInput">
        </div>
    </div>

    <script>
        function openForm(modalId) {
            document.getElementById(modalId).style.display = 'block'; 
        }

        function closeForm(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openEditBukuForm(buttonElement) {
            // Ambil data dari atribut data-*
            document.getElementById('edit_id').value = buttonElement.getAttribute('data-id');
            document.getElementById('edit_kode_buku').value = buttonElement.getAttribute('data-kode');
            document.getElementById('edit_judul_buku').value = buttonElement.getAttribute('data-judul');
            document.getElementById('edit_penulis').value = buttonElement.getAttribute('data-penulis');
            document.getElementById('edit_isbn').value = buttonElement.getAttribute('data-isbn');
            document.getElementById('edit_penerbit').value = buttonElement.getAttribute('data-penerbit');
            document.getElementById('edit_tahun_terbit').value = buttonElement.getAttribute('data-tahun');
            document.getElementById('edit_total_copy').value = buttonElement.getAttribute('data-total');
            document.getElementById('edit_salinan_tersedia').value = buttonElement.getAttribute('data-tersedia');
            document.getElementById('edit_gambar_lama').value = buttonElement.getAttribute('data-gambar');

            const coverContainer = document.getElementById('current-cover-container');
            const gambar = buttonElement.getAttribute('data-gambar');
            if (gambar) {
                coverContainer.innerHTML = `<img src="../assets/images/buku/${gambar}" alt="Current Cover" class="current-cover-preview">`;
            } else {
                coverContainer.innerHTML = '<p>Tidak ada cover</p>';
            }

            openForm('editForm');
        }

        function openDeleteConfirm(buttonElement) {
            const deleteUrl = buttonElement.getAttribute('data-url');
            document.getElementById('deleteUrlInput').value = deleteUrl;
            openForm('deleteConfirmModal');
        }

        function confirmDelete() {
            const deleteUrl = document.getElementById('deleteUrlInput').value;
            if (deleteUrl) {
                window.location.href = deleteUrl;
            } else {
                alert('Error: URL Hapus tidak ditemukan!');
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