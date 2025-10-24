<?php 
include 'db.php'; // db.php'yi header'dan önce çağırıyoruz ki session'dan önce çalışsın
include 'frontend_header.php'; 
// Arama ve listeleme PHP kodları (öncekiyle aynı)
$kalkis = $_GET['kalkis'] ?? '';
$varis = $_GET['varis'] ?? '';
$sql = "SELECT trips.*, companies.name AS company_name FROM trips JOIN companies ON trips.company_id = companies.id";
$params = [];
if (!empty($kalkis) && !empty($varis)) {
    $sql .= " WHERE departure_location = ? AND arrival_location = ?";
    $params[] = $kalkis;
    $params[] = $varis;
}
$sql .= " ORDER BY departure_time ASC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$trips = $stmt->fetchAll(PDO::FETCH_ASSOC);
$konumlar_stmt = $db->query("SELECT DISTINCT departure_location FROM trips UNION SELECT DISTINCT arrival_location FROM trips");
$konumlar = $konumlar_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php if (!isset($_SESSION['user_id'])): ?>
<div class="alert alert-info">Bilet almak için lütfen giriş yapın.</div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <h3>Sefer Ara</h3>
    </div>
    <div class="card-body">
        <form action="index.php" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="kalkis" class="form-label">Nereden:</label>
                <select name="kalkis" id="kalkis" class="form-select" required>
                    <option value="">Seçiniz...</option>
                    <?php foreach($konumlar as $konum): ?>
                        <option value="<?php echo htmlspecialchars($konum); ?>" <?php if($kalkis == $konum) echo 'selected'; ?>><?php echo htmlspecialchars($konum); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="varis" class="form-label">Nereye:</label>
                <select name="varis" id="varis" class="form-select" required>
                    <option value="">Seçiniz...</option>
                    <?php foreach($konumlar as $konum): ?>
                        <option value="<?php echo htmlspecialchars($konum); ?>" <?php if($varis == $konum) echo 'selected'; ?>><?php echo htmlspecialchars($konum); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Sefer Ara</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">Tümünü Göster</a>
            </div>
        </form>
    </div>
</div>

<h3><?php echo (!empty($kalkis) && !empty($varis)) ? 'Arama Sonuçları' : 'Tüm Seferler'; ?></h3>

<div id="trip-list">
    <?php if (empty($trips)): ?>
        <div class="alert alert-warning">Bu kriterlere uygun sefer bulunamadı.</div>
    <?php else: ?>
        <?php foreach ($trips as $trip): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title"><?php echo htmlspecialchars($trip['departure_location']); ?> -> <?php echo htmlspecialchars($trip['arrival_location']); ?></h5>
                            <p class="card-text mb-1">
                                <strong>Firma:</strong> <?php echo htmlspecialchars($trip['company_name']); ?>
                            </p>
                            <p class="card-text">
                                <strong>Tarih & Saat:</strong> <?php echo date('d.m.Y H:i', strtotime($trip['departure_time'])); ?>
                            </p>
                        </div>
                        <div class="col-md-2 text-center">
                            <h4 class="text-success"><?php echo htmlspecialchars($trip['price']); ?> TL</h4>
                        </div>
                        <div class="col-md-2">
                             <a href="sefer_detay.php?id=<?php echo $trip['id']; ?>" class="btn btn-success w-100">Detay / Bilet Al</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>