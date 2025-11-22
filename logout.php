<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
// Tampilkan popup konfirmasi
Swal.fire({
    title: "Yakin ingin logout?",
    text: "Anda akan keluar dari sistem.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, Logout",
    cancelButtonText: "Batal",
    reverseButtons: true
}).then((result) => {

    if (result.isConfirmed) {
        // Jika user pilih logout → jalankan PHP logout via redirect
        window.location.href = "logoutProcess.php";

    } else {
        // Jika batal → kembali ke halaman sebelumnya
        window.history.back();
    }

});
</script>

</body>
</html>
