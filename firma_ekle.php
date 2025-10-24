<?php 
include 'db.php';
include 'backend_header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h2>Yeni Firma Ekle</h2></div>
            <div class="card-body">
                <form action="firma_ekle_isle.php" method="POST">
                    <div class="mb-3"><label class="form-label">Firma Adı:</label><input type="text" name="name" class="form-control" required></div>
                    <button type="submit" class="btn btn-primary">Firmayı Ekle</button>
                    <a href="firma_yonetimi.php" class="btn btn-secondary">Geri Dön</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>