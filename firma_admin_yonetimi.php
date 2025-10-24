<?php
include 'db.php';
include 'backend_header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
$stmt = $db->query("SELECT users.*, companies.name AS company_name FROM users LEFT JOIN companies ON users.company_id = companies.id WHERE users.role = 'Firma Admin'");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center"><h1>Firma Admin Yönetimi</h1><a href="admin_panel.php" class="btn btn-secondary">Admin Paneline Dön</a></div>
    <div class="card-body">
        <a href="firma_admin_ekle.php" class="btn btn-success mb-3">+ Yeni Firma Admin Ekle</a>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark"><tr><th>Ad Soyad</th><th>E-posta</th><th>Atandığı Firma</th><th class="text-end">İşlemler</th></tr></thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($admin['company_name'] ?? 'Atanmamış'); ?></span></td>
                            <td class="text-end">
                                <a href="firma_admin_duzenle.php?id=<?php echo $admin['id']; ?>" class="btn btn-warning btn-sm">Düzenle</a>
                                <a href="firma_admin_sil.php?id=<?php echo $admin['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>