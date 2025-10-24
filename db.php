<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {

    $db = new PDO('sqlite:veritabani.sqlite');
    
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
        die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>