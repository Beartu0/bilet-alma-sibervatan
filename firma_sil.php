<?php
include 'db.php';

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: firma_yonetimi.php'); exit(); }

$company_id = $_GET['id'];

try {
    $stmt = $db->prepare("DELETE FROM companies WHERE id = ?");
    $stmt->execute([$company_id]);

    echo "Firma başarıyla silindi. Yönlendiriliyorsunuz...";
    header("refresh:2;url=firma_yonetimi.php");

} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>