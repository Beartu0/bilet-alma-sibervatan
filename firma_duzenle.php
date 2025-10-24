<?php
include 'db.php';
include 'backend_header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
if (!isset($_GET['id'])) { header('Location: firma_yonetimi.php'); exit(); }
$stmt = $db->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$_GET['id']]);
$firma = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$firma) { die("Böyle bir firma bulunamadı."); }
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h2>Firmayı Düzenle</h2></div>
            <div class="card-body">
                <form action="firma_duzenle_isle.php" method="POST">
                    <input type="hidden" name="company_id" value="<?php echo $firma['id']; ?>">
                    <div class="mb-3"><label class="form-label">Firma Adı:</label><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($firma['name']); ?>" required></div>
                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                    <a href="firma_yonetimi.php" class="btn btn-secondary">Geri Dön</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>