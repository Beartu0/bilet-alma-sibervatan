<?php
include 'db.php';
// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }

// Form verilerini al
$admin_id = $_POST['admin_id'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$company_id = $_POST['company_id'];

try {
    // Şifre alanı doluysa, şifreyi güncelle. Boşsa, şifreye dokunma.
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET fullname = ?, email = ?, password = ?, company_id = ? WHERE id = ?");
        $stmt->execute([$fullname, $email, $hashed_password, $company_id, $admin_id]);
    } else {
        $stmt = $db->prepare("UPDATE users SET fullname = ?, email = ?, company_id = ? WHERE id = ?");
        $stmt->execute([$fullname, $email, $company_id, $admin_id]);
    }

    echo "Firma Admin bilgileri başarıyla güncellendi. Yönlendiriliyorsunuz...";
    header("refresh:2;url=firma_admin_yonetimi.php");

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        die("Hata: Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor.");
    }
    die("Hata: " . $e->getMessage());
}
?>