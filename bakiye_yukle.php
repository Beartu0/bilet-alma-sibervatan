<?php 
include 'db.php';
include 'frontend_header.php'; 
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Bu sayfayı görmek için giriş yapmalısınız.</div>";
    include 'footer.php';
    exit();
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Bakiye Yükle</h3>
            </div>
            <div class="card-body">
                <form action="bakiye_yukle_isle.php" method="POST">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Yüklenecek Tutar (TL)</label>
                        <input type="number" class="form-control" name="amount" step="0.01" placeholder="Örn: 100" required>
                    </div>
                    <hr>
                    <p class="text-muted">Aşağıdaki alanlar test amaçlıdır, gerçek bilgi girmenize gerek yoktur.</p>
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Kart Numarası</label>
                        <input type="text" class="form-control" name="card_number" value="1111222233334444">
                    </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                             <label for="expiry_date" class="form-label">Son Kullanma Tarihi</label>
                             <input type="text" class="form-control" name="expiry_date" value="12/29">
                        </div>
                        <div class="col-md-6 mb-3">
                             <label for="cvc" class="form-label">CVC</label>
                             <input type="text" class="form-control" name="cvc" value="123">
                        </div>
                     </div>
                     <button type="submit" class="btn btn-success w-100">Yüklemeyi Onayla</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>