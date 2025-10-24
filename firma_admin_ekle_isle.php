<?php
include 'db.php';
// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }

// Formdan verileri al
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$company_id = $_POST['company_id'];

// Şifreyi hash'le
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Yeni kullanıcıyı 'Firma Admin' rolü ve seçilen firma ID'si ile veritabanına ekle
    $stmt = $db->prepare("INSERT INTO users (fullname, email, password, role, company_id) VALUES (?, ?, ?, 'Firma Admin', ?)");
    $stmt->execute([$fullname, $email, $hashed_password, $company_id]);

    header("Location: firma_admin_yonetimi.php");
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        die("Hata: Bu e-posta adresi zaten kayıtlı.");
    }
    die("Hata: " . $e->getMessage());
}
?> 