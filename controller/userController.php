<?php
//userController.php

session_start();
include '../config/database.php';
include '../models/userModels.php';

if (!isset($_SESSION['user_id'])) { 
    die("ERROR: Akses tidak sah!"); 
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'update_profile':
        if (isset($_POST['id'], $_POST['username'], $_POST['name'])) {
            if ($_POST['id'] != $_SESSION['user_id']) {
                header("Location: ../views/adminProfilViews.php?error=Aksi tidak diizinkan!");
                exit();
            }

            $data = [
                'id' => $_SESSION['user_id'],
                'username' => $_POST['username'],
                'name' => $_POST['name'],
                'password' => $_POST['password']
            ];
            
            $result = updateProfile($conn, $data);
            
            if ($result) {
                $_SESSION['user_name'] = $data['name'];
                header("Location: ../views/adminProfilViews.php?success=Profil berhasil diupdate!");
            } else {
                if ($conn->errno == 1062) { 
                    header("Location: ../views/adminProfilViews.php?error=Gagal: Username tersebut sudah digunakan!");
                } else {
                    header("Location: ../views/adminProfilViews.php?error=Gagal mengupdate profil!");
                }
            }
        }
        break;

    default:
        header("Location: ../views/adminProfilViews.php");
        break;
}

$conn->close();
exit();
?>