<?php
include 'db.php';

try {
    echo "Veri ekleme işlemi başlıyor...<br>";

    // Örnek Firmaları Ekleme
    $db->exec("INSERT INTO companies (name) VALUES ('Kamil Koç')");
    $db->exec("INSERT INTO companies (name) VALUES ('Metro Turizm')");
    
    // Son eklenen firmaların ID'lerini alalım (Bu yöntem basitlik için, normalde daha garantili yollar kullanılır)
    $kamil_koc_id = 1;
    $metro_id = 2;

    echo "Örnek firmalar eklendi.<br>";

    // Örnek Seferleri Ekleme
    // Not: Tarih ve saatleri gelecekte bir zaman olarak ayarladık.
    $db->exec("
        INSERT INTO trips (company_id, departure_location, arrival_location, departure_time, arrival_time, price, seat_count) 
        VALUES 
        ($kamil_koc_id, 'Ankara', 'İstanbul', '2025-10-28 08:00:00', '2025-10-28 14:30:00', 750.0, 40),
        ($kamil_koc_id, 'İstanbul', 'Ankara', '2025-10-28 10:00:00', '2025-10-28 16:30:00', 750.0, 40),
        ($metro_id, 'Ankara', 'İzmir', '2025-10-29 22:00:00', '2025-10-30 06:00:00', 800.0, 42),
        ($metro_id, 'İzmir', 'Ankara', '2025-10-29 23:00:00', '2025-10-30 07:00:00', 800.0, 42)
    ");
    
    echo "Örnek seferler eklendi.<br>";
    echo "İşlem başarıyla tamamlandı!";

} catch (PDOException $e) {
    die("Veri eklenirken hata oluştu: " . $e->getMessage());
}
?>