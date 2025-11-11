<?php
session_start();
require_once('../config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_as = $_POST['login_as'];
    $username_input = $_POST['username']; 
    $password = $_POST['password'];

    if (empty($login_as) || empty($username_input) || empty($password)) {
        $_SESSION['error'] = "Semua field wajib diisi.";
        header("Location: ../login.php");
        exit();
    }

    try {

        if ($login_as === 'member') {
            $sql = "SELECT id, password, name, type, status FROM members WHERE nisn = ? OR nuptk = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username_input, $username_input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $member = $result->fetch_assoc();

                if (password_verify($password, $member['password'])) {
                    
                    if ($member['status'] !== 'active') {
                        $_SESSION['error'] = "Akun Anda tidak aktif. Hubungi petugas.";
                        header("Location: ../login.php");
                        exit();
                    }

                    $_SESSION['member_id'] = $member['id'];
                    $_SESSION['member_name'] = $member['name'];
                    $_SESSION['member_type'] = $member['type'];
                    
                    $stmt_update = $conn->prepare("UPDATE members SET last_login = NOW() WHERE id = ?");
                    $stmt_update->bind_param("i", $member['id']);
                    $stmt_update->execute();
                    $stmt_update->close();

                    header("Location: ../dashboard_member.php");
                    exit();

                } else {
                    $_SESSION['error'] = "NISN/NUPTK atau password salah.";
                }
            } else {
                $_SESSION['error'] = "NISN/NUPTK atau password salah.";
            }

        } else if ($login_as === 'user') {

            $sql = "SELECT id, password, name, role FROM user WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username_input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];

                    $stmt_update = $conn->prepare("UPDATE user SET last_login = NOW() WHERE id = ?");
                    $stmt_update->bind_param("i", $user['id']);
                    $stmt_update->execute();
                    $stmt_update->close();

                    header("Location: ../dashboard_admin.php");
                    exit();

                } else {
                    $_SESSION['error'] = "Username atau password salah.";
                }
            } else {
                $_SESSION['error'] = "Username atau password salah.";
            }

        } else {
            $_SESSION['error'] = "Tipe login tidak valid.";
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan server: " . $e->getMessage();
    }

    header("Location: ../login.php");
    exit();

} else {
    header("Location: ../login.php");
    exit();
}
?>