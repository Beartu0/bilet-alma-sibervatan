<?php
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }

$code = $_POST['code']; $discount_rate = $_POST['discount_rate']; $usage_limit = $_POST['usage_limit']; $expiration_date = $_POST['expiration_date'];

try {
    // Genel kupon eklerken company_id'yi NULL olarak ayarlıyoruz.
    $stmt = $db->prepare("INSERT INTO coupons (code, discount_rate, usage_limit, expiration_date, company_id) VALUES (?, ?, ?, ?, NULL)");
    $stmt->execute([$code, $discount_rate, $usage_limit, $expiration_date]);
    header("Location: genel_kupon_yonetimi.php");
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { die("Hata: Bu kupon kodu daha önce kullanılmış."); }
    die("Hata: " . $e->getMessage());
}
?>