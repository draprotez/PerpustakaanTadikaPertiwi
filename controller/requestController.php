<?php
// controller/requestController.php
session_start();
require_once '../config/database.php';

// 1. Cek Login (Hanya Member yang bisa akses)
if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

$member_id = $_SESSION['member_id'];
$tanggal_pinjam = date('Y-m-d');
$tenggat_waktu = date('Y-m-d', strtotime('+7 days')); // Default pinjam 7 hari

// =======================================================================
// KASUS 1: PEMINJAMAN BANYAK JUDUL SEKALIGUS (BULK LOAN via CHECKBOX)
// =======================================================================
if (isset($_POST['action']) && $_POST['action'] === 'bulk_loan') {
    
    // Validasi: Apakah ada buku yang dipilih?
    if (empty($_POST['buku_ids'])) {
        header("Location: ../views/lihatBukuViews.php?error=Pilih setidaknya satu buku.");
        exit();
    }

    $buku_ids = $_POST['buku_ids']; // Array ID Buku yang dipilih
    $success_count = 0;
    $fail_count = 0;

    // Siapkan statement INSERT di luar loop (Optimasi)
    $stmtInsert = $conn->prepare("INSERT INTO peminjaman (member_id, buku_id, tanggal_pinjam, tenggat_waktu, status) VALUES (?, ?, ?, ?, 'borrowed')");

    foreach ($buku_ids as $buku_id) {
        $buku_id = intval($buku_id);

        // 1. Cek Stok Buku per ID
        $cekStok = $conn->query("SELECT salinan_tersedia FROM buku WHERE id = $buku_id");
        $stokData = $cekStok->fetch_assoc();

        if ($stokData && $stokData['salinan_tersedia'] > 0) {
            
            // 2. Eksekusi Peminjaman
            $stmtInsert->bind_param("iiss", $member_id, $buku_id, $tanggal_pinjam, $tenggat_waktu);
            
            if ($stmtInsert->execute()) {
                // 3. Update Stok (Kurangi 1)
                $conn->query("UPDATE buku SET salinan_tersedia = salinan_tersedia - 1 WHERE id = $buku_id");
                $success_count++;
            } else {
                $fail_count++;
            }
        } else {
            $fail_count++; // Stok habis saat diproses
        }
    }
    
    $stmtInsert->close();

    // Feedback pesan ke user
    $msg = "Berhasil meminjam $success_count buku.";
    if ($fail_count > 0) {
        $msg .= " Gagal meminjam $fail_count buku (Stok habis atau error).";
    }

    header("Location: ../views/lihatBukuViews.php?success=" . urlencode($msg));
    exit();
}

// =======================================================================
// KASUS 2: PEMINJAMAN BANYAK COPY DARI 1 JUDUL (SINGLE TITLE - MULTI COPY)
// =======================================================================
if (isset($_POST['buku_id'])) {
    $buku_id = intval($_POST['buku_id']);
    
    // Ambil jumlah yang diminta dari input modal (default 1)
    $jumlah_pinjam = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 1;

    // Validasi input agar tidak 0 atau negatif
    if ($jumlah_pinjam < 1) $jumlah_pinjam = 1;

    // 1. Cek Ketersediaan Stok
    // Pastikan stok cukup untuk jumlah yang diminta
    $cekStok = $conn->query("SELECT salinan_tersedia, judul_buku FROM buku WHERE id = $buku_id");
    $stokData = $cekStok->fetch_assoc();

    if ($stokData && $stokData['salinan_tersedia'] >= $jumlah_pinjam) {
        
        $berhasil = 0;
        
        // Siapkan statement INSERT
        $stmt = $conn->prepare("INSERT INTO peminjaman (member_id, buku_id, tanggal_pinjam, tenggat_waktu, status) VALUES (?, ?, ?, ?, 'borrowed')");
        
        // 2. Looping Insert
        // Masukkan data ke tabel peminjaman sebanyak jumlah copy yang diminta
        for ($i = 0; $i < $jumlah_pinjam; $i++) {
            $stmt->bind_param("iiss", $member_id, $buku_id, $tanggal_pinjam, $tenggat_waktu);
            if ($stmt->execute()) {
                $berhasil++;
            }
        }
        $stmt->close();

        // 3. Update Stok Sekaligus
        if ($berhasil > 0) {
            // Kurangi stok buku sebesar jumlah yang berhasil dipinjam
            $conn->query("UPDATE buku SET salinan_tersedia = salinan_tersedia - $berhasil WHERE id = $buku_id");
            
            $judul = $stokData['judul_buku'];
            $msg = "Berhasil meminjam $berhasil eksemplar buku '$judul'.";
            header("Location: ../views/lihatBukuViews.php?success=" . urlencode($msg));
        } else {
            header("Location: ../views/lihatBukuViews.php?error=Terjadi kesalahan saat memproses data.");
        }

    } else {
        // Stok tidak cukup
        $stokAda = $stokData['salinan_tersedia'] ?? 0;
        $msg = "Stok tidak cukup. Anda meminta $jumlah_pinjam, tapi hanya tersedia $stokAda.";
        header("Location: ../views/lihatBukuViews.php?error=" . urlencode($msg));
    }
    exit();
}

// =======================================================================
// DEFAULT FALLBACK
// =======================================================================
// Jika file ini diakses langsung tanpa POST, kembalikan ke view
header("Location: ../views/lihatBukuViews.php");
exit();
?>