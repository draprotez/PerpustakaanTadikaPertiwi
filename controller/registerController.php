<?php
// controller/registerController.php
session_start();
require_once ('../config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi dasar
    if (empty($type)) {
        $_SESSION['error'] = "Silakan pilih tipe member.";
        header("Location: ../register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok.";
        header("Location: ../register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Siapkan variabel data
    $nisn = null;
    $kode_guru = null;
    $kode_member = 'TP-' . strtoupper(uniqid()); // Format kode: TP-XXXXX

    if ($type === 'siswa') {
        $nisn = $_POST['nisn'];
        if(empty($nisn)) {
            $_SESSION['error'] = "NISN wajib diisi untuk siswa.";
            header("Location: ../register.php");
            exit();
        }
    } else if ($type === 'guru') {
        $kode_guru = $_POST['kode_guru'];
        if(empty($kode_guru)) {
            $_SESSION['error'] = "Kode Guru wajib diisi untuk guru.";
            header("Location: ../register.php");
            exit();
        }
    }

    try {
        // Cek apakah email sudah terdaftar
        $stmt_check = $conn->prepare("SELECT id FROM members WHERE username = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $_SESSION['error'] = "Email sudah terdaftar.";
            header("Location: ../register.php");
            exit();
        }
        $stmt_check->close();

        // Insert ke database (sesuai kolom baru)
        $sql = "INSERT INTO members (
                    kode_member, username, password, name, type, 
                    nisn, kode_guru, registrasi, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), 'active')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssss",
            $kode_member,
            $email,
            $hashed_password,
            $name,
            $type,
            $nisn,
            $kode_guru
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: ../login.php");
        } else {
            if ($conn->errno == 1062) {
                $_SESSION['error'] = "Gagal: NISN, Kode Guru, atau Email sudah terdaftar.";
            } else {
                $_SESSION['error'] = "Terjadi kesalahan sistem.";
            }
            header("Location: ../register.php");
        }
        $stmt->close();

    } catch (Exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan server: " . $e->getMessage();
        header("Location: ../register.php");
    }
    $conn->close();
    exit();

} else {
    header("Location: ../register.php");
    exit();
}
?>