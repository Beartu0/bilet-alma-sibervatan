<?php
include 'db.php';
// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }

$name = $_POST['name'];

try {
    $stmt = $db->prepare("INSERT INTO companies (name) VALUES (?)");
    $stmt->execute([$name]);
    header("Location: firma_yonetimi.php");
} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>