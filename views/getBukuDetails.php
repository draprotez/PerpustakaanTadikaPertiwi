<?php
// getBukuDetails.php
session_start();
include '../config/database.php';
include '../models/bukuModels.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['member_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID buku tidak ditemukan']);
    exit();
}

$id = $_GET['id'];
$buku_data = getBukuById($conn, $id);

if (!$buku_data) {
    echo json_encode(['error' => 'Data buku tidak ditemukan']);
    exit();
}

echo json_encode($buku_data);

$conn->close();
?>