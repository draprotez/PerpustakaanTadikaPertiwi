<?php
// views/kelolaBukuViews.php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['member_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/bukuModels.php';

$search = $_GET['search'] ?? '';

$buku_list = getAllBuku($conn, $search); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Kelola Data Buku</title>
</head>
<body>

    <h1>Kelola Data Buku</h1>

    <button onclick="openForm('createForm')">Tambah Buku Baru</button>

    <hr>
    <form action="kelolaBukuViews.php" method="GET">
        <label for="search">Cari Buku (Judul, Kode, Penulis):</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
        <a href="kelolaBukuViews.php">Hapus Filter</a>
    </form>
    <hr>
    <?php if (isset($_GET['success'])) echo '<p><b>'.htmlspecialchars($_GET['success']).'</b></p>'; ?>
    <?php if (isset($_GET['error'])) echo '<p><b>'.htmlspecialchars($_GET['error']).'</b></p>'; ?>

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok (Tersedia/Total)</th>
                <th>Lokasi Rak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($buku_list)): ?>
                <tr>
                    <td colspan="9" align="center">
                        <?php if ($search): ?>
                            Data buku dengan kata kunci "<?php echo htmlspecialchars($search); ?>" tidak ditemukan.
                        <?php else: ?>
                            Belum ada data buku.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($buku_list as $buku): ?>
                    <tr>
                        <td><?php echo $buku['id']; ?></td>
                        <td><?php echo htmlspecialchars($buku['kode_buku']); ?></td>
                        <td><?php echo htmlspecialchars($buku['judul_buku']); ?></td>
                        <td><?php echo htmlspecialchars($buku['penulis']); ?></td>
                        <td><?php echo htmlspecialchars($buku['penerbit']); ?></td>
                        <td><?php echo htmlspecialchars($buku['tahun_terbit']); ?></td>
                        <td><?php echo $buku['salinan_tersedia'] . ' / ' . $buku['total_copy']; ?></td>
                        <td><?php echo htmlspecialchars($buku['lokasi_rak']); ?></td>
                        <td>
                            <button type="button" 
                                    onclick="openEditBukuForm(this)"
                                    data-id="<?php echo $buku['id']; ?>"
                                    data-kode="<?php echo htmlspecialchars($buku['kode_buku']); ?>"
                                    data-judul="<?php echo htmlspecialchars($buku['judul_buku']); ?>"
                                    data-penulis="<?php echo htmlspecialchars($buku['penulis']); ?>"
                                    data-isbn="<?php echo htmlspecialchars($buku['isbn']); ?>"
                                    data-penerbit="<?php echo htmlspecialchars($buku['penerbit']); ?>"
                                    data-tahun="<?php echo htmlspecialchars($buku['tahun_terbit']); ?>"
                                    data-total="<?php echo $buku['total_copy']; ?>"
                                    data-tersedia="<?php echo $buku['salinan_tersedia']; ?>"
                                    data-rak="<?php echo htmlspecialchars($buku['lokasi_rak']); ?>">
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
    <br>
    <button type="button" style="background-color: red; color: white;" onclick="window.location.href='../logout.php'">Logout</button>
    
    <div id="createForm" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
        <div style="background:white; margin: 10% auto; padding: 20px; width: 400px; max-height: 80%; overflow-y: auto;">
            <h2>Form Tambah Buku Baru</h2>
            <form action="../controller/bukuController.php" method="POST">
                <input type="hidden" name="action" value="create">
                <p><label>Kode Buku:</label><br><input type="text" name="kode_buku" required></p>
                <p><label>Judul Buku:</label><br><input type="text" name="judul_buku" required></p>
                <p><label>Penulis:</label><br><input type="text" name="penulis" required></p>
                <p><label>ISBN:</label><br><input type="text" name="isbn"></p>
                <p><label>Penerbit:</label><br><input type="text" name="penerbit"></p>
                <p><label>Tahun Terbit:</label><br><input type="number" name="tahun_terbit" placeholder="YYYY" min="1900" max="2099"></p>
                <p><label>Total Stok:</label><br><input type="number" step="1" min="1" name="total_copy" value="1" required></p>
                <p><label>Lokasi Rak:</label><br><input type="text" name="lokasi_rak"></p>
                <div>
                     <button type="submit">Simpan</button> 
                    <button type="button" onclick="closeForm('createForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="editForm" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
        <div style="background:white; margin: 10% auto; padding: 20px; width: 400px; max-height: 80%; overflow-y: auto;">
            <h2>Form Edit Buku</h2>
            <form action="../controller/bukuController.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="action" value="update">
                <p><label>Kode Buku:</label><br><input type="text" id="edit_kode_buku" name="kode_buku" required></p>
                <p><label>Judul Buku:</label><br><input type="text" id="edit_judul_buku" name="judul_buku" required></p>
                <p><label>Penulis:</label><br><input type="text" id="edit_penulis" name="penulis" required></p>
                <p><label>ISBN:</label><br><input type="text" id="edit_isbn" name="isbn"></p>
                <p><label>Penerbit:</label><br><input type="text" id="edit_penerbit" name="penerbit"></p>
                <p><label>Tahun Terbit:</label><br><input type="number" id="edit_tahun_terbit" name="tahun_terbit" placeholder="YYYY" min="1900" max="2099"></p>
                <p><label>Total Stok:</label><br><input type="number" step="1" min="1" id="edit_total_copy" name="total_copy" required></p>
                <p><label>Salinan Tersedia:</label><br><input type="number" step="1" min="0" id="edit_salinan_tersedia" name="salinan_tersedia" required></p>
                <p><label>Lokasi Rak:</label><br><input type="text" id="edit_lokasi_rak" name="lokasi_rak"></p>
                <div>
                     <button type="submit">Update</button> 
                    <button type="button" onclick="closeForm('editForm')">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="deleteConfirmModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
        <div style="background:white; margin: 20% auto; padding: 20px; width: 300px; text-align: center;">
            <p>Yakin ingin hapus buku ini?</p>
            <div>
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
            document.getElementById('edit_id').value = buttonElement.getAttribute('data-id');
            document.getElementById('edit_kode_buku').value = buttonElement.getAttribute('data-kode');
            document.getElementById('edit_judul_buku').value = buttonElement.getAttribute('data-judul');
            document.getElementById('edit_penulis').value = buttonElement.getAttribute('data-penulis');
            document.getElementById('edit_isbn').value = buttonElement.getAttribute('data-isbn');
            document.getElementById('edit_penerbit').value = buttonElement.getAttribute('data-penerbit');
            document.getElementById('edit_tahun_terbit').value = buttonElement.getAttribute('data-tahun');
            document.getElementById('edit_total_copy').value = buttonElement.getAttribute('data-total');
            document.getElementById('edit_salinan_tersedia').value = buttonElement.getAttribute('data-tersedia');
            document.getElementById('edit_lokasi_rak').value = buttonElement.getAttribute('data-rak');
           
            openForm('editForm');
        }

        function openDeleteConfirm(buttonElement) {
            //
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
    </script>

</body>
</html>