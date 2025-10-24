<?php
include 'db.php';
include 'frontend_header.php'; 
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Bu sayfayı görmek için giriş yapmalısınız.</div>";
    include 'footer.php';
    exit();
}
$user_id = $_SESSION['user_id'];

// Veritabanı sorgusu (öncekiyle aynı)
$stmt = $db->prepare("SELECT tickets.id AS ticket_id, tickets.seat_number, trips.departure_location, trips.arrival_location, trips.departure_time, companies.name AS company_name FROM tickets JOIN trips ON tickets.trip_id = trips.id JOIN companies ON trips.company_id = companies.id WHERE tickets.user_id = ? AND tickets.status = 'ACTIVE' ORDER BY trips.departure_time ASC");
$stmt->execute([$user_id]);
$biletler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="mb-4">Aktif Biletlerim</h1>

<?php if (empty($biletler)): ?>
    <div class="alert alert-info">Hiç aktif biletiniz bulunmamaktadır.</div>
<?php else: ?>
    <?php foreach ($biletler as $bilet): ?>
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1"><?php echo htmlspecialchars($bilet['departure_location']); ?> -> <?php echo htmlspecialchars($bilet['arrival_location']); ?></h5>
                        <p class="card-text text-muted mb-1">
                            <strong>Firma:</strong> <?php echo htmlspecialchars($bilet['company_name']); ?> | 
                            <strong>Koltuk No:</strong> <?php echo htmlspecialchars($bilet['seat_number']); ?>
                        </p>
                        <p class="card-text">
                            <strong>Kalkış:</strong> <?php echo date('d.m.Y H:i', strtotime($bilet['departure_time'])); ?>
                        </p>
                    </div>
                    <div>
                        <a href="bilet_pdf.php?id=<?php echo $bilet['ticket_id']; ?>" target="_blank" class="btn btn-success me-2">PDF İndir</a>
                        <a href="bilet_iptal.php?id=<?php echo $bilet['ticket_id']; ?>" class="btn btn-danger" onclick="return confirm('Bu bileti iptal etmek istediğinizden emin misiniz?');">İptal Et</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'footer.php'; ?>