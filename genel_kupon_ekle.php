<?php include 'db.php';
include 'backend_header.php';
 if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); } ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Genel Kupon Ekle</title>
    <style> body { font-family: sans-serif; } .container { max-width: 500px; margin: auto; padding: 20px; } input, button { width: 100%; padding: 10px; margin-bottom: 10px; } </style>
</head>
<body>
<div class="container">
    <h2>Yeni Genel Kupon Ekle</h2>
    <form action="genel_kupon_ekle_isle.php" method="POST">
        <label for="code">Kupon Kodu:</label> <input type="text" name="code" required>
        <label for="discount_rate">İndirim Oranı (%):</label> <input type="number" name="discount_rate" required>
        <label for="usage_limit">Kullanım Limiti:</label> <input type="number" name="usage_limit" required>
        <label for="expiration_date">Son Kullanma Tarihi:</label> <input type="date" name="expiration_date" required>
        <button type="submit">Kuponu Ekle</button>
    </form>
    <a href="genel_kupon_yonetimi.php">Geri Dön</a>
</div>
</body>
</html>