<?php
session_start();
require_once('../config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok.";
        header("Location: ../registrasiAdmin.php");
        exit();
    }

    if (!in_array($role, ['admin', 'staff'])) {
        $_SESSION['error'] = "Role tidak valid.";
        header("Location: ../registrasiAdmin.php");
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt_check = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $_SESSION['error'] = "Username sudah terdaftar. Silakan gunakan username lain.";
            $stmt_check->close();
            header("Location: ../registrasiAdmin.php");
            exit();
        }
        $stmt_check->close();
        $sql = "INSERT INTO user (username, password, name, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $hashed_password, $name, $role);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registrasi petugas berhasil! Silakan login.";
            header("Location: ../login.php"); 
        } else {
            $_SESSION['error'] = "Registrasi gagal. Terjadi kesalahan: " . $stmt->error;
            header("Location: ../registrasiAdmin.php");
        }
        $stmt->close();

    } catch (Exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan server: " . $e->getMessage();
        header("Location: ../registrasiAdmin.php");
    }
    $conn->close();
    exit();

} else {
    header("Location: ../registrasiAdmin.php");
    exit();
}
?>