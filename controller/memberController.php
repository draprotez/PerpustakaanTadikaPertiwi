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

    case 'delete':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = deleteMember($conn, $id);
            
            if ($result) {
                header("Location: ../views/memberViews.php?success=Data anggota berhasil dihapus!");
            } else {
                if ($conn->errno == 1451) {
                    header("Location: ../views/memberViews.php?error=Gagal hapus! Anggota ini memiliki riwayat peminjaman.");
                } else {
                    header("Location: ../views/memberViews.php?error=Gagal menghapus data!");
                }
            }
        }
        break;

    default:
        header("Location: ../views/memberViews.php");
        break;
}

$conn->close();
exit();
?>