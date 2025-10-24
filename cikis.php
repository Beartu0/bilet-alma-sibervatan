<?php
session_start(); // Oturumu başlat
session_unset(); // Tüm oturum değişkenlerini sil
session_destroy(); // Oturumu sonlandır

// Kullanıcıyı ana sayfaya yönlendir
header("Location: index.php");
exit();
?>