<?php
session_start();
// Memanggil file koneksi dan model
require_once('../config/database.php');
require_once('../models/bukuModels.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'create' && $_SERVER["REQUEST_METHOD"] == "POST") {

    $bukuModel = new Buku($conn);

    $bukuModel->kode_buku = $_POST['kode_buku'];
    $bukuModel->judul_buku = $_POST['judul_buku'];
    $bukuModel->penulis = $_POST['penulis'];
    $bukuModel->isbn = $_POST['isbn'];
    $bukuModel->penerbit = $_POST['penerbit'];
    $bukuModel->tahun_terbit = $_POST['tahun_terbit'];
    $bukuModel->total_copy = $_POST['total_copy'];
    $bukuModel->salinan_tersedia = $_POST['total_copy']; 
    $bukuModel->lokasi_rak = $_POST['lokasi_rak'];

    if ($bukuModel->create()) {
        $_SESSION['success'] = "Buku baru berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan buku.";
    }

    header("Location: ../views/bukuViews.php");
    exit();

} 
// Anda bisa tambahkan "else if ($action === 'update')" di sini nanti
// Anda bisa tambahkan "else if ($action === 'delete')" di sini nanti

else {
    $_SESSION['error'] = "Aksi tidak valid.";
    header("Location: ../views/bukuViews.php");
    exit();
}
?>