<?php
session_start();
require_once('../config/database.php');
require_once('../models/bukuModels.php');

$bukuModel = new Buku($conn);

$daftarBuku = $bukuModel->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Daftar Buku Perpustakaan</h2>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    ?>

    <p><a href="create.php"><button>+ Tambah Buku Baru</button></a></p>

    <table>
        <thead>
            <tr>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($daftarBuku->num_rows > 0) {
                while ($buku = $daftarBuku->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($buku['kode_buku']) . "</td>";
                    echo "<td>" . htmlspecialchars($buku['judul_buku']) . "</td>";
                    echo "<td>" . htmlspecialchars($buku['penulis']) . "</td>";
                    echo "<td>" . htmlspecialchars($buku['penerbit']) . "</td>";
                    echo "<td>" . htmlspecialchars($buku['tahun_terbit']) . "</td>";
                    echo "<td>" . htmlspecialchars($buku['salinan_tersedia']) . " / " . htmlspecialchars($buku['total_copy']) . "</td>";
                    echo "<td><a href='edit.php?id=" . $buku['id'] . "'>Edit</a> | <a href='delete.php?id=" . $buku['id'] . "'>Hapus</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Belum ada data buku.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>