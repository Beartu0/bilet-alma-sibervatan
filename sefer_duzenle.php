<?php
include 'db.php';
include 'backend_header.php';
// Güvenlik Kontrolleri
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: firma_panel.php'); exit(); }

$trip_id = $_GET['id'];
$company_id = $_SESSION['user_company_id'];

// Düzenlenecek seferin bilgilerini, bu firmaya ait olup olmadığını kontrol ederek çek.
$stmt = $db->prepare("SELECT * FROM trips WHERE id = ? AND company_id = ?");
$stmt->execute([$trip_id, $company_id]);
$sefer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sefer) {
    die("Böyle bir sefer bulunamadı veya düzenleme yetkiniz yok.");
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Seferi Düzenle</title>
    <style> body { font-family: sans-serif; } .container { max-width: 500px; margin: auto; padding: 20px; } input, button { width: 100%; padding: 10px; margin-bottom: 10px; } </style>
</head>
<body>
<div class="container">
    <h2>Seferi Düzenle</h2>
    <form action="sefer_duzenle_isle.php" method="POST">
        <input type="hidden" name="trip_id" value="<?php echo $sefer['id']; ?>">
        
        <label for="departure_location">Kalkış Yeri:</label>
        <input type="text" name="departure_location" value="<?php echo htmlspecialchars($sefer['departure_location']); ?>" required>

        <label for="arrival_location">Varış Yeri:</label>
        <input type="text" name="arrival_location" value="<?php echo htmlspecialchars($sefer['arrival_location']); ?>" required>

        <label for="departure_time">Kalkış Zamanı:</label>
        <input type="datetime-local" name="departure_time" value="<?php echo date('Y-m-d\TH:i', strtotime($sefer['departure_time'])); ?>" required>

        <label for="arrival_time">Tahmini Varış Zamanı:</label>
        <input type="datetime-local" name="arrival_time" value="<?php echo date('Y-m-d\TH:i', strtotime($sefer['arrival_time'])); ?>" required>

        <label for="price">Fiyat (TL):</label>
        <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($sefer['price']); ?>" required>

        <label for="seat_count">Toplam Koltuk Sayısı:</label>
        <input type="number" name="seat_count" value="<?php echo htmlspecialchars($sefer['seat_count']); ?>" required>

        <button type="submit">Değişiklikleri Kaydet</button>
    </form>
    <a href="firma_panel.php">Panele Geri Dön</a>
</div>
</body>
</html>