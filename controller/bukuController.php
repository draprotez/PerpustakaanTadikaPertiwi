<?php
// bukuController.php
session_start();
include '../config/database.php';
include '../models/bukuModels.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['member_id'])) { 
    die("ERROR: Anda harus login untuk mengakses ini!"); 
}

function handleImageUpload($fileInput, $existingImage = null) {
    $target_dir = "../assets/images/buku/";
    
    if (isset($fileInput) && $fileInput['error'] == 0) {
        $imageFileType = strtolower(pathinfo($fileInput["name"], PATHINFO_EXTENSION));
        $new_filename = 'cover_' . uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;

        $check = getimagesize($fileInput["tmp_name"]);
        if ($check === false) return (object)['error' => "File bukan gambar."];
        if ($fileInput["size"] > 2000000) return (object)['error' => "Ukuran file terlalu besar (Max 2MB)."];
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "svg"])) return (object)['error' => "Hanya format JPG, JPEG, PNG, & SVG."];

        if (move_uploaded_file($fileInput["tmp_name"], $target_file)) {
            if ($existingImage && file_exists($target_dir . $existingImage)) {
                unlink($target_dir . $existingImage);
            }
            return (object)['filename' => $new_filename];
        } else {
            return (object)['error' => "Gagal mengupload file."];
        }
    }
    return (object)['filename' => $existingImage];
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        if (isset($_POST['judul_buku'])) {
            $uploadResult = handleImageUpload($_FILES['gambar'] ?? null);
            if (isset($uploadResult->error)) {
                header("Location: ../views/bukuViews.php?error=" . urlencode($uploadResult->error));
                exit();
            }

            $data = [
                'kode_buku' => $_POST['kode_buku'],
                'judul_buku' => $_POST['judul_buku'],
                'penulis' => $_POST['penulis'],
                'isbn' => $_POST['isbn'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'kelas' => $_POST['kelas'],
                'kurikulum' => $_POST['kurikulum'],
                'total_copy' => $_POST['total_copy'],
                'gambar' => $uploadResult->filename
            ];
            
            $result = insertBuku($conn, $data);
            
            if ($result) {
                header("Location: ../views/bukuViews.php?success=Buku baru berhasil ditambahkan!");
            } else {
                header("Location: ../views/bukuViews.php?error=Gagal menambahkan data buku!");
            }
        }
        break;

    case 'update':
        if (isset($_POST['id'])) {
            $gambar_lama = $_POST['gambar_lama'] ?? null;
            $uploadResult = handleImageUpload($_FILES['gambar'] ?? null, $gambar_lama);
            if (isset($uploadResult->error)) {
                header("Location: ../views/bukuViews.php?error=" . urlencode($uploadResult->error));
                exit();
            }

            $data = [
                'id' => $_POST['id'],
                'kode_buku' => $_POST['kode_buku'],
                'judul_buku' => $_POST['judul_buku'],
                'penulis' => $_POST['penulis'],
                'isbn' => $_POST['isbn'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'kelas' => $_POST['kelas'],
                'kurikulum' => $_POST['kurikulum'],
                'total_copy' => $_POST['total_copy'],
                'salinan_tersedia' => $_POST['salinan_tersedia'],
                'gambar' => $uploadResult->filename
            ];
            
            $result = updateBuku($conn, $data);
            
            if ($result) {
                header("Location: ../views/bukuViews.php?success=Data buku berhasil diupdate!");
            } else {
                header("Location: ../views/bukuViews.php?error=Gagal mengupdate data buku!");
            }
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $bukuData = getBukuById($conn, $id);
            $gambar_lama = $bukuData['gambar'] ?? null;

            $result = deleteBuku($conn, $id);
            
            if ($result) {
                if ($gambar_lama && file_exists('../assets/images/buku/' . $gambar_lama)) {
                    unlink('../assets/images/buku/' . $gambar_lama);
                }
                header("Location: ../views/bukuViews.php?success=Data buku berhasil dihapus!");
            } else {
                header("Location: ../views/bukuViews.php?error=Gagal menghapus data! (Mungkin buku sedang dipinjam)");
            }
        }
        break;

    default:
        header("Location: ../views/bukuViews.php");
        break;
}

$conn->close();
exit();
?>