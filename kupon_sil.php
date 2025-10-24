<?php
include 'db.php';

// Güvenlik Kontrolleri
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') { die("Bu işlemi yapma yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: kupon_yonetimi.php'); exit(); }

$coupon_id = $_GET['id'];
$company_id = $_SESSION['user_company_id'];

try {
    // SİLMEDEN ÖNCE KONTROL ET: Bu kupon, gerçekten bu adminin firmasına mı ait?
    $stmt_check = $db->prepare("SELECT id FROM coupons WHERE id = ? AND company_id = ?");
    $stmt_check->execute([$coupon_id, $company_id]);

    if ($stmt_check->rowCount() > 0) {
        // Kontrol başarılı, kupon bu firmaya ait. Şimdi silebiliriz.
        $stmt_delete = $db->prepare("DELETE FROM coupons WHERE id = ?");
        $stmt_delete->execute([$coupon_id]);

        echo "Kupon başarıyla silindi. Yönlendiriliyorsunuz...";
        header("refresh:2;url=kupon_yonetimi.php");
    } else {
        die("Silme yetkiniz olmayan bir kuponu silmeye çalıştınız.");
    }
} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>