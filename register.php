<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Register</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        #siswa, #guru { display: none; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-[#1C77D2] py-10">

    <div class="bg-white w-full max-w-xl rounded-2xl shadow-xl p-8">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <img src="assets/images/logo/logo-smk.png" 
                 alt="Logo Sekolah" class="w-20 mx-auto mb-3">
            <h2 class="text-2xl font-bold text-gray-700">Daftar Akun</h2>
        </div>

        <!-- Form -->
        <form action="controller/registerController.php" method="POST" class="space-y-4">

            <!-- Pilih Input -->
            <div>
                <label class="block text-gray-600 mb-1 font-semibold">Daftar Sebagai</label>
                <select name="type" id="type" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Input</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                </select>
            </div>

            <!-- Data Siswa -->
            <div id="siswa" class="bg-blue-50 p-4 rounded-lg space-y-3 border border-blue-200">
                <h3 class="font-semibold text-blue-700">Data Siswa</h3>

                <input type="text" name="nisn" placeholder="NISN"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <input type="text" name="nis" placeholder="NIS"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">

                <input type="text" name="kelas" placeholder="Kelas"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Data Guru -->
            <div id="guru" class="bg-yellow-50 p-4 rounded-lg space-y-3 border border-yellow-200">
                <h3 class="font-semibold text-yellow-700">Data Guru</h3>

                <input type="text" name="nuptk" placeholder="NUPTK"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">

                <input type="text" name="nip" placeholder="NIP"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">

                <input type="text" name="mapel" placeholder="Mata Pelajaran"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">

                <input type="text" name="kelas_guru" placeholder="Kelas"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Data Umum -->
            <div class="space-y-3">

                <input type="text" name="name" placeholder="Nama Lengkap"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>

                <input type="email" name="email" placeholder="Email"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>

     <input type="text" name="no_hp" id="no_hp" placeholder="No Telepon"
    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
    required maxlength="13">

                <input type="text" name="alamat" placeholder="Alamat"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>

                <input type="password" name="password" placeholder="Password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>

                <input type="password" name="confirm_password" placeholder="Konfirmasi Password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit"
                class="w-full bg-[#1C77D2] hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                Daftar
            </button>

        </form>

        <!-- Link Login -->
        <p class="mt-4 text-center text-sm">
            Sudah punya akun? 
            <a href="login.php" class="text-blue-600 font-semibold hover:underline">Login</a>
        </p>
    </div>

    <script>
        var siswaDiv = document.getElementById('siswa');
        var guruDiv = document.getElementById('guru');

        var siswaInputs = siswaDiv.querySelectorAll('input');
        var guruInputs = guruDiv.querySelectorAll('input');

        function setInputsDisabled(inputs, disabled) {
            inputs.forEach(input => input.disabled = disabled);
        }

        setInputsDisabled(siswaInputs, true);
        setInputsDisabled(guruInputs, true);

        document.getElementById('type').addEventListener('change', function() {
            var tipe = this.value;

            if (tipe === 'siswa') {
                siswaDiv.style.display = 'block';
                guruDiv.style.display = 'none';

                setInputsDisabled(siswaInputs, false);
                setInputsDisabled(guruInputs, true);

            } else if (tipe === 'guru') {
                siswaDiv.style.display = 'none';
                guruDiv.style.display = 'block';

                setInputsDisabled(siswaInputs, true);
                setInputsDisabled(guruInputs, false);

            } else {
                siswaDiv.style.display = 'none';
                guruDiv.style.display = 'none';

                setInputsDisabled(siswaInputs, true);
                setInputsDisabled(guruInputs, true);
            }
        });
    </script>

</body>
</html>
<script>
document.getElementById('no_hp').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, ''); // hanya angka
});
</script>

