<?php
include 'db.php';
include 'backend_header.php';
// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') {
    die("Bu sayfaya erişim yetkiniz yok.");
}

$company_id = $_SESSION['user_company_id'];

// Sadece bu firmaya ait kuponları veritabanından çekiyoruz.
$stmt = $db->prepare("SELECT * FROM coupons WHERE company_id = ? ORDER BY expiration_date DESC");
$stmt->execute([$company_id]);
$kuponlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kupon Yönetimi</title>
    <style> /* firma_panel.php'deki stilleri kullanalım */
        body { font-family: sans-serif; } .container { max-width: 900px; margin: auto; padding: 20px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } .actions a { margin-right: 10px; }
    </style>
</head>
<body>
<div class="container">
    <a href="firma_panel.php"><< Sefer Yönetimine Dön</a>
    <h1>Kupon Yönetimi</h1>
    <p>Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>.</p>
    <hr>
    <h2>Firmanızın Kuponları</h2>
    <p><a href="kupon_ekle.php" style="background-color: green; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">+ Yeni Kupon Ekle</a></p>
    <br>
    <table>
        <thead>
            <tr>
                <th>Kupon Kodu</th>
                <th>İndirim Oranı (%)</th>
                <th>Kullanım Limiti</th>
                <th>Son Kullanma Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($kuponlar)): ?>
                <tr><td colspan="5">Firmanıza ait hiç kupon bulunmamaktadır.</td></tr>
            <?php else: ?>
                <?php foreach ($kuponlar as $kupon): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($kupon['code']); ?></td>
                        <td>% <?php echo htmlspecialchars($kupon['discount_rate']); ?></td>
                        <td><?php echo htmlspecialchars($kupon['usage_limit']); ?></td>
                        <td><?php echo date('d.m.Y', strtotime($kupon['expiration_date'])); ?></td>
                        <td class="actions">
    <a href="kupon_duzenle.php?id=<?php echo $kupon['id']; ?>">Düzenle</a>
    <a href="kupon_sil.php?id=<?php echo $kupon['id']; ?>" style="color:red;" onclick="return confirm('Bu kuponu kalıcı olarak silmek istediğinizden emin misiniz?');">Sil</a>
</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>