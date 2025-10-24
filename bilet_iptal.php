<?php
include 'db.php';

// ... Güvenlik kontrolleri aynı ...
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) { die("Yetkisiz erişim."); }
$user_id = $_SESSION['user_id']; $ticket_id = $_GET['id'];

try {
    // BİLET BİLGİLERİNİ ÇEKERKEN ARTIK 'price_paid' SÜTUNUNU ALIYORUZ
    $stmt = $db->prepare("SELECT tickets.id, tickets.price_paid, trips.departure_time FROM tickets JOIN trips ON tickets.trip_id = trips.id WHERE tickets.id = ? AND tickets.user_id = ? AND tickets.status = 'ACTIVE'");
    $stmt->execute([$ticket_id, $user_id]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) { die("Böyle bir bilet bulunamadı veya iptal etme yetkiniz yok."); }

    // ... 1 saat kuralı kontrolü aynı ...
    $time_diff_seconds = strtotime($ticket['departure_time']) - time();
    if ($time_diff_seconds < 3600) { die("Kalkışa 1 saatten az kaldığı için bilet iptal edilemez."); }

    // VERİTABANI İŞLEMLERİ
    $db->beginTransaction();
    
    // İADE EDİLECEK TUTAR ARTIK 'price_paid' SÜTUNUNDAN GELİYOR
    $refund_amount = $ticket['price_paid'];
    $stmt_refund = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt_refund->execute([$refund_amount, $user_id]);

    $stmt_cancel = $db->prepare("UPDATE tickets SET status = 'CANCELLED' WHERE id = ?");
    $stmt_cancel->execute([$ticket_id]);
    $db->commit();
    
    $_SESSION['user_balance'] += $refund_amount;

    echo "Biletiniz başarıyla iptal edildi. Ücret iadesi yapıldı. Yönlendiriliyorsunuz...";
    header("refresh:3;url=biletlerim.php");
} catch (Exception $e) {
    if ($db->inTransaction()) { $db->rollBack(); }
    die("İşlem sırasında bir hata oluştu: " . $e->getMessage());
}
?>