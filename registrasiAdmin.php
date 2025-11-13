<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Petugas</title>
</head>
<body>
    <h2>Registrasi Petugas (Admin/Staff)</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);
    }
    ?>

    <form action="controller/registrasiAdminController.php" method="POST">
        <div>
            <label for="name">Nama Lengkap:</label>
            <input type="text" name="name" id="name" placeholder="Nama Lengkap" required>
        </div>
        <br>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Username" required>
        </div>
        <br>
        <div>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>
        </div>
        <br>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <br>
        <div>
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" required>
        </div>
        <br>
        <button type="submit">Daftarkan Petugas</button>
    </form>
    
    <p><a href="login.php">Kembali ke Login</a></p>

</body>
</html>