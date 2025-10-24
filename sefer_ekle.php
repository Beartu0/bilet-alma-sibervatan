<?php 
include 'db.php';
include 'backend_header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') { die("Bu sayfaya erişim yetkiniz yok."); }
?>
<div class="card">
    <div class="card-header"><h2>Yeni Sefer Ekle</h2></div>
    <div class="card-body">
        <form action="sefer_ekle_isle.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Kalkış Yeri:</label><input type="text" name="departure_location" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Varış Yeri:</label><input type="text" name="arrival_location" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Kalkış Zamanı:</label><input type="datetime-local" name="departure_time" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Tahmini Varış Zamanı:</label><input type="datetime-local" name="arrival_time" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Fiyat (TL):</label><input type="number" name="price" step="0.01" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Toplam Koltuk Sayısı:</label><input type="number" name="seat_count" class="form-control" required></div>
            </div>
            <button type="submit" class="btn btn-primary">Seferi Ekle</button>
            <a href="firma_panel.php" class="btn btn-secondary">Panele Geri Dön</a>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>