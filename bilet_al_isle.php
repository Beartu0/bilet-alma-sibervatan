<?php
include 'db.php';


if (!isset($_SESSION['user_id'])) { die("Bu işlemi yapmak için giriş yapmalısınız."); }
if (!isset($_POST['trip_id'], $_POST['seat_number'])) { die("Eksik bilgi gönderildi."); }

$user_id = $_SESSION['user_id'];
$trip_id = $_POST['trip_id'];
$seat_number = $_POST['seat_number'];
$coupon_code = $_POST['coupon_code'];
$coupon_id = null;
$discount_rate = 0;

try {
    // Önce seferin orijinal fiyatını alalım
    $stmt_trip = $db->prepare("SELECT price, company_id FROM trips WHERE id = ?");
    $stmt_trip->execute([$trip_id]);
    $trip = $stmt_trip->fetch(PDO::FETCH_ASSOC);
    $original_price = $trip['price'];

    // Eğer kupon kodu gönderilmişse, TEKRAR KONTROL ET
    if (!empty($coupon_code)) {
        $stmt_coupon = $db->prepare("SELECT * FROM coupons WHERE code = ? AND expiration_date >= date('now')");
        $stmt_coupon->execute([$coupon_code]);
        $coupon = $stmt_coupon->fetch(PDO::FETCH_ASSOC);
        if ($coupon && ($coupon['company_id'] === null || $coupon['company_id'] == $trip['company_id'])) {
            $discount_rate = $coupon['discount_rate'];
            $coupon_id = $coupon['id'];
        }
    }
    
    // Nihai fiyatı hesapla
    $final_price = $original_price * (1 - ($discount_rate / 100));

    // Bakiye kontrolünü NİHAİ FİYATA göre yap
    $stmt_user = $db->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt_user->execute([$user_id]);
    if ($stmt_user->fetchColumn() < $final_price) {
        die("Yetersiz bakiye! Bilet satın alınamadı.");
    }

    // Diğer kontroller (koltuk dolu mu vb.)
    $stmt_seat = $db->prepare("SELECT id FROM tickets WHERE trip_id = ? AND seat_number = ? AND status = 'ACTIVE'");
    $stmt_seat->execute([$trip_id, $seat_number]);
    if ($stmt_seat->fetch()) { die("Üzgünüz, bu koltuk başkası tarafından satın alındı."); }

    // Transaction
    $db->beginTransaction();
    $stmt_update_balance = $db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $stmt_update_balance->execute([$final_price, $user_id]);

    // Bileti yeni alanlarla birlikte kaydet
    $stmt_insert_ticket = $db->prepare("INSERT INTO tickets (user_id, trip_id, seat_number, price_paid, coupon_id) VALUES (?, ?, ?, ?, ?)");
    $stmt_insert_ticket->execute([$user_id, $trip_id, $seat_number, $final_price, $coupon_id]);
    
    $db->commit();
    $_SESSION['user_balance'] -= $final_price;

    echo "Biletiniz başarıyla satın alındı! Yönlendiriliyorsunuz...";
    header("refresh:3;url=biletlerim.php");

} catch (PDOException $e) {
    if ($db->inTransaction()) { $db->rollBack(); }
    die("İşlem sırasında bir hata oluştu: " . $e->getMessage());
}
?>