<?php
include 'db.php';
include 'backend_header.php'; // Arka yüz header'ını kullanıyoruz

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') {
    echo "<div class='alert alert-danger'>Bu sayfaya erişim yetkiniz yok.</div>";
    include 'footer.php';
    exit();
}
$stmt = $db->prepare("SELECT * FROM trips WHERE company_id = ? ORDER BY departure_time DESC");
$stmt->execute([$_SESSION['user_company_id']]);
$seferler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h1>Firma Yönetim Paneli</h1>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="sefer_ekle.php" class="btn btn-success">+ Yeni Sefer Ekle</a>
            <a href="kupon_yonetimi.php" class="btn btn-warning">Kuponları Yönet</a>
        </div>
        <h2 class="h4">Seferleriniz</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Kalkış</th>
                        <th>Varış</th>
                        <th>Tarih & Saat</th>
                        <th>Fiyat</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($seferler)): ?>
                        <tr><td colspan="5" class="text-center">Henüz hiç sefer eklememişsiniz.</td></tr>
                    <?php else: ?>
                        <?php foreach ($seferler as $sefer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sefer['departure_location']); ?></td>
                                <td><?php echo htmlspecialchars($sefer['arrival_location']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($sefer['departure_time'])); ?></td>
                                <td><?php echo htmlspecialchars($sefer['price']); ?> TL</td>
                                <td class="text-end">
                                    <a href="sefer_duzenle.php?id=<?php echo $sefer['id']; ?>" class="btn btn-warning btn-sm">Düzenle</a>
                                    <a href="sefer_sil.php?id=<?php echo $sefer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu seferi kalıcı olarak silmek istediğinizden emin misiniz?');">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>