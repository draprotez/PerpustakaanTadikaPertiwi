<?php
// controller/carouselController.php
session_start();
include '../config/database.php';
include '../models/carouselModels.php'; //

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add_book':
        if (isset($_POST['buku_id'])) {
            // Panggil fungsi dari Model
            $result = addBookToCarousel($conn, $_POST['buku_id']);
            
            if ($result === true) {
                header('Location: ../views/carouselViews.php?success=Buku berhasil ditambahkan ke carousel!');
            } else {
                // $result berisi pesan error string jika gagal
                header('Location: ../views/carouselViews.php?error=' . urlencode($result));
            }
        }
        break;

    case 'remove':
        if (isset($_GET['id'])) {
            // Panggil fungsi dari Model
            $result = removeBookFromCarousel($conn, $_GET['id']);
            
            if ($result === true) {
                header('Location: ../views/carouselViews.php?success=Buku berhasil dihapus dari carousel!');
            } else {
                header('Location: ../views/carouselViews.php?error=' . urlencode($result));
            }
        }
        break;

    case 'toggle':
        if (isset($_GET['id'])) {
            // Panggil fungsi dari Model
            $result = toggleCarouselStatus($conn, $_GET['id']);
            
            if ($result === true) {
                header('Location: ../views/carouselViews.php?success=Status buku berhasil diubah!');
            } else {
                header('Location: ../views/carouselViews.php?error=' . urlencode($result));
            }
        }
        break;

    case 'update_order':
        if (isset($_POST['urutan']) && is_array($_POST['urutan'])) {
            // Panggil fungsi dari Model
            $result = updateCarouselOrder($conn, $_POST['urutan']);
            
            if ($result === true) {
                header('Location: ../views/carouselViews.php?success=Urutan buku berhasil diperbarui!');
            } else {
                header('Location: ../views/carouselViews.php?error=' . urlencode($result));
            }
        }
        break;

    default:
        header('Location: ../views/carouselViews.php');
        break;
}

$conn->close();
exit;
?>