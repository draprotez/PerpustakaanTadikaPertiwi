<?php
session_start();
require_once ('../config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    if (empty($type)) {
        $_SESSION['error'] = "Silakan pilih tipe member (Siswa/Guru).";
        header("Location: ../register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok.";
        header("Location: ../register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $nisn = null;
    $nis = null;
    $nuptk = null;
    $nip = null;
    $kelas = null;
    $keterangan = null;
    $kode_member = null;

    if ($type === 'siswa') {
        $nisn = $_POST['nisn'];
        $nis = $_POST['nis'];
        $kelas = $_POST['kelas'];
        $kode_member = 'S-' . strtoupper(uniqid()); 
    
    } else if ($type === 'guru') {
        $nuptk = $_POST['nuptk'];
        $nip = $_POST['nip'];
        $mapel = $_POST['mapel'];
        $kelas = $_POST['kelas_guru'];
        $keterangan = $mapel;
        $kode_member = 'TP-' . strtoupper(uniqid());
    }

    try {
        $stmt_check = $conn->prepare("SELECT id FROM members WHERE username = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $_SESSION['error'] = "Email (username) sudah terdaftar.";
            header("Location: ../register.php");
            exit();
        }
        $stmt_check->close();
        $sql = "INSERT INTO members (
                    kode_member, username, password, name, type, 
                    nisn, nis, nuptk, nip, kelas, keterangan, 
                    registrasi, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), 'active')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssss",
            $kode_member,
            $email,
            $hashed_password,
            $name,
            $type,
            $nisn,
            $nis,
            $nuptk,
            $nip,
            $kelas,
            $keterangan
        );
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: ../login.php");
        } else {
            if ($conn->errno == 1062) {
                $_SESSION['error'] = "Registrasi gagal. Data (NISN/NIS/NUPTK/NIP) mungkin sudah terdaftar.";
            } else {
                $_SESSION['error'] = "Registrasi gagal. Terjadi kesalahan: " . $stmt->error;
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