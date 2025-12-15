<?php
// views/lihatBukuViews.php
session_start();
require_once '../config/database.php';
include '../header.php';

// 1. Cek Login
if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

$nama_user = $_SESSION['member_name'];

// 2. Ambil Parameter Filter
$search = $_GET['search'] ?? '';
$kelas_filter = $_GET['kelas'] ?? ''; 

// 3. Bangun Query SQL
$sql = "SELECT * FROM buku WHERE 1=1"; 

// Filter Search
if ($search) {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (judul_buku LIKE '%$safe_search%' OR penulis LIKE '%$safe_search%' OR penerbit LIKE '%$safe_search%' OR kode_buku LIKE '%$safe_search%')";
}

// Filter Kelas (Dropdown)
if ($kelas_filter) {
    $safe_kelas = mysqli_real_escape_string($conn, $kelas_filter);
    $sql .= " AND kelas = '$safe_kelas'";
}

$sql .= " ORDER BY judul_buku ASC";

// Eksekusi Query
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
    <title>Koleksi Buku - Perpustakaan Tadika Pertiwi</title>
    <link rel="website icon" type="png" href="../assets/images/logo/logo-smk.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding-bottom: 100px; }
        .container-custom { max-width: 1200px; margin: 20px auto; padding: 0 15px; }
        
        /* Grid Buku */
        .book-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; margin-top: 20px; }
        .book-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; display: flex; flex-direction: column; position: relative; border: 1px solid #eee; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-color: #bce0fd; }
        
        .card-img { width: 100%; height: 280px; background-color: #f9f9f9; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
        .card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
        .book-card:hover .card-img img { transform: scale(1.05); }
        .no-img { font-size: 3rem; color: #ccc; }

        .card-body { padding: 15px; flex: 1; display: flex; flex-direction: column; }
        .card-title { font-size: 1rem; font-weight: 700; color: #333; margin-bottom: 5px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 44px; }
        .card-author { font-size: 0.85rem; color: #666; margin-bottom: 10px; }
        
        /* Badges */
        .card-badge { margin-top: auto; align-self: flex-start; font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; }
        .badge-success { background: #e6fffa; color: #047857; border: 1px solid #a7f3d0; }
        .badge-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-kelas { position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.7); color: white; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: bold; z-index: 5; }

        /* Checkbox & Floating Bar */
        .select-book-checkbox { position: absolute; top: 10px; right: 10px; transform: scale(1.3); z-index: 10; cursor: pointer; accent-color: #1C77D2; width: 20px; height: 20px; }
        .floating-bar { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%) translateY(100px); background: #2d3748; color: white; padding: 15px 30px; border-radius: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 20px; z-index: 1000; transition: transform 0.3s ease; width: 90%; max-width: 500px; justify-content: space-between; opacity: 0; pointer-events: none; }
        .floating-bar.active { transform: translateX(-50%) translateY(0); opacity: 1; pointer-events: all; }
        .btn-submit-bulk { background: #48bb78; color: white; border: none; padding: 8px 20px; border-radius: 20px; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-submit-bulk:hover { background: #38a169; }

        /* Modal Styles */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; backdrop-filter: blur(3px); justify-content: center; align-items: center; }
        .modal-content { background: white; width: 90%; max-width: 450px; border-radius: 16px; overflow: hidden; animation: zoomIn 0.2s ease; }
        @keyframes zoomIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header { padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
        .modal-body { padding: 20px; text-align: center; }
        .modal-cover { width: 100px; height: 150px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: inline-block; }
        .modal-details { text-align: left; margin-bottom: 20px; background: #f1f5f9; padding: 15px; border-radius: 10px; font-size: 0.9rem; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .detail-value { font-weight: 600; color: #334155; }
        .qty-control { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 20px; }
        .qty-control input { width: 70px; text-align: center; font-size: 1.2rem; padding: 5px; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: bold; }
        .modal-footer { padding: 15px 20px; background: white; border-top: 1px solid #eee; display: flex; gap: 10px; }
        .btn-full { width: 100%; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; border: none; }
        .btn-cancel { background: #e2e8f0; color: #475569; width: 30%; }
        .btn-confirm { background: #1C77D2; color: white; width: 70%; }
        
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 500; font-size: 0.95rem; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>

    <div class="container-custom">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Koleksi Buku</h1>
                <p class="text-gray-600">Halo, <span class="font-bold text-blue-600"><?php echo htmlspecialchars($nama_user); ?></span>!</p>
            </div>
            <a href="../index.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold transition text-sm">
                &laquo; Dashboard
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="" method="GET" class="mb-6 flex flex-col md:flex-row gap-3 max-w-4xl mx-auto">
            
            <div class="relative w-full md:w-1/4">
                <select name="kelas" onchange="this.form.submit()" 
                        class="w-full appearance-none px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm cursor-pointer text-gray-700 font-medium">
                    <option value="" <?php echo ($kelas_filter == '') ? 'selected' : ''; ?>>Semua Kategori</option>
                    <option value="X" <?php echo ($kelas_filter == 'X') ? 'selected' : ''; ?>>Kelas X</option>
                    <option value="XI" <?php echo ($kelas_filter == 'XI') ? 'selected' : ''; ?>>Kelas XI</option>
                    <option value="XII" <?php echo ($kelas_filter == 'XII') ? 'selected' : ''; ?>>Kelas XII</option>
                    <option value="UMUM" <?php echo ($kelas_filter == 'UMUM') ? 'selected' : ''; ?>>Umum</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="relative w-full md:w-3/4 flex">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Cari judul, penulis, kode..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm border-r-0">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-r-xl font-semibold shadow-sm transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari
                </button>
            </div>

            <?php if($search || $kelas_filter): ?>
                <a href="lihatBukuViews.php" class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-xl font-semibold shadow-sm transition flex items-center justify-center">
                    Reset
                </a>
            <?php endif; ?>
        </form>

        <form id="bulkLoanForm" action="../controller/requestController.php" method="POST">
            <input type="hidden" name="action" value="bulk_loan">
            
            <div class="book-grid">
                <?php if (empty($books)): ?>
                    <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-xl shadow-sm">
                        <p class="text-4xl mb-3">📚</p>
                        <p class="text-lg font-medium">Tidak ada buku yang ditemukan.</p>
                        <?php if($kelas_filter || $search): ?>
                            <p class="text-sm text-gray-400">Coba ubah kata kunci atau kategori.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($books as $book): ?>
                        <div class="book-card" onclick="openModal(<?php echo htmlspecialchars(json_encode($book)); ?>)">
                            
                            <div class="badge-kelas"><?php echo htmlspecialchars($book['kelas'] ?? 'Umum'); ?></div>

                            <?php if ($book['salinan_tersedia'] > 0): ?>
                                <input type="checkbox" name="buku_ids[]" value="<?php echo $book['id']; ?>" class="select-book-checkbox" onclick="event.stopPropagation(); updateFloatingBar()">
                            <?php endif; ?>

                            <div class="card-img">
                                <?php if ($book['gambar'] && file_exists('../assets/images/buku/' . $book['gambar'])): ?>
                                    <img src="../assets/images/buku/<?php echo htmlspecialchars($book['gambar']); ?>" alt="Cover">
                                <?php else: ?>
                                    <span class="no-img">📚</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-body">
                                <div class="card-title" title="<?php echo htmlspecialchars($book['judul_buku']); ?>">
                                    <?php echo htmlspecialchars($book['judul_buku']); ?>
                                </div>
                                <div class="card-author">
                                    by <?php echo htmlspecialchars($book['penulis']); ?>
                                </div>
                                
                                <?php if ($book['salinan_tersedia'] > 0): ?>
                                    <div class="card-badge badge-success">
                                        Stok: <?php echo $book['salinan_tersedia']; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="card-badge badge-danger">
                                        Stok Habis
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="floatingBar" class="floating-bar">
                <span class="text-sm font-medium"><span id="selectedCount" class="font-bold text-yellow-400 text-lg">0</span> Buku Dipilih</span>
                <button type="submit" class="btn-submit-bulk">Pinjam Terpilih</button>
            </div>
        </form>
    </div>

    <div id="bookModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="font-bold text-gray-800">Detail Peminjaman</h3>
                <span class="text-2xl cursor-pointer text-gray-400 hover:text-gray-600" onclick="closeModal()">&times;</span>
            </div>
            
            <div class="modal-body">
                <img id="modalImg" src="" alt="Cover" class="modal-cover" style="display:none;">
                <div id="modalNoImg" style="font-size:50px; margin-bottom:10px;">📚</div>

                <h4 id="modalTitle" class="text-lg font-bold text-gray-800 mb-4 px-4 leading-tight">-</h4>

                <div class="modal-details">
                    <div class="detail-row">
                        <span class="text-gray-500">Kategori/Kelas</span>
                        <span class="detail-value" id="modalClass">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-gray-500">Penulis</span>
                        <span class="detail-value" id="modalAuthor">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-gray-500">Stok Tersedia</span>
                        <span class="detail-value text-green-600" id="modalStockDisplay">0</span>
                    </div>
                </div>

                <form action="../controller/requestController.php" method="POST">
                    <input type="hidden" name="buku_id" id="modalBookId">
                    
                    <div id="stockControlArea">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Buku:</label>
                        <div class="qty-control">
                            <button type="button" onclick="adjustQty(-1)" class="w-10 h-10 rounded bg-gray-100 hover:bg-gray-200 text-xl font-bold">-</button>
                            <input type="number" name="jumlah" id="qtyInput" value="1" min="1" max="1" readonly>
                            <button type="button" onclick="adjustQty(1)" class="w-10 h-10 rounded bg-gray-100 hover:bg-gray-200 text-xl font-bold">+</button>
                        </div>
                        <p class="text-xs text-gray-500 mb-4 text-center">(Maksimal pinjam: <span id="maxQtyLabel">1</span>)</p>
                    </div>
                    
                    <div id="outOfStockMsg" class="hidden text-red-500 font-bold mb-4 bg-red-50 p-3 rounded">
                        Maaf, Stok Buku Habis.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-full btn-cancel" onclick="closeModal()">Batal</button>
                        <button type="submit" id="btnPinjam" class="btn-full btn-confirm">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('bookModal');
        const qtyInput = document.getElementById('qtyInput');
        
        function openModal(book) {
            // Isi Data Modal
            document.getElementById('modalTitle').textContent = book.judul_buku;
            document.getElementById('modalAuthor').textContent = book.penulis;
            document.getElementById('modalClass').textContent = book.kelas ? 'Kelas ' + book.kelas : 'Umum';
            document.getElementById('modalStockDisplay').textContent = book.salinan_tersedia;
            document.getElementById('modalBookId').value = book.id;

            // Gambar
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

            // Logic Stok & Input
            const maxStock = parseInt(book.salinan_tersedia);
            const btn = document.getElementById('btnPinjam');
            const controlArea = document.getElementById('stockControlArea');
            const msgArea = document.getElementById('outOfStockMsg');

            qtyInput.value = 1;

            if (maxStock > 0) {
                qtyInput.max = maxStock;
                document.getElementById('maxQtyLabel').textContent = maxStock;
                controlArea.style.display = 'block';
                msgArea.style.display = 'none';
                btn.disabled = false;
            } else {
                controlArea.style.display = 'none';
                msgArea.style.display = 'block';
                btn.disabled = true;
            }

            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function adjustQty(change) {
            let current = parseInt(qtyInput.value);
            let max = parseInt(qtyInput.max);
            let newVal = current + change;
            if (newVal >= 1 && newVal <= max) {
                qtyInput.value = newVal;
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }

        // Logic Floating Bar
        function updateFloatingBar() {
            const checkboxes = document.querySelectorAll('.select-book-checkbox:checked');
            const count = checkboxes.length;
            const floatingBar = document.getElementById('floatingBar');
            const countText = document.getElementById('selectedCount');

            if (count > 0) {
                floatingBar.classList.add('active');
                countText.textContent = count;
            } else {
                floatingBar.classList.remove('active');
            }
        }
    </script>

</body>
</html>