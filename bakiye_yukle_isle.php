<?php
include 'db.php';


if (!isset($_SESSION['user_id']) || !isset($_POST['amount'])) {
    die("Yetkisiz erişim veya eksik bilgi.");
}

$user_id = $_SESSION['user_id'];
$amount_to_add = (float)$_POST['amount']; // Gelen veriyi ondalıklı sayıya çevir

if ($amount_to_add <= 0) {
    die("Lütfen geçerli bir tutar giriniz.");
}

try {
    $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$amount_to_add, $user_id]);

    // Session'daki bakiyeyi de anında güncelle
    $_SESSION['user_balance'] += $amount_to_add;

    echo "Bakiye başarıyla yüklendi! Yönlendiriliyorsunuz...";
    header("refresh:2;url=index.php");

} catch (PDOException $e) {
    die("Bakiye yüklenirken bir hata oluştu: " . $e->getMessage());
}
?>