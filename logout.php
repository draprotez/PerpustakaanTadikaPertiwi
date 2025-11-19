<?php
//logout.php
session_start();
$_SESSION = [];
session_unset();
session_destroy();

header("Location:index.php?success=Anda telah berhasil logout.");
exit();
?>