<?php
include 'db.php';

try {
    echo "Sahte veri oluşturma işlemi başlatılıyor...<br>";
    $db->beginTransaction();

    // 1. ADIM: MEVCUT VERİLERİ TEMİZLEME (Doğru sırada)
    echo "Eski veriler temizleniyor...<br>";
    $db->exec("DELETE FROM tickets");
    $db->exec("DELETE FROM coupons");
    $db->exec("DELETE FROM trips");
    $db->exec("DELETE FROM users");
    $db->exec("DELETE FROM companies");
    // SQLite'ın auto-increment sayaçlarını sıfırlama
    $db->exec("DELETE FROM sqlite_sequence");
    
    // 2. ADIM: FİRMALARI OLUŞTURMA
    echo "Yeni firmalar oluşturuluyor...<br>";
    $db->exec("INSERT INTO companies (name) VALUES ('Pamukkale Turizm'), ('Metro Turizm'), ('Kamil Koç')");
    $pamukkale_id = 1;
    $metro_id = 2;
    $kamilkoc_id = 3;

    // 3. ADIM: KULLANICILARI OLUŞTURMA
    echo "Test kullanıcıları oluşturuluyor...<br>";
    $users = [
        ['fullname' => 'Ali Veli', 'email' => 'yolcu@eposta.com', 'password' => '123456', 'role' => 'User', 'company_id' => null, 'balance' => 5000],
        ['fullname' => 'Ayşe Firma', 'email' => 'firma@eposta.com', 'password' => '123456', 'role' => 'Firma Admin', 'company_id' => $pamukkale_id, 'balance' => 0],
        ['fullname' => 'Süper Admin', 'email' => 'admin@eposta.com', 'password' => '123456', 'role' => 'Admin', 'company_id' => null, 'balance' => 0]
    ];

    $user_stmt = $db->prepare("INSERT INTO users (fullname, email, password, role, company_id, balance) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($users as $user) {
        $user_stmt->execute([$user['fullname'], $user['email'], password_hash($user['password'], PASSWORD_DEFAULT), $user['role'], $user['company_id'], $user['balance']]);
    }
    $yolcu_id = 1;

    // 4. ADIM: ÇEŞİTLİ SEFERLER OLUŞTURMA
    echo "Gelecek tarihli seferler oluşturuluyor...<br>";
    $trips = [
        [$pamukkale_id, 'İstanbul', 'Ankara', '+1 day 08:00', '+1 day 14:30', 800, 40],
        [$pamukkale_id, 'Ankara', 'İstanbul', '+1 day 10:00', '+1 day 16:30', 800, 40],
        [$metro_id, 'İzmir', 'Antalya', '+2 days 22:00', '+3 days 05:00', 750, 42],
        [$metro_id, 'Antalya', 'İzmir', '+2 days 21:00', '+3 days 04:00', 750, 42],
        [$kamilkoc_id, 'Bursa', 'Ankara', '+3 days 11:00', '+3 days 17:00', 600, 38],
        [$kamilkoc_id, 'Ankara', 'Bursa', '+3 days 13:00', '+3 days 19:00', 600, 38],
        [$pamukkale_id, 'İstanbul', 'İzmir', '+4 days 23:59', '+5 days 07:00', 900, 40],
    ];
    $trip_stmt = $db->prepare("INSERT INTO trips (company_id, departure_location, arrival_location, departure_time, arrival_time, price, seat_count) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($trips as $trip) {
        $dep_time = date('Y-m-d H:i:s', strtotime($trip[3]));
        $arr_time = date('Y-m-d H:i:s', strtotime($trip[4]));
        $trip_stmt->execute([$trip[0], $trip[1], $trip[2], $dep_time, $arr_time, $trip[5], $trip[6]]);
    }
    $istanbul_ankara_sefer_id = 1;

    // 5. ADIM: KUPONLARI OLUŞTURMA
    echo "Çeşitli kuponlar oluşturuluyor...<br>";
    $db->exec("INSERT INTO coupons (code, discount_rate, usage_limit, expiration_date, company_id) VALUES ('YAZ15', 15, 100, date('now', '+1 month'), NULL)"); // Genel kupon
    $db->exec("INSERT INTO coupons (code, discount_rate, usage_limit, expiration_date, company_id) VALUES ('PAMUKKALE20', 20, 50, date('now', '+2 months'), $pamukkale_id)"); // Firmaya özel kupon
    $db->exec("INSERT INTO coupons (code, discount_rate, usage_limit, expiration_date, company_id) VALUES ('GECMISKUPON', 10, 10, date('now', '-1 day'), NULL)"); // Süresi geçmiş kupon

    // 6. ADIM: ÖRNEK BİR BİLET SATIN ALMA İŞLEMİ
    echo "Örnek bilet satın alınıyor...<br>";
    $bilet_fiyati = 800;
    $alinan_koltuk = 5;
    $db->exec("INSERT INTO tickets (user_id, trip_id, seat_number, price_paid, coupon_id, status) VALUES ($yolcu_id, $istanbul_ankara_sefer_id, $alinan_koltuk, $bilet_fiyati, NULL, 'ACTIVE')");
    $db->exec("UPDATE users SET balance = balance - $bilet_fiyati WHERE id = $yolcu_id");

    $db->commit();
    echo "<hr><b>Tüm sahte veriler başarıyla oluşturuldu!</b>";

} catch (Exception $e) {
    $db->rollBack();
    die("<hr><b>Hata oluştu, tüm işlemler geri alındı:</b> " . $e->getMessage());
}
?>