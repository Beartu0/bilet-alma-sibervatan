<?php
include 'db.php';
include 'backend_header.php';
// Güvenlik Kontrolleri
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: firma_admin_yonetimi.php'); exit(); }

$admin_id = $_GET['id'];

// Düzenlenecek adminin bilgilerini çek
$stmt = $db->prepare("SELECT * FROM users WHERE id = ? AND role = 'Firma Admin'");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) { die("Böyle bir Firma Admin kullanıcısı bulunamadı."); }

// Dropdown için tüm firmaları çekiyoruz
$firmalar = $db->query("SELECT * FROM companies ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Firma Admini Düzenle</title>
    <style> body { font-family: sans-serif; } .container { max-width: 500px; margin: auto; padding: 20px; } input, select, button { width: 100%; padding: 10px; margin-bottom: 10px; } </style>
</head>
<body>
<div class="container">
    <h2>Firma Admini Düzenle</h2>
    <form action="firma_admin_duzenle_isle.php" method="POST">
        <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">

        <label for="fullname">Ad Soyad:</label>
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($admin['fullname']); ?>" required>
        
        <label for="email">E-posta:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>

        <label for="password">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın):</label>
        <input type="password" name="password">
        
        <label for="company_id">Atanacak Firma:</label>
        <select name="company_id" required>
            <option value="">-- Firma Seçiniz --</option>
            <?php foreach ($firmalar as $firma): ?>
                <option value="<?php echo $firma['id']; ?>" <?php if($admin['company_id'] == $firma['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($firma['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Değişiklikleri Kaydet</button>
    </form>
    <a href="firma_admin_yonetimi.php">Geri Dön</a>
</div>
</body>
</html>