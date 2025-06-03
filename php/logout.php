<?php
//çıkış yap sayfam
session_start();
session_unset(); // Oturumdaki tüm değişkenleri temizler
session_destroy(); // Oturumu sonlandırır
header("Location: /El-Emek/index.php"); // Ana sayfaya yönlendir
exit();
?>
