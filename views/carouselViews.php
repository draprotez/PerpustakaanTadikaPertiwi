<?php
//carouselViews.php
session_start();
include '../config/database.php';
include '../models/carouselModels.php'; // Pastikan ini memuat file yang benar
include '../header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$nama_user = $_SESSION['user_name'];
$role_user = $_SESSION['user_role'];

$homepage_books = getCarouselBooks($conn); 

$all_books = getAllBooksOption($conn);     

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
       
        .breadcrumb { color: #666; font-size: 14px; margin-bottom: 20px; }
        .breadcrumb a { color: #008CBA; text-decoration: none; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .card-title { font-size: 1.3em; color: #333; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #008CBA; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #333; font-weight: bold; }
        select, input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
       table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:hover { background-color: #f9f9f9; }
        .book-img { width: 50px; height: 70px; object-fit: cover; border-radius: 4px; background: #008CBA; color: white; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
      .actions { display: flex; gap: 5px; }
        .order-input { width: 60px; padding: 5px; text-align: center; }
        .empty-state { text-align: center; padding: 40px; color: #999; }
    </style>
</head>
<body class="ml-[320px] bg-[#EDF0F7]">

    <?php include 'partials/sidebar.php'; ?>
    <div class="container">
        <p class="font-semibold text-xl py-5">Kelola Buku Dashboard</p>
        <div class="breadcrumb">
            <a href="dashboardAdmin.php">Dashboard</a> / Kelola Buku Dashboard
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
            <h2 class="card-title">Tambah Buku ke Dashboard</h2>
            <form action="../controller/carouselController.php" method="POST">
                <input type="hidden" name="action" value="add_book">
                <div class="form-group">
                    <label for="buku_id">Pilih Buku:</label>
                    <select class="h-10" name="buku_id" id="buku_id" required>
                        <option value="">-- Pilih Buku --</option>
                        
                        <?php if (!empty($all_books)): ?>
                            <?php foreach ($all_books as $book): ?>
                                <option value="<?php echo $book['id']; ?>">
                                    <?php echo htmlspecialchars($book['judul_buku'] . ' - ' . $book['penulis'] . ' (' . $book['kode_buku'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Semua buku bergambar sudah masuk carousel.</option>
                        <?php endif; ?>
                        
                    </select>
                </div>
                        <button 
                type="submit"
                class="btn-primary font-semibold inline-flex items-center py-3 px-3 rounded-full bg-[#05AC48] text-white">

                <p class="leading-none pt-1">Tambah ke Dashboard</p>

                <img 
                    src="../assets/images/icon/mdi_add-bold.png"
                    alt="Tambah"
                    class="w-4 h-4 ml-2 ">
            </button>
     </form>
        </div>

        <div class="card">
            <h2 class="card-title">Daftar Buku di Dashboard</h2>
            
            <?php if (!empty($homepage_books)): ?>
                <form action="../controller/carouselController.php" method="POST">
                    <input type="hidden" name="action" value="update_order">
                    <table>
                        <thead>
                            <tr>
                                <th class="bg-[#73A7DB]">Urutan</th>
                                <th class="bg-[#73A7DB]">Cover</th>
                                <th class="bg-[#73A7DB] ">Judul Buku</th>
                                <th class="bg-[#73A7DB]">Penulis</th>
                                <th class="bg-[#73A7DB] w-[150px]">Kode Buku</th>
                                <th class="bg-[#73A7DB] w-[180px]">Status</th>
                                <th class="bg-[#73A7DB]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($homepage_books as $book): ?>
                                <tr>
                                    <td>
                                        <input type="number" name="urutan[<?php echo $book['id']; ?>]" 
                                               value="<?php echo $book['urutan']; ?>" 
                                               class="order-input" min="1">
                                    </td>
                                    <td>
                                        <div class="book-img">
                                            <?php if ($book['gambar'] && file_exists('../assets/images/buku/' . $book['gambar'])): ?>
                                                <img src="../assets/images/buku/<?php echo htmlspecialchars($book['gambar']); ?>" 
                                                     style="width:100%; height:100%; object-fit:cover;">
                                            <?php else: ?>
                                                📚
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($book['judul_buku']); ?></td>
                                    <td><?php echo htmlspecialchars($book['penulis']); ?></td>
                                    <td><?php echo htmlspecialchars($book['kode_buku']); ?></td>
                                    <td>
                                        <span class="status-badge font-semibold text-lg text-white py-1 px-3 bg-blue-500 <?php echo $book['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $book['is_active'] ? 'Aktif ✅' : 'Nonaktif ❎'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions" style="flex-direction:column; align-items:flex-start; gap:8px;">
                                            <a href="../controller/carouselController.php?action=toggle&id=<?php echo $book['id']; ?>"
                                               class="inline-flex items-center gap-2 py-1 px-3 rounded-full bg-yellow-400 text-black font-semibold">
                                                <?php echo $book['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>
                                            </a>

                                            <a href="../controller/carouselController.php?action=remove&id=<?php echo $book['id']; ?>"
                                               class="inline-flex items-center gap-2 py-1 pr-5 pl-2 rounded-full bg-red-500 font-semibold w-[115px]"
                                               onclick="return confirm('Yakin ingin menghapus buku ini dari dashboard?')">
                                                <img src="../assets/images/icon/mi_delete.png" alt="Hapus" style="width:16px;height:16px;" />
                                                <span>Delete</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn-success bg-green-600 text-white font-semibold py-2 px-2 rounded-full shadow-xl" style="margin-top: 15px;">Simpan Urutan</button>
                </form>
            <?php else: ?>
                <div class="empty-state">
                    <p>Belum ada buku yang ditambahkan ke carousel.</p>
                    <p>Silakan tambah buku menggunakan form di atas.</p>
                </div>
            <?php endif; ?>
        </div>

        </div>
</body>
</html>