<?php
// Veritabanı bağlantı dosyamızı dahil ediyoruz.
include 'db.php';

// Formdan POST metodu ile gelen verileri alıyoruz.
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];

// Şifreyi doğrudan veritabanına kaydetmek tehlikelidir.
// Bu yüzden "hash"leyerek (şifreleyerek) kaydediyoruz.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // SQL sorgumuzu hazırlıyoruz.
    $stmt = $db->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    
    // Sorguyu çalıştırıyoruz.
    $stmt->execute([$fullname, $email, $hashed_password]);
    
    echo "Kayıt başarılı! Giriş sayfasına yönlendiriliyorsunuz...";
    
    // Kullanıcıyı 2 saniye sonra giriş sayfasına yönlendir.
    header("refresh:2;url=giris.php");

} catch (PDOException $e) {
    // Eğer e-posta daha önce kayıtlıysa, SQLite UNIQUE kısıtlaması hata verecektir.
    if ($e->getCode() == 23000) {
        die("Hata: Bu e-posta adresi zaten kayıtlı.");
    } else {
        die("Kayıt sırasında bir hata oluştu: " . $e->getMessage());
    }
}
?>