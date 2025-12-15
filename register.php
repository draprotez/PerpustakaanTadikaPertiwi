<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Register</title>
    <link rel="website icon" type="png" href="assets/images/logo/logo-smk.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #siswa, #guru { display: none; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-[#1C77D2] py-10">

    <div class="bg-white w-full max-w-xl rounded-2xl shadow-xl p-8">
        
        <div class="text-center mb-6">
            <img src="assets/images/logo/logo-smk.png" 
                 alt="Logo Sekolah" class="w-20 mx-auto mb-3">
            <h2 class="text-2xl font-bold text-gray-700">Daftar Akun</h2>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="controller/registerController.php" method="POST" class="space-y-4">

            <div>
                <label class="block text-gray-600 mb-1 font-semibold">Daftar Sebagai</label>
                <select name="type" id="type" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                </select>
            </div>

            <div id="siswa" class="bg-blue-50 p-4 rounded-lg space-y-3 border border-blue-200">
                <h3 class="font-semibold text-blue-700">Data Siswa</h3>
                <input type="text" name="nisn" placeholder="NISN"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div id="guru" class="bg-yellow-50 p-4 rounded-lg space-y-3 border border-yellow-200">
                <h3 class="font-semibold text-yellow-700">Data Guru</h3>
                <input type="text" name="kode_guru" placeholder="Kode Guru"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>

            <div class="space-y-3">
                <input type="text" name="name" placeholder="Nama Lengkap"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>

                <input type="email" name="email" placeholder="Email (Username)"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>

                <input type="password" name="password" placeholder="Password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>

                <input type="password" name="confirm_password" placeholder="Konfirmasi Password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <button type="submit"
                class="w-full bg-[#1C77D2] hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                Daftar
            </button>

        </form>

        <p class="mt-4 text-center text-sm">
            Sudah punya akun? 
            <a href="login.php" class="text-blue-600 font-semibold hover:underline">Login</a>
        </p>
    </div>

    <script>
        const typeSelect = document.getElementById('type');
        const siswaDiv = document.getElementById('siswa');
        const guruDiv = document.getElementById('guru');
        
        // Ambil input di dalam div masing-masing
        const siswaInput = siswaDiv.querySelector('input');
        const guruInput = guruDiv.querySelector('input');

        function toggleFields() {
            const tipe = typeSelect.value;

            if (tipe === 'siswa') {
                siswaDiv.style.display = 'block';
                guruDiv.style.display = 'none';
                siswaInput.required = true;
                guruInput.required = false;
            } else if (tipe === 'guru') {
                siswaDiv.style.display = 'none';
                guruDiv.style.display = 'block';
                siswaInput.required = false;
                guruInput.required = true;
            } else {
                siswaDiv.style.display = 'none';
                guruDiv.style.display = 'none';
                siswaInput.required = false;
                guruInput.required = false;
            }
        }

        typeSelect.addEventListener('change', toggleFields);
    </script>

</body>
</html>