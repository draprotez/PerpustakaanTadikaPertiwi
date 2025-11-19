<?php
// controller/requestController.php
session_start();
include '../config/database.php';

// 1. Pastikan yang login adalah MEMBER
if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

// 2. Proses Permintaan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buku_id'])) {
    $member_id = $_SESSION['member_id'];
    $buku_id = $_POST['buku_id'];

    // A. Cek apakah stok buku tersedia
    $sql_stok = "SELECT salinan_tersedia, judul_buku FROM buku WHERE id = ?";
    $stmt = $conn->prepare($sql_stok);
    $stmt->bind_param("i", $buku_id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();

    if ($book['salinan_tersedia'] <= 0) {
        // Arahkan kembali ke halaman lihat buku
        header("Location: ../views/lihatBukuViews.php?error=Gagal: Stok buku '{$book['judul_buku']}' habis.");
        exit();
    }

    // B. Cek apakah member sedang meminjam buku yang SAMA (Mencegah double pinjam)
    // Cek status 'borrowed' atau 'overdue' atau 'requested'
    $sql_check = "SELECT id FROM peminjaman 
                  WHERE member_id = ? AND buku_id = ? AND status IN ('borrowed', 'overdue', 'requested')";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $member_id, $buku_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows > 0) {
        header("Location: ../views/lihatBukuViews.php?error=Anda sedang meminjam atau sudah mengajukan buku ini.");
        exit();
    }

    // C. PROSES PEMINJAMAN LANGSUNG
    // Mulai Transaksi agar aman
    $conn->begin_transaction();

    try {
        // Tenggat waktu 7 hari dari sekarang
        $tenggat_waktu = date('Y-m-d', strtotime('+7 days'));

        // Masukkan data 'borrowed' ke database
        // created_by NULL karena member yang meminjam sendiri
        $sql_insert = "INSERT INTO peminjaman (member_id, buku_id, tanggal_pinjam, tenggat_waktu, status) 
                       VALUES (?, ?, CURDATE(), ?, 'borrowed')"; // Langsung 'borrowed'
        
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iis", $member_id, $buku_id, $tenggat_waktu);
        $stmt_insert->execute();

        // Kurangi stok buku
        $sql_update_stok = "UPDATE buku SET salinan_tersedia = salinan_tersedia - 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_stok);
        $stmt_update->bind_param("i", $buku_id);
        $stmt_update->execute();
        
        // Commit transaksi
        $conn->commit();
        
        header("Location: ../views/lihatBukuViews.php?success=Berhasil meminjam buku '{$book['judul_buku']}'! Harap kembalikan sebelum tanggal $tenggat_waktu.");
        
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../views/lihatBukuViews.php?error=Terjadi kesalahan sistem.");
    }

} else {
    header("Location: ../views/lihatBukuViews.php");
}
?>