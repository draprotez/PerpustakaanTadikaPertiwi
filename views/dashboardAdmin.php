<?php
//dashboardAdmin.php
session_start();
include '../config/database.php';
include '../header.php';

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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan Tadika Pertiwi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Responsive table wrapper */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Custom scrollbar untuk tabel */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-[#EDF0F7]">

    <!-- Wrapper untuk sidebar dan main content -->
    <div class="flex min-h-screen">
        
        <?php include 'partials/sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="flex-1 lg:ml-80 p-4 md:p-6">
            
            <?php if ($isLoggedIn && $isAdmin) : ?>

                <!-- Header Dashboard -->
                <div class="mb-6 bg-white rounded-xl shadow-md p-4 md:p-6">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800 mb-1">
                        Dashboard Petugas (<?php echo htmlspecialchars($role_user); ?>)
                    </h1>
                    <p class="text-base md:text-lg text-gray-600">
                        Selamat datang, <span class="font-semibold"><?php echo htmlspecialchars($nama_user); ?></span>!
                    </p>
                </div>

                <!-- Dashboard Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                    
                    <!-- Card Total Buku -->
                    <div class="bg-[#3FB3AD] text-white rounded-xl shadow-lg p-5 md:p-6 hover:shadow-xl transition">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="../assets/images/icon/Vector.png" class="w-5 h-5" alt="Book Icon">
                            <h3 class="font-semibold text-sm md:text-base">Total Judul Buku</h3>
                        </div>
                        <p class="text-4xl md:text-5xl font-bold mb-3"><?php echo $total_buku; ?></p>
                        <a href="bukuViews.php" class="inline-block text-sm font-medium hover:underline">
                            Kelola Buku &raquo;
                        </a>
                    </div>

                    <!-- Card Total Anggota -->
                    <div class="bg-[#F6BC3D] text-white rounded-xl shadow-lg p-5 md:p-6 hover:shadow-xl transition">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="../assets/images/icon/majesticons_user.png" class="w-6 h-6" alt="User Icon">
                            <h3 class="font-semibold text-sm md:text-base">Total Anggota</h3>
                        </div>
                        <p class="text-4xl md:text-5xl font-bold mb-3"><?php echo $total_member; ?></p>
                        <a href="memberViews.php" class="inline-block text-sm font-medium hover:underline">
                            Kelola Anggota &raquo;
                        </a>
                    </div>

                    <!-- Card Buku Kadaluarsa -->
                    <div class="bg-[#DF4B41] text-white rounded-xl shadow-lg p-5 md:p-6 hover:shadow-xl transition">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="../assets/images/icon/pajamas_calendar-overdue.png" class="w-5 h-5" alt="Calendar Icon">
                            <h3 class="font-semibold text-sm md:text-base">Buku Kadaluarsa</h3>
                        </div>
                        <p class="text-4xl md:text-5xl font-bold mb-3"><?php echo $total_overdue; ?></p>
                        <a href="kelolaPeminjamanViews.php" class="inline-block text-sm font-medium hover:underline">
                            Kelola Peminjaman &raquo;
                        </a>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="my-6 md:my-8 border-gray-300">

                <!-- Search Form -->
                <div class="bg-white rounded-xl shadow-md p-4 md:p-6 mb-6">
                    <form action="" method="GET" class="space-y-4">
                        <label for="search" class="block text-sm md:text-base font-semibold text-gray-700">
                            Cari Peminjaman Aktif (Judul, Nama, NISN):
                        </label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input 
                                type="text" 
                                id="search" 
                                name="search" 
                                value="<?php echo htmlspecialchars($search); ?>" 
                                placeholder="Ketik untuk mencari..." 
                                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                            />
                            <div class="flex gap-2">
                                <button 
                                    type="submit" 
                                    class="flex-1 sm:flex-none bg-[#008CBA] hover:bg-[#007399] text-white px-5 py-2.5 rounded-full font-semibold flex items-center justify-center gap-2 transition text-sm md:text-base"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <span>Cari</span>
                                </button>
                                <a href="dashboardAdmin.php" class="flex-1 sm:flex-none">
                                    <button 
                                        type="button" 
                                        class="w-full bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-full font-semibold transition text-sm md:text-base"
                                    >
                                        Reset
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Peminjaman Aktif Table -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">Peminjaman Aktif</h2>
                    </div>

                    <!-- Responsive Table Wrapper -->
                    <div class="table-responsive">
                        <table class="w-full min-w-[640px]">
                            <thead>
                                <tr class="bg-[#73A7DB]">
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold text-gray-700">No</th>
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold text-gray-700">Judul Buku</th>
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold text-gray-700">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold text-gray-700">NISN</th>
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold text-gray-700">Nama Peminjam</th>
                                    <th class="px-4 py-3 text-left text-xs md:text-sm font-semibold text-gray-700">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($active_loans)): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-sm md:text-base">
                                            <?php if (!empty($search)): ?>
                                                Tidak ditemukan peminjaman aktif dengan kata kunci "<strong><?php echo htmlspecialchars($search); ?></strong>".
                                            <?php else: ?>
                                                Tidak ada data peminjaman aktif.
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($active_loans as $loan): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-sm md:text-base text-gray-700"><?php echo $no++; ?></td>
                                            <td class="px-4 py-3 text-sm md:text-base text-gray-800 font-medium">
                                                <?php echo htmlspecialchars($loan['judul_buku']); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm md:text-base text-gray-700">1</td>
                                            <td class="px-4 py-3 text-sm md:text-base text-gray-700">
                                                <?php echo htmlspecialchars($loan['nisn'] ?? '-'); ?>
                                            </td>
                                            <td class="px-4 py-3 text-sm md:text-base text-gray-700">
                                                <?php echo htmlspecialchars($loan['name']); ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php if ($loan['status'] == 'overdue'): ?>
                                                    <span class="inline-block px-3 py-1.5 bg-red-500 text-black font-semibold rounded-xl border border-black text-xs md:text-sm">
                                                        Kadaluarsa
                                                    </span>
                                                <?php elseif ($loan['status'] == 'borrowed'): ?>
                                                    <span class="inline-block px-3 py-1.5 bg-yellow-200 text-black font-semibold rounded-xl border border-black text-xs md:text-sm">
                                                        Dipinjam
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-sm md:text-base text-gray-700">
                                                        <?php echo htmlspecialchars($loan['status']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Note untuk mobile -->
                <div class="mt-4 text-xs md:text-sm text-gray-500 text-center sm:hidden">
                    💡 Geser tabel ke kiri/kanan untuk melihat lebih banyak data
                </div>

            <?php else : ?>
                
                <!-- Access Denied -->
                <div class="min-h-screen flex items-center justify-center px-4">
                    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 max-w-md w-full text-center border border-gray-200">
                        <div class="mb-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/6195/6195699.png" 
                                 class="w-16 md:w-20 mx-auto opacity-80" alt="Access Denied">
                        </div>
                        <h2 class="text-xl md:text-2xl font-bold text-red-600 mb-2">
                            Akses Ditolak
                        </h2>
                        <p class="text-sm md:text-base text-gray-600 mb-4">
                            Halaman ini hanya dapat diakses oleh petugas perpustakaan.
                        </p>
                        <p class="text-sm md:text-base text-gray-600 mb-6">
                            Silakan <a href="../login.php" class="text-blue-600 font-semibold hover:underline">
                                login sebagai petugas
                            </a> untuk mengakses dashboard.
                        </p>
                        <a href="../login.php">
                            <button class="w-full bg-[#1C77D2] hover:bg-blue-700 text-white py-2.5 md:py-3 rounded-lg font-semibold transition text-sm md:text-base">
                                Kembali ke Halaman Utama
                            </button>
                        </a>
                    </div>
                </div>

            <?php endif; ?>

        </main>

    </div>

</body>
</html>