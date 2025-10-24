<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$coupon_code = $data['coupon_code'] ?? '';
$trip_id = $data['trip_id'] ?? 0;

if (empty($coupon_code) || empty($trip_id)) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgi.']);
    exit();
}

try {
    // 1. Kupon var mı ve süresi geçmiş mi?
    $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND expiration_date >= date('now')");
    $stmt->execute([$coupon_code]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$coupon) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz veya süresi dolmuş kupon kodu.']);
        exit();
    }

    // 2. Kupon kullanım limitine ulaşmış mı? (Bu kontrol için veritabanı şemasını değiştirmemiz gerekiyor)
    // Şimdilik bu adımı atlıyoruz, ama gerçek bir projede bilet tablosunda coupon_id tutulmalı ve sayılmalı.

    // 3. Kupon bu firma için geçerli mi?
    $stmt_trip = $db->prepare("SELECT company_id FROM trips WHERE id = ?");
    $stmt_trip->execute([$trip_id]);
    $trip_company_id = $stmt_trip->fetchColumn();

    if ($coupon['company_id'] !== null && $coupon['company_id'] != $trip_company_id) {
        echo json_encode(['success' => false, 'message' => 'Bu kupon bu firma için geçerli değil.']);
        exit();
    }

    // Her şey yolundaysa
    echo json_encode(['success' => true, 'message' => 'Kupon başarıyla uygulandı!', 'rate' => $coupon['discount_rate']]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası.']);
}
?>