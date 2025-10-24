<?php
include 'db.php';

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') {
    die("Bu işlemi yapma yetkiniz yok.");
}

// Formdan gelen verileri al
$departure_location = $_POST['departure_location'];
$arrival_location = $_POST['arrival_location'];
$departure_time = $_POST['departure_time'];
$arrival_time = $_POST['arrival_time'];
$price = $_POST['price'];
$seat_count = $_POST['seat_count'];

// Adminin firma ID'sini al (Session'a eklemek daha iyi ama şimdilik DB'den alıyoruz)
$stmt_user = $db->prepare("SELECT company_id FROM users WHERE id = ?");
$stmt_user->execute([$_SESSION['user_id']]);
$company_id = $stmt_user->fetchColumn();

try {
    $stmt = $db->prepare("INSERT INTO trips (company_id, departure_location, arrival_location, departure_time, arrival_time, price, seat_count) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$company_id, $departure_location, $arrival_location, $departure_time, $arrival_time, $price, $seat_count]);

    echo "Yeni sefer başarıyla eklendi. Panele yönlendiriliyorsunuz...";
    header("refresh:2;url=firma_panel.php");

} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>