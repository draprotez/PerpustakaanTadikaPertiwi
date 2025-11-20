<?php
// views/partials/sidebar.php
// Shared sidebar partial for admin pages
?>
<div class="sidebar fixed left-0 top-0 w-[280px] h-screen bg-[#1C77D2] overflow-auto p-5 text-white">
    <div class="logo text-center mb-4">
        <img src="../assets/images/logo/logo-smk.png" alt="Logo SMK" class="w-[50px] h-[50px] object-contain mx-auto mb-2" />
        <p class="text-sm">E-LIBRARY</p>
        <h2 class="text-lg font-bold">SMK TADIKA PERTIWI</h2>
    </div>
    <div class="menu-links">
        <a href="dashboardAdmin.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/dashboard-icon (light).png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Dashboard
        </a>
        <a href="bukuViews.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/subway_book.png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Manajemen Buku
        </a>
        <a href="peminjamanViews.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/ri_draft-fill.png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Peminjaman
        </a>
        <a href="memberViews.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/tdesign_member-filled.png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Anggota
        </a>
        <a href="carouselViews.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/tabler_carousel-horizontal-filled.png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Buku dashboard
        </a>
        <a href="laporanViews.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/mdi_report-line.png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Laporan
        </a>
        <a href="adminProfilViews.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/weui_setting-filled.png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Pengaturan
        </a>
        <a href="../logout.php" class="flex items-center text-white font-bold text-[20px] py-2 px-3 rounded hover:text-yellow-400 transition-colors" style="font-family: 'Inter', sans-serif; font-weight:700;">
            <img src="../assets/images/icon/majesticons_logout.png" alt="icon" class="w-5 h-5 object-contain inline-block mr-3" />
            Logout
        </a>
    </div>
</div>
