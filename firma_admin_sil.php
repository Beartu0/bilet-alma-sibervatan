<?php
include 'db.php';

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: firma_admin_yonetimi.php'); exit(); }

$admin_id_to_delete = $_GET['id'];

try {
    // Sadece 'Firma Admin' rolündeki kullanıcıları silebildiğimizden emin olalım.
    // Bu, yanlışlıkla bir Süper Admin'i silmeyi önler.
    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'Firma Admin'");
    $stmt->execute([$admin_id_to_delete]);

    echo "Firma Admin kullanıcısı başarıyla silindi. Yönlendiriliyorsunuz...";
    header("refresh:2;url=firma_admin_yonetimi.php");

} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>