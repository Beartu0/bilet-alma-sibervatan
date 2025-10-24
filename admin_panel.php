<?php 
include 'db.php';
include 'backend_header.php'; // Backend header'ı kullanıyoruz

// Güvenlik Kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Admin') {
    echo "<div class='alert alert-danger'>Bu sayfaya erişim yetkiniz yok.</div>";
    include 'footer.php';
    exit();
}
?>

<div class="card">
    <div class="card-header">
        <h1>Süper Admin Paneli</h1>
    </div>
    <div class="card-body">
        <p class="lead">Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>. Lütfen yapmak istediğiniz işlemi seçin.</p>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Firma Yönetimi</h5>
                        <p class="card-text">Yeni otobüs firmaları ekleyin, mevcutları düzenleyin veya silin.</p>
                        <a href="firma_yonetimi.php" class="btn btn-primary">Firmaları Yönet</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Firma Admin Yönetimi</h5>
                        <p class="card-text">Firma yetkilileri oluşturun ve firmalara atayın.</p>
                        <a href="firma_admin_yonetimi.php" class="btn btn-primary">Firma Adminlerini Yönet</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Genel Kupon Yönetimi</h5>
                        <p class="card-text">Tüm firmalarda geçerli indirim kuponları oluşturun.</p>
                        <a href="genel_kupon_yonetimi.php" class="btn btn-primary">Genel Kuponları Yönet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>