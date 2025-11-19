<?php
// controller/memberController.php

session_start();
include '../config/database.php';
include '../models/memberModels.php';

if (!isset($_SESSION['user_id'])) { 
    die("ERROR: Hanya petugas yang dapat mengakses ini!"); 
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        // ... (Bagian create tidak berubah) ...
        if (isset($_POST['name'], $_POST['username'], $_POST['password'])) {
            $data = [
                'name' => $_POST['name'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'type' => $_POST['type'],
                'status' => $_POST['status'],
                'nisn' => $_POST['nisn'] ?: null,
                'nis' => $_POST['nis'] ?: null,
                'nuptk' => $_POST['nuptk'] ?: null,
                'nip' => $_POST['nip'] ?: null,
                'kelas' => $_POST['kelas'],
                'keterangan' => $_POST['keterangan']
            ];
            
            $result = insertMember($conn, $data);
            
            if ($result) {
                header("Location: ../views/memberViews.php?success=Anggota baru berhasil ditambahkan!");
            } else {
                if ($conn->errno == 1062) {
                    header("Location: ../views/memberViews.php?error=Gagal: Username (Email), NISN, NIS, NUPTK, atau NIP sudah terdaftar!");
                } else {
                    header("Location: ../views/memberViews.php?error=Gagal menambahkan anggota!");
                }
            }
        }
        break;

    case 'update':
        // ... (Bagian update tidak berubah) ...
        if (isset($_POST['id'])) {
            $data = [
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'type' => $_POST['type'],
                'status' => $_POST['status'],
                'nisn' => $_POST['nisn'] ?: null,
                'nis' => $_POST['nis'] ?: null,
                'nuptk' => $_POST['nuptk'] ?: null,
                'nip' => $_POST['nip'] ?: null,
                'kelas' => $_POST['kelas'],
                'keterangan' => $_POST['keterangan']
            ];
            
            $result = updateMember($conn, $data);
            
            if ($result) {
                header("Location: ../views/memberViews.php?success=Data anggota berhasil diupdate!");
            } else {
                if ($conn->errno == 1062) {
                    header("Location: ../views/memberViews.php?error=Gagal: Username (Email), NISN, NIS, NUPTK, atau NIP sudah terdaftar!");
                } else {
                    header("Location: ../views/memberViews.php?error=Gagal mengupdate data!");
                }
            }
        }
        break;

    // ▼▼▼ PERBAIKAN UTAMA ADA DI SINI (BAGIAN DELETE) ▼▼▼
    case 'delete':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            try {
                // Mencoba menghapus member
                $result = deleteMember($conn, $id);
                
                if ($result) {
                    header("Location: ../views/memberViews.php?success=Data anggota berhasil dihapus!");
                }
            } catch (mysqli_sql_exception $e) {
                // Menangkap error Foreign Key Constraint (Error 1451)
                if ($e->getCode() == 1451) {
                    header("Location: ../views/memberViews.php?error=Gagal Hapus: Anggota ini masih memiliki riwayat peminjaman buku. Hapus data peminjamannya terlebih dahulu.");
                } else {
                    // Error database lainnya
                    header("Location: ../views/memberViews.php?error=Terjadi kesalahan database: " . $e->getMessage());
                }
            } catch (Exception $e) {
                // Error umum
                header("Location: ../views/memberViews.php?error=Terjadi kesalahan sistem.");
            }
        }
        break;
    // ▲▲▲ AKHIR PERBAIKAN ▲▲▲

    default:
        header("Location: ../views/memberViews.php");
        break;
}

$conn->close();
exit();
?>