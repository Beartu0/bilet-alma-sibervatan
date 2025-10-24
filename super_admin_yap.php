<?php
include 'db.php';

// --- BU BİLGİYİ KENDİNE GÖRE DÜZENLE ---
$user_email_to_promote = 'asdasd@gmail.com'; // Admin yapmak istediğiniz kullanıcının e-posta adresi
// ---------------------------------------------

try {
    // Kullanıcının rolünü 'Admin' yapıyor ve company_id'sini NULL yapıyoruz.
    // Çünkü süper admin herhangi bir firmaya bağlı değildir.
    $stmt = $db->prepare("UPDATE users SET role = 'Admin', company_id = NULL WHERE email = ?");
    $stmt->execute([$user_email_to_promote]);

    if ($stmt->rowCount() > 0) {
        echo "Başarılı! '" . htmlspecialchars($user_email_to_promote) . "' e-postalı kullanıcı artık bir Süper Admin.";
    } else {
        echo "Kullanıcı bulunamadı veya bir hata oluştu.";
    }

} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>