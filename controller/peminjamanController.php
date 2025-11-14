<?php
// controller/peminjamanController.php

session_start();
include '../config/database.php';
include '../models/peminjamanModels.php';

if (!isset($_SESSION['user_id'])) { 
    die("ERROR: Hanya petugas yang dapat mengakses ini!"); 
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if (isset($_POST['member_id'], $_POST['buku_id'])) {
            
            $tenggat = $_POST['tenggat_waktu'] ?? date('Y-m-d', strtotime('+7 days'));
            
            $data = [
                'member_id' => $_POST['member_id'],
                'buku_id' => $_POST['buku_id'],
                'tenggat_waktu' => $tenggat,
                'created_by' => $_SESSION['user_id']
            ];
            
            $result = insertPeminjaman($conn, $data);
            
            if ($result === true) {
                header("Location: ../views/peminjamanViews.php?success=Buku berhasil dipinjam!");
            } else {
                header("Location: ../views/peminjamanViews.php?error=" . urlencode($result));
            }
        }
        break;

    case 'return':
        if (isset($_GET['id']) && isset($_GET['buku_id'])) {
            $peminjaman_id = $_GET['id'];
            $buku_id = $_GET['buku_id'];
            
            $result = returnBuku($conn, $peminjaman_id, $buku_id);
            
            if ($result === true) {
                header("Location: ../views/peminjamanViews.php?success=Buku telah dikembalikan!");
            } else {
                header("Location: ../views/peminjamanViews.php?error=" . urlencode($result));
            }
        }
        break;

    default:
        header("Location: ../views/peminjamanViews.php");
        break;
}

$conn->close();
exit();
?>