<?php
//adminProfilViews.php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../login.php"); 
    exit(); 
}

include '../config/database.php';
include '../models/userModels.php';
include '../header.php';

$user_id = $_SESSION['user_id'];
$user_data = getUserById($conn, $user_id);

if (!$user_data) {
    header("Location: ../controller/logout.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Saya</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px;  }
        .form-container {
            
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 3px; }
        .form-group input[disabled] { background-color: #eee; }

        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body class="ml-[320px] bg-[#EDF0F7] ">

    <?php include 'partials/sidebar.php'; ?>
 <p class="my-5 font-semibold text-xl mt-2  bg-white rounded-xl shadow-md py-4 md:p-6">Pengaturan Profile</p>
    <div class="form-container">
        <h2>Edit Profil Saya</h2>

        <?php if (isset($_GET['success'])) echo '<div class="alert alert-success">'.htmlspecialchars($_GET['success']).'</div>'; ?>
        <?php if (isset($_GET['error'])) echo '<div class="alert alert-error">'.htmlspecialchars($_GET['error']).'</div>'; ?>

        <form action="../controller/userController.php" method="POST">
            <input type="hidden" name="action" value="update_profile">
            <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
            
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Username (Login):</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Password Baru:</label>
                <input type="password" name="password" placeholder="(Kosongkan jika tidak ingin diubah)">
            </div>
            
            <div class="form-group">
                <label>Role:</label>
                <input type="text" value="<?php echo htmlspecialchars($user_data['role']); ?>" disabled>
            </div>
            
            <div class="button-group flex gap-4 justify-end">
                <button class="bg-green-500 rounded-full py-2 px-2 font-semibold text-white" type="submit">Update Profil</button> 
                <button class="bg-blue-500 rounded-full py-2 px-2 font-semibold text-white" type="button" onclick="window.location.href='dashboardAdmin.php'">Kembali</button>
            </div>
        </form>
    </div>

</body>
</html>