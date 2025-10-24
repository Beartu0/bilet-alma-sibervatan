<?php
include 'db.php';

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') {
    die("Bu işlemi yapma yetkiniz yok.");
}
if (!isset($_POST['trip_id'])) {
    header('Location: firma_panel.php');
    exit();
}

// Formdan gelen verileri al
$trip_id = $_POST['trip_id'];
$company_id = $_SESSION['user_company_id'];
$departure_location = $_POST['departure_location'];
$arrival_location = $_POST['arrival_location'];
$departure_time = $_POST['departure_time'];
$arrival_time = $_POST['arrival_time'];
$price = $_POST['price'];
$seat_count = $_POST['seat_count'];

try {
    // GÜNCELLEMEDEN ÖNCE KONTROL ET: Bu sefer, gerçekten bu adminin firmasına mı ait?
    $stmt_check = $db->prepare("SELECT id FROM trips WHERE id = ? AND company_id = ?");
    $stmt_check->execute([$trip_id, $company_id]);

    if ($stmt_check->rowCount() > 0) {
        // Kontrol başarılı, sefer bu firmaya ait. Şimdi güncelleyebiliriz.
        $sql = "UPDATE trips SET 
                    departure_location = ?, 
                    arrival_location = ?, 
                    departure_time = ?, 
                    arrival_time = ?, 
                    price = ?, 
                    seat_count = ? 
                WHERE id = ?";
        $stmt_update = $db->prepare($sql);
        $stmt_update->execute([$departure_location, $arrival_location, $departure_time, $arrival_time, $price, $seat_count, $trip_id]);

        echo "Sefer başarıyla güncellendi. Panele yönlendiriliyorsunuz...";
        header("refresh:2;url=firma_panel.php");
    } else {
        die("Güncelleme yetkiniz olmayan bir seferi düzenlemeye çalıştınız.");
    }
} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>