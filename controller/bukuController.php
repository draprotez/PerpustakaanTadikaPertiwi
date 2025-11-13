<?php
// bukuController.php
session_start();
include '../config/database.php';
include '../models/bukuModels.php'; 

if (!isset($_SESSION['user_id']) && !isset($_SESSION['member_id'])) { 
    die("ERROR: Anda harus login untuk mengakses ini!"); 
}
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if (isset($_POST['judul_buku'])) {
            $data = [
                'kode_buku' => $_POST['kode_buku'],
                'judul_buku' => $_POST['judul_buku'],
                'penulis' => $_POST['penulis'],
                'isbn' => $_POST['isbn'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'total_copy' => $_POST['total_copy'],
                'lokasi_rak' => $_POST['lokasi_rak']
            ];
            $result = insertBuku($conn, $data);
            
            if ($result) {
                header("Location: ../views/kelolaBukuViews.php?success=Buku baru berhasil ditambahkan!");
            } else {
                header("Location: ../views/kelolaBukuViews.php?error=Gagal menambahkan data buku!");
            }
        }
        break;

    case 'update':
        if (isset($_POST['id'])) {
            $data = [
                'id' => $_POST['id'],
                'kode_buku' => $_POST['kode_buku'],
                'judul_buku' => $_POST['judul_buku'],
                'penulis' => $_POST['penulis'],
                'isbn' => $_POST['isbn'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'total_copy' => $_POST['total_copy'],
                'salinan_tersedia' => $_POST['salinan_tersedia'],
                'lokasi_rak' => $_POST['lokasi_rak']
            ];
            $result = updateBuku($conn, $data);
            
            if ($result) {
                header("Location: ../views/kelolaBukuViews.php?success=Data buku berhasil diupdate!");
            } else {
                header("Location: ../views/kelolaBukuViews.php?error=Gagal mengupdate data buku!");
            }
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = deleteBuku($conn, $id);
            
            if ($result) {
                header("Location: ../views/kelolaBukuViews.php?success=Data buku berhasil dihapus!");
            } else {
                header("Location: ../views/kelolaBukuViews.php?error=Gagal menghapus data! (Mungkin buku sedang dipinjam)");
            }
        }
        break;

    default:
        header("Location: ../views/kelolaBukuViews.php");
        break;
}

$conn->close();
exit();
?>