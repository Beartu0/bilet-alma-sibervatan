<?php
include 'db.php';
include 'backend_header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }

// Sadece company_id'si NULL olan genel kuponları çek
$kuponlar = $db->query("SELECT * FROM coupons WHERE company_id IS NULL ORDER BY expiration_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Genel Kupon Yönetimi</title>
    <style> body { font-family: sans-serif; } .container { max-width: 900px; margin: auto; padding: 20px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } .actions a { margin-right: 10px; } </style>
</head>
<body>
<div class="container">
    <a href="admin_panel.php"><< Admin Paneline Dön</a>
    <h1>Genel Kupon Yönetimi</h1>
    <hr>
    <p><a href="genel_kupon_ekle.php" style="background-color: green; color: white; padding: 10px; text-decoration: none; border-radius: 5px;">+ Yeni Genel Kupon Ekle</a></p>
    <br>
    <table>
        <thead> <tr> <th>Kupon Kodu</th> <th>İndirim Oranı (%)</th> <th>Kullanım Limiti</th> <th>Son Kullanma Tarihi</th> <th>İşlemler</th> </tr> </thead>
        <tbody>
            <?php foreach ($kuponlar as $kupon): ?>
                <tr>
                    <td><?php echo htmlspecialchars($kupon['code']); ?></td>
                    <td>% <?php echo htmlspecialchars($kupon['discount_rate']); ?></td>
                    <td><?php echo htmlspecialchars($kupon['usage_limit']); ?></td>
                    <td><?php echo date('d.m.Y', strtotime($kupon['expiration_date'])); ?></td>
                    <td class="actions">
                        <a href="genel_kupon_duzenle.php?id=<?php echo $kupon['id']; ?>">Düzenle</a>
                        <a href="genel_kupon_sil.php?id=<?php echo $kupon['id']; ?>" style="color:red;" onclick="return confirm('Bu kuponu silmek istediğinizden emin misiniz?');">Sil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>