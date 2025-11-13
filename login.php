<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
</head>
<body>
    <h2>Login</h2>
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
    <form action="controller/loginController.php" method="POST">
        <div>
            <label for="login_as">Login sebagai:</label>
            <select name="login_as" id="login_as" required>
                <option value="member">Member (Siswa/Guru)</option>
                <option value="user">Petugas (Admin/Staff)</option>
            </select>
        </div>
        <br>
        <div>
            <label for="username">ID Pengguna:</label>
            <input type="text" id="username" name="username" placeholder="NISN / NUPTK / Username" required>
        </div>
        <br>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>
    <p><a href="register.php">Register Member</a></p>
</body>
</html>