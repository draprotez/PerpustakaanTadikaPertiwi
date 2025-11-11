<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Register</title>
    <style>
        #siswa,
        #guru {
            display: none;
        }
    </style>
</head>
<body>
    <h2>Register</h2>
    <form action="controller/registerController.php" method="POST">
        <select name="type" id="type">
            <option value="">Pilih Input</option>
            <option value="siswa">Siswa</option>
            <option value="guru">Guru</option>
            </select>
        <div id="siswa">
            <h2>Data Siswa</h2>
            <input type="text" name="nisn" id="nisn" placeholder="NISN" required>
            <input type="text" name="nis" id="nis" placeholder="NIS" required>
            <input type="text" name="kelas" id="kelas" placeholder="Kelas" required>
        </div>
        <div id="guru">
            <h2>Data Guru</h2>
            <input type="text" name="nuptk" id="nuptk" placeholder="NUPTK" required>
            <input type="text" name="nip" id="nip" placeholder="NIP" required>
            <input type="text" name="mapel" id="mapel" placeholder="Mata Pelajaran" required>
            <input type="text" name="kelas_guru" id="kelas_guru" placeholder="Kelas" required>
        </div>
        <input type="text" name="name" id="name" placeholder="Name Panjang" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="text" name="no_hp" id="no_hp" placeholder="No Telp" required>
        <input type="text" name="alamat" id="alamat" placeholder="Alamat" required>
        <?php
        if(isset($_GET['error'])) {
            echo '<p> class="error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>
        <button type="submit">Daftar</button>
    </form>
    <p><a href="login.php">Login</a></p>
    
    <script>
        document.getElementById('type').addEventListener('change', function() {
            var tipe = this.value
            var siswa = document.getElementById('siswa')
            var guru = document.getElementById('guru')
            if (tipe === 'siswa') {
                siswa.style.display = 'block'
                guru.style.display = 'none'
            } else if (tipe === 'guru') {
                siswa.style.display = 'none'
                guru.style.display = 'block'
            } else {
                siswa.style.display = 'none'
                guru.style.display = 'none'                
            }
        });
    </script>
</body>
</html>