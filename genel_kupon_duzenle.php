<?php
include 'db.php';
include 'backend_header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: genel_kupon_yonetimi.php'); exit(); }
$stmt = $db->prepare("SELECT * FROM coupons WHERE id = ? AND company_id IS NULL");
$stmt->execute([$_GET['id']]);
$kupon = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$kupon) { die("Böyle bir genel kupon bulunamadı."); }
?>
<!DOCTYPE html>
<html lang="tr"> <head> <meta charset="UTF-8"> <title>Genel Kuponu Düzenle</title> <style> body { font-family: sans-serif; } .container { max-width: 500px; margin: auto; padding: 20px; } input, button { width: 100%; padding: 10px; margin-bottom: 10px; } </style> </head>
<body>
<div class="container">
    <h2>Genel Kuponu Düzenle</h2>
    <form action="genel_kupon_duzenle_isle.php" method="POST">
        <input type="hidden" name="coupon_id" value="<?php echo $kupon['id']; ?>">
        <label for="code">Kupon Kodu:</label> <input type="text" name="code" value="<?php echo htmlspecialchars($kupon['code']); ?>" required>
        <label for="discount_rate">İndirim Oranı (%):</label> <input type="number" name="discount_rate" value="<?php echo htmlspecialchars($kupon['discount_rate']); ?>" required>
        <label for="usage_limit">Kullanım Limiti:</label> <input type="number" name="usage_limit" value="<?php echo htmlspecialchars($kupon['usage_limit']); ?>" required>
        <label for="expiration_date">Son Kullanma Tarihi:</label> <input type="date" name="expiration_date" value="<?php echo htmlspecialchars($kupon['expiration_date']); ?>" required>
        <button type="submit">Değişiklikleri Kaydet</button>
    </form>
    <a href="genel_kupon_yonetimi.php">Geri Dön</a>
</div>
</body>
</html>