<?php
$host='localhost';
$username='root';
$password='';
$db='db_perpustakaan_tadikapertiwi';

$conn = new mysqli($host, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection Failed:" . $conn->connect_error);
}
echo "Berhasil terhubung ke database!";
?>