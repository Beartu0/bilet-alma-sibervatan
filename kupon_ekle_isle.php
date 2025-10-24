<?php
include 'db.php';

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') {
    die("Bu işlemi yapma yetkiniz yok.");
}

// Formdan gelen verileri al
$code = $_POST['code'];
$discount_rate = $_POST['discount_rate'];
$usage_limit = $_POST['usage_limit'];
$expiration_date = $_POST['expiration_date'];
$company_id = $_SESSION['user_company_id']; // Kuponu bu firmaya ata

try {
    $stmt = $db->prepare("INSERT INTO coupons (code, discount_rate, usage_limit, expiration_date, company_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$code, $discount_rate, $usage_limit, $expiration_date, $company_id]);

    echo "Yeni kupon başarıyla eklendi. Yönlendiriliyorsunuz...";
    header("refresh:2;url=kupon_yonetimi.php");

} catch (PDOException $e) {
    // Eğer kupon kodu daha önce kullanılmışsa (UNIQUE kısıtlaması) hata verir.
    if ($e->getCode() == 23000) {
        die("Hata: Bu kupon kodu daha önce kullanılmış. Lütfen farklı bir kod deneyin.");
    }
    die("Hata: " . $e->getMessage());
}
?>