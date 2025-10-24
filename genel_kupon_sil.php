<?php
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: genel_kupon_yonetimi.php'); exit(); }

try {
    $stmt = $db->prepare("DELETE FROM coupons WHERE id = ? AND company_id IS NULL");
    $stmt->execute([$_GET['id']]);
    header("Location: genel_kupon_yonetimi.php");
} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>