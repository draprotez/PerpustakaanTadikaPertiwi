<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-[#1C77D2]">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">

        <!-- Logo -->
        <div class="flex flex-col items-center mb-6">
            <img src="assets/images/logo/logo-smk.png" 
                 alt="Logo Sekolah" class="w-20 mb-3">
            <h2 class="text-2xl font-bold text-gray-700">Login Sistem</h2>
        </div>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['error'])): ?>
            <p class="text-red-600 text-center mb-4 font-semibold">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </p>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="text-green-600 text-center mb-4 font-semibold">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </p>
        <?php endif; ?>

        <form action="controller/loginController.php" method="POST" class="space-y-4">

            <!-- Login As -->
            <div>
                <label class="block text-gray-600 mb-1">Login Sebagai:</label>
                <select name="login_as" id="login_as" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="member">Member (Siswa/Guru)</option>
                    <option value="user">Petugas (Admin/Staff)</option>
                </select>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-gray-600 mb-1">ID Pengguna:</label>
                <div class="flex items-center border rounded-lg px-3">
                    <!-- Icon User -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>

                    <input type="text" id="username" name="username" placeholder="NISN / NUPTK / Username" 
                        class="w-full py-2 px-2 focus:outline-none" required>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-600 mb-1">Password:</label>
                <div class="flex items-center border rounded-lg px-3">
                    <!-- Icon password -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>

                    <input type="password" id="password" name="password" placeholder="Password" 
                        class="w-full py-2 px-2 focus:outline-none" required>
                </div>
            </div>

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full bg-[#1C77D2] hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                Login
            </button>

        </form>

        <p class="mt-4 text-center text-sm">
            Belum punya akun? <a href="register.php" class="text-blue-600 font-semibold">Register Member</a>
        </p>

    </div>

</body>
</html>
