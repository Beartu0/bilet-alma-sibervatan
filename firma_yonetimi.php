<?php
include 'db.php';
include 'backend_header.php'; // Backend header'ı kullanıyoruz

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
$firmalar = $db->query("SELECT * FROM companies ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1>Firma Yönetimi</h1>
        <a href="admin_panel.php" class="btn btn-secondary">Admin Paneline Dön</a>
    </div>
    <div class="card-body">
        <a href="firma_ekle.php" class="btn btn-success mb-3">+ Yeni Firma Ekle</a>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Firma Adı</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($firmalar as $firma): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($firma['name']); ?></td>
                        <td class="text-end">
                            <a href="firma_duzenle.php?id=<?php echo $firma['id']; ?>" class="btn btn-warning btn-sm">Düzenle</a>
                            <a href="firma_sil.php?id=<?php echo $firma['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu firmayı silmek istediğinizden emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>