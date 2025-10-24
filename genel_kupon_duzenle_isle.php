<?php
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu işlemi yapma yetkiniz yok."); }
if (!isset($_POST['coupon_id'])) { header('Location: genel_kupon_yonetimi.php'); exit(); }

$coupon_id = $_POST['coupon_id']; $code = $_POST['code']; $discount_rate = $_POST['discount_rate']; $usage_limit = $_POST['usage_limit']; $expiration_date = $_POST['expiration_date'];

try {
    $stmt = $db->prepare("UPDATE coupons SET code = ?, discount_rate = ?, usage_limit = ?, expiration_date = ? WHERE id = ? AND company_id IS NULL");
    $stmt->execute([$code, $discount_rate, $usage_limit, $expiration_date, $coupon_id]);
    header("Location: genel_kupon_yonetimi.php");
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { die("Hata: Bu kupon kodu daha önce kullanılmış."); }
    die("Hata: " . $e->getMessage());
}
?>