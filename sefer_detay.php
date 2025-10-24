<?php 
include 'db.php';
include 'frontend_header.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header('Location: index.php'); exit(); }
$trip_id = $_GET['id'];
$stmt = $db->prepare("SELECT trips.*, companies.name AS company_name FROM trips JOIN companies ON trips.company_id = companies.id WHERE trips.id = ?");
$stmt->execute([$trip_id]);
$trip = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trip) { header('Location: index.php'); exit(); }
$stmt_sold = $db->prepare("SELECT seat_number FROM tickets WHERE trip_id = ? AND status = 'ACTIVE'");
$stmt_sold->execute([$trip_id]);
$sold_seats = $stmt_sold->fetchAll(PDO::FETCH_COLUMN);
?>

<style>
    /* ... Önceki koltuk stilleri aynı kalabilir ... */
    .seat-map { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; max-width: 300px; } .seat input[type="radio"] { display: none; } .seat-label { display: flex; justify-content: center; align-items: center; border: 1px solid #ccc; border-radius: 5px; height: 40px; cursor: pointer; } .seat-label.sold { background-color: #f8d7da; color: #842029; border-color: #f5c2c7; cursor: not-allowed; } .seat-label:not(.sold):hover { background-color: #e2e6ea; } .seat input[type="radio"]:checked + .seat-label { background-color: #198754; color: white; border-color: #146c43; }
</style>

<div class="card">
    <div class="card-header"><h2 class="h3">Sefer Detayları ve Bilet Alma</h2></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-7">
                <h4>Sefer Bilgileri</h4>
                <p><strong>Firma:</strong> <?php echo htmlspecialchars($trip['company_name']); ?></p>
                <p><strong>Güzergah:</strong> <?php echo htmlspecialchars($trip['departure_location']); ?> -> <?php echo htmlspecialchars($trip['arrival_location']); ?></p>
                <p><strong>Kalkış Zamanı:</strong> <?php echo date('d.m.Y H:i', strtotime($trip['departure_time'])); ?></p>
                <hr>
                <h4>Koltuk Seçimi</h4>
                <div class="seat-map">
                    <?php for ($i = 1; $i <= $trip['seat_count']; $i++): ?>
                        <?php $is_sold = in_array($i, $sold_seats); ?>
                        <div class="seat">
                            <input type="radio" name="seat_number_radio" class="seat_radio" id="seat-<?php echo $i; ?>" value="<?php echo $i; ?>" required <?php if ($is_sold) echo 'disabled'; ?>>
                            <label for="seat-<?php echo $i; ?>" class="seat-label <?php if ($is_sold) echo 'sold'; ?>"><?php echo $i; ?></label>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="col-md-5">
                <h4>Ödeme</h4>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="alert alert-warning">Bilet alabilmek için lütfen <a href="giris.php" class="alert-link">giriş yapınız</a>.</div>
                <?php else: ?>
                    <form action="bilet_al_isle.php" method="POST" id="purchaseForm">
                        <input type="hidden" name="trip_id" value="<?php echo $trip['id']; ?>">
                        <input type="hidden" name="seat_number" id="selected_seat" value="">
                        <input type="hidden" name="coupon_code" id="applied_coupon_code" value="">

                        <div class="input-group mb-3">
                            <input type="text" id="coupon_input" class="form-control" placeholder="İndirim Kuponu">
                            <button class="btn btn-outline-secondary" type="button" id="apply_coupon_btn">Uygula</button>
                        </div>
                        <div id="coupon_status" class="mb-3"></div>

                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between"><span>Normal Fiyat:</span> <span id="original_price"><?php echo $trip['price']; ?> TL</span></li>
                            <li class="list-group-item d-flex justify-content-between text-danger"><span>İndirim:</span> <span id="discount_amount">- 0.00 TL</span></li>
                            <li class="list-group-item d-flex justify-content-between active"><h4>Toplam Tutar:</h4> <h4 id="final_price"><?php echo $trip['price']; ?> TL</h4></li>
                        </ul>
                        
                        <button type="submit" class="btn btn-success mt-3 w-100" id="buy_button" disabled>Satın Al</button>
                        <div id="seat_alert" class="alert alert-warning mt-2 d-none">Lütfen bir koltuk seçin.</div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const originalPrice = <?php echo $trip['price']; ?>;
    let finalPrice = originalPrice;
    let discountRate = 0;

    const applyCouponBtn = document.getElementById('apply_coupon_btn');
    const couponInput = document.getElementById('coupon_input');
    const couponStatus = document.getElementById('coupon_status');
    const discountAmountEl = document.getElementById('discount_amount');
    const finalPriceEl = document.getElementById('final_price');
    const appliedCouponCodeEl = document.getElementById('applied_coupon_code');
    const buyButton = document.getElementById('buy_button');
    const selectedSeatInput = document.getElementById('selected_seat');
    const seatAlert = document.getElementById('seat_alert');

    // Koltuk seçimi dinleyicisi
    document.querySelectorAll('.seat_radio').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedSeatInput.value = this.value;
            buyButton.disabled = false;
            seatAlert.classList.add('d-none');
        });
    });

    // Satın al butonu tıklama dinleyicisi
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        if (!selectedSeatInput.value) {
            e.preventDefault(); // Formu göndermeyi engelle
            seatAlert.classList.remove('d-none');
        }
    });

    // Kupon uygulama butonu
    applyCouponBtn.addEventListener('click', async function() {
        const couponCode = couponInput.value.trim();
        if (!couponCode) return;

        const response = await fetch('kupon_kontrol.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ coupon_code: couponCode, trip_id: <?php echo $trip_id; ?> })
        });
        const data = await response.json();

        if (data.success) {
            discountRate = data.rate;
            appliedCouponCodeEl.value = couponCode;
            couponStatus.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        } else {
            discountRate = 0;
            appliedCouponCodeEl.value = '';
            couponStatus.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
        updatePrice();
    });

    function updatePrice() {
        const discount = originalPrice * (discountRate / 100);
        finalPrice = originalPrice - discount;
        discountAmountEl.textContent = `- ${discount.toFixed(2)} TL`;
        finalPriceEl.textContent = `${finalPrice.toFixed(2)} TL`;
    }
});
</script>

<?php include 'footer.php'; ?>