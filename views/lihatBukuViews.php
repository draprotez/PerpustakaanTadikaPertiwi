<?php
//lihatBukuViews.php
session_start();
require_once '../config/database.php';
include '../header.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

$nama_user = $_SESSION['member_name'];

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM buku";
if ($search) {
    $sql .= " WHERE judul_buku LIKE '%$search%' OR penulis LIKE '%$search%' OR penerbit LIKE '%$search%'";
}
$sql .= " ORDER BY judul_buku ASC";

$result = mysqli_query($conn, $sql);
$books = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Koleksi Buku</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        h1 { color: #333; margin: 0; }
      .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; }
        .book-card { background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; display: flex; flex-direction: column; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.2); }
        .card-img { width: 100%; height: 280px; background-color: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; color: #aaa; font-size: 3rem; }
        .card-img img { width: 100%; height: 100%; object-fit: cover; }
        .card-body { padding: 15px; flex: 1; display: flex; flex-direction: column; }
        .card-title { font-size: 1.1rem; font-weight: bold; color: #333; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .card-author { font-size: 0.9rem; color: #666; margin-bottom: 10px; }
        .card-badge { margin-top: auto; align-self: flex-start; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 999; justify-content: center; align-items: center; }
        .modal-content { background: white; width: 90%; max-width: 500px; border-radius: 10px; padding: 25px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.3); animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .close-btn { position: absolute; top: 15px; right: 20px; font-size: 24px; cursor: pointer; color: #666; }
        .modal-body { text-align: center; }
        .modal-cover { width: 120px; height: 180px; object-fit: cover; border-radius: 5px; margin-bottom: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .modal-title { font-size: 1.4rem; font-weight: bold; margin-bottom: 5px; color: #333; }
        .modal-info { color: #555; margin-bottom: 20px; font-size: 0.95rem; line-height: 1.6; }
        .modal-stock { font-size: 1.1rem; font-weight: bold; margin-bottom: 25px; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .btn-group { display: flex; gap: 10px; justify-content: center; }
        .btn-action { padding: 12px 25px; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; font-weight: bold; width: 100%; }
        .btn-borrow { background: #28a745; color: white; }
        .btn-borrow:hover { background: #218838; }
        .btn-disabled { background: #ccc; color: #666; cursor: not-allowed; }
        .btn-cancel { background: #f0f0f0; color: #333; width: auto; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; text-align: center; font-weight: 500; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            
            <div class="bg-white w-full justify-between flex items-center px-6 py-4 rounded-lg shadow-md">
                <p class="text-xl font-semibold">Koleksi Buku</p>
                <p class="text-lg font-medium">Halo <b><?php echo htmlspecialchars($nama_user); ?>👋</b>! Pilih buku yang ingin Anda baca.</p>
                  <div>
                      <a href="../index.php" class="bg-[#1C77D2] rounded-lg text-white py-2 px-2 font-semibold hover:text-black hover:bg-yellow-400">Kembali ke Beranda</a>
      
                  </div>
            </div>
          </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="" method="GET" class="search-box flex">
            <input class="w-[400px] h-10 rounded-full px-2" type="text" name="search" placeholder="Cari judul buku, penulis, atau penerbit..." value="<?php echo htmlspecialchars($search); ?>">
           <button type="submit" class="flex items-center mx-2 rounded-full bg-blue-400 py-2 px-3 font-semibold text-white">
    Cari 
    <img class="w-5 h-5 ml-1" src="../assets/images/icon/mingcute_search-line.png" alt="">
</button>
 <?php if($search): ?>
                <a href="lihatBukuViews.php" class="bg-red-500 rounded-full text-white py-2 px-2 font-semibold">Reset</a>
            <?php endif; ?>
        </form>

        <div class="book-grid">
            <?php if (empty($books)): ?>
                <p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #777;">Tidak ada buku yang ditemukan.</p>
            <?php else: ?>
                <?php foreach ($books as $book): ?>
                    <div class="book-card" onclick="openModal(<?php echo htmlspecialchars(json_encode($book)); ?>)">
                        <div class="card-img">
                            <?php if ($book['gambar'] && file_exists('../assets/images/buku/' . $book['gambar'])): ?>
                                <img src="../assets/images/buku/<?php echo htmlspecialchars($book['gambar']); ?>" alt="Cover">
                            <?php else: ?>
                                📚
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="card-title"><?php echo htmlspecialchars($book['judul_buku']); ?></div>
                            <div class="card-author"><?php echo htmlspecialchars($book['penulis']); ?></div>
                            
                            <?php if ($book['salinan_tersedia'] > 0): ?>
                                <span class="card-badge badge-success">Tersedia: <?php echo $book['salinan_tersedia']; ?></span>
                            <?php else: ?>
                                <span class="card-badge badge-danger">Stok Habis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div id="bookModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            
            <div class="modal-body">
                <img id="modalImg" src="" alt="Cover" class="modal-cover" style="display:none;">
                <div id="modalNoImg" style="font-size:40px; margin-bottom:10px;">📚</div>

                <div class="modal-title" id="modalTitle">Judul Buku</div>
                
                <div class="modal-info">
                    Penulis: <b id="modalAuthor">-</b><br>
                    Penerbit: <span id="modalPublisher">-</span> (<span id="modalYear">-</span>)<br>
                    Kode: <span id="modalCode">-</span>
                </div>

                <div class="modal-stock">
                    Stok Tersedia: <span id="modalStock">0</span>
                </div>

                <form action="../controller/requestController.php" method="POST">
                    <input type="hidden" name="buku_id" id="modalBookId">
                    
                    <div class="btn-group">
                        <button type="button" class="btn-action btn-cancel" onclick="closeModal()">Batal</button>
                        <button type="submit" id="btnPinjam" class="btn-action btn-borrow">Pinjam Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  <footer class="bg-[#1F7BD8] text-white py-8 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">

        <!-- LEFT (Logo + Menu) -->
        <div class="flex flex-col items-start">
            <img src="../assets/images/logo/logo-smk.png" class="w-16 mb-3" alt="logo">
            <ul class="space-y-1">
               <a href="../index.php"> <li>Beranda</li></a>
                <a href="#katalog buku"><li>Katalog Buku</li></a>
                <a href="#about"><li>Tentang Kami</li>
            </ul></a>
        </div>

        <!-- MIDDLE (Shortcut Link) -->
        <div class="flex flex-col items-start mt-12 md:items-center">
            <h3 class="font-semibold mb-2">Shortcut Link :</h3>
            <ul class="space-y-1">
                <a href="https://www.smktadikapertiwi.sch.id/"><li>Website Profile Sekolah</li></a>
                <li>Website Perpustakaan</li>
            </ul>
        </div>

        <!-- RIGHT (Kontak) -->
        <div class="flex flex-col items-start mt-12 md:items-end">
            <h3 class="font-semibold mb-2">Kontak :</h3>
            <ul class="space-y-1">
                <li>0895383578689</li>
                <li>tadika.pertiwi@gmail.com</li>
                <li>info@smktadikapertiwi</li>
            </ul>
        </div>
    </div>

    <!-- COPYRIGHT -->
    <div class="text-center text-xs mt-6">
        © 2025 Perpustakaan SMK Tadika Pertiwi. All Rights Reserved.
    </div>
</footer>
    <script>
        const modal = document.getElementById('bookModal');
        const btnPinjam = document.getElementById('btnPinjam');

        function openModal(book) {
            document.getElementById('modalTitle').textContent = book.judul_buku;
            document.getElementById('modalAuthor').textContent = book.penulis;
            document.getElementById('modalPublisher').textContent = book.penerbit;
            document.getElementById('modalYear').textContent = book.tahun_terbit;
            document.getElementById('modalCode').textContent = book.kode_buku;
            document.getElementById('modalStock').textContent = book.salinan_tersedia;
            document.getElementById('modalBookId').value = book.id;

            const imgElem = document.getElementById('modalImg');
            const noImgElem = document.getElementById('modalNoImg');
            
            if (book.gambar) {
                imgElem.src = '../assets/images/buku/' + book.gambar;
                imgElem.style.display = 'inline-block';
                noImgElem.style.display = 'none';
            } else {
                imgElem.style.display = 'none';
                noImgElem.style.display = 'block';
            }

            if (book.salinan_tersedia > 0) {
                btnPinjam.disabled = false;
                btnPinjam.classList.remove('btn-disabled');
                btnPinjam.classList.add('btn-borrow');
                btnPinjam.textContent = "Pinjam Sekarang";
            } else {
                btnPinjam.disabled = true;
                btnPinjam.classList.remove('btn-borrow');
                btnPinjam.classList.add('btn-disabled');
                btnPinjam.textContent = "Stok Habis";
            }

            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>