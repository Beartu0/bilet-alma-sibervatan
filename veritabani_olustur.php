<?php
try {
    // veritabani.sqlite adında bir veritabanı dosyası oluşturur veya mevcut olana bağlanır.
    $db = new PDO('sqlite:veritabani.sqlite');
    // Hata modunu ayarlayarak olası sorunları görmemizi sağlar.
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "SQLite veritabanına başarıyla bağlanıldı.<br>";

    // Tabloları oluşturmak için SQL komutları
    $sql = "
    -- Kullanıcılar tablosu
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        fullname TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT 'User', -- Roller: User, Firma Admin, Admin
        company_id INTEGER NULL,
        balance REAL NOT NULL DEFAULT 0.0
    );

    -- Otobüs Firmaları tablosu
    CREATE TABLE IF NOT EXISTS companies (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL
    );

    -- Seferler (Otobüs Yolculukları) tablosu
    CREATE TABLE IF NOT EXISTS trips (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        company_id INTEGER NOT NULL,
        departure_location TEXT NOT NULL,
        arrival_location TEXT NOT NULL,
        departure_time DATETIME NOT NULL,
        arrival_time DATETIME NOT NULL,
        price REAL NOT NULL,
        seat_count INTEGER NOT NULL,
        FOREIGN KEY (company_id) REFERENCES companies(id)
    );

    -- Biletler tablosu
    CREATE TABLE IF NOT EXISTS tickets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        trip_id INTEGER NOT NULL,
        seat_number INTEGER NOT NULL,
        purchase_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        status TEXT NOT NULL DEFAULT 'ACTIVE', -- ACTIVE, CANCELLED
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (trip_id) REFERENCES trips(id)
    );

    -- Kuponlar tablosu
    CREATE TABLE IF NOT EXISTS coupons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT NOT NULL UNIQUE,
        discount_rate REAL NOT NULL,
        usage_limit INTEGER NOT NULL,
        expiration_date DATE NOT NULL,
        company_id INTEGER NULL -- NULL ise tüm firmalarda geçerli
    );
    ";

    // SQL komutlarını çalıştır
    $db->exec($sql);

    echo "Tüm tablolar başarıyla oluşturuldu.";

} catch (PDOException $e) {
    // Hata olursa ekrana yazdır
    echo "Hata: " . $e->getMessage();
}
?>