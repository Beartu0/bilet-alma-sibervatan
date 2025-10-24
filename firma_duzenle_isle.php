<?php
include 'db.php';

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }
if (!isset($_POST['company_id']) || !isset($_POST['name'])) { header('Location: firma_yonetimi.php'); exit(); }

$company_id = $_POST['company_id'];
$name = $_POST['name'];

try {
    $stmt = $db->prepare("UPDATE companies SET name = ? WHERE id = ?");
    $stmt->execute([$name, $company_id]);

    echo "Firma başarıyla güncellendi. Yönlendiriliyorsunuz...";
    header("refresh:2;url=firma_yonetimi.php");
} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>