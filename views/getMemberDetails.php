<?php
//getMemberDetails.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { 
    echo json_encode(['error' => 'Akses ditolak']);
    exit(); 
}

include '../config/database.php';
include '../models/memberModels.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID Anggota tidak ditemukan']);
    exit();
}

$id = (int)$_GET['id'];
$data = getMemberById($conn, $id);

if ($data) {
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Anggota tidak ditemukan']);
}

$conn->close();
?>