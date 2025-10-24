<?php
include 'db.php';
include 'backend_header.php';
// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }

// Dropdown için tüm firmaları çekiyoruz
$firmalar = $db->query("SELECT * FROM companies ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Firma Admin Ekle</title>
    <style> body { font-family: sans-serif; } .container { max-width: 500px; margin: auto; padding: 20px; } input, select, button { width: 100%; padding: 10px; margin-bottom: 10px; } </style>
</head>
<body>
<div class="container">
    <h2>Yeni Firma Admin Ekle</h2>
    <form action="firma_admin_ekle_isle.php" method="POST">
        <label for="fullname">Ad Soyad:</label>
        <input type="text" name="fullname" required>
        
        <label for="email">E-posta:</label>
        <input type="email" name="email" required>

        <label for="password">Şifre:</label>
        <input type="password" name="password" required>
        
        <label for="company_id">Atanacak Firma:</label>
        <select name="company_id" required>
            <option value="">-- Firma Seçiniz --</option>
            <?php foreach ($firmalar as $firma): ?>
                <option value="<?php echo $firma['id']; ?>"><?php echo htmlspecialchars($firma['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Firma Admini Ekle</button>
    </form>
    <a href="firma_admin_yonetimi.php">Geri Dön</a>
</div>
</body>
</html>