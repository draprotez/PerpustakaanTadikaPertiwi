<?php
//requestController.php
session_start();
include '../config/database.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buku_id'])) {
    $member_id = $_SESSION['member_id'];
    $buku_id = $_POST['buku_id'];

    $sql_stok = "SELECT salinan_tersedia, judul_buku FROM buku WHERE id = ?";
    $stmt = $conn->prepare($sql_stok);
    $stmt->bind_param("i", $buku_id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();

    if ($book['salinan_tersedia'] <= 0) {
        header("Location: ../views/lihatBukuViews.php?error=Gagal: Stok buku '{$book['judul_buku']}' habis.");
        exit();
    }

    $sql_check = "SELECT id FROM peminjaman 
                  WHERE member_id = ? AND buku_id = ? AND status IN ('borrowed', 'overdue', 'requested')";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $member_id, $buku_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows > 0) {
        header("Location: ../views/lihatBukuViews.php?error=Anda sedang meminjam atau sudah mengajukan buku ini.");
        exit();
    }

    $conn->begin_transaction();

    try {
        $tenggat_waktu = date('Y-m-d', strtotime('+7 days'));

        $sql_insert = "INSERT INTO peminjaman (member_id, buku_id, tanggal_pinjam, tenggat_waktu, status) 
                       VALUES (?, ?, CURDATE(), ?, 'borrowed')";
        
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iis", $member_id, $buku_id, $tenggat_waktu);
        $stmt_insert->execute();

        $sql_update_stok = "UPDATE buku SET salinan_tersedia = salinan_tersedia - 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_stok);
        $stmt_update->bind_param("i", $buku_id);
        $stmt_update->execute();
        
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