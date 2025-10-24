<?php
include 'db.php';

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') { die("Bu işlemi yapma yetkiniz yok."); }
if (!isset($_POST['coupon_id'])) { header('Location: kupon_yonetimi.php'); exit(); }

// Formdan gelen verileri al
$coupon_id = $_POST['coupon_id'];
$company_id = $_SESSION['user_company_id'];
$code = $_POST['code'];
$discount_rate = $_POST['discount_rate'];
$usage_limit = $_POST['usage_limit'];
$expiration_date = $_POST['expiration_date'];

try {
    // GÜNCELLEMEDEN ÖNCE KONTROL ET
    $stmt_check = $db->prepare("SELECT id FROM coupons WHERE id = ? AND company_id = ?");
    $stmt_check->execute([$coupon_id, $company_id]);

    if ($stmt_check->rowCount() > 0) {
        $sql = "UPDATE coupons SET code = ?, discount_rate = ?, usage_limit = ?, expiration_date = ? WHERE id = ?";
        $stmt_update = $db->prepare($sql);
        $stmt_update->execute([$code, $discount_rate, $usage_limit, $expiration_date, $coupon_id]);

        echo "Kupon başarıyla güncellendi. Yönlendiriliyorsunuz...";
        header("refresh:2;url=kupon_yonetimi.php");
    } else {
        die("Güncelleme yetkiniz olmayan bir kuponu düzenlemeye çalıştınız.");
    }
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        die("Hata: Bu kupon kodu daha önce kullanılmış. Lütfen farklı bir kod deneyin.");
    }
    die("Hata: " . $e->getMessage());
}
?>