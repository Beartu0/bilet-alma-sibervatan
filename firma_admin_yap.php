<?php
include 'db.php';

// --- BU BİLGİLERİ KENDİNİZE GÖRE DÜZENLEYİN ---
$user_email_to_promote = 'asdasd@gmail.com'; // Firma Admini yapmak istediğiniz kullanıcının e-posta adresi
$assign_to_company_id = 1; // Atamak istediğiniz firma ID'si (1: Kamil Koç, 2: Metro Turizm)
// ---------------------------------------------

try {
    // Kullanıcının rolünü ve firma ID'sini güncelliyoruz.
    $stmt = $db->prepare("UPDATE users SET role = 'Firma Admin', company_id = ? WHERE email = ?");
    $stmt->execute([$assign_to_company_id, $user_email_to_promote]);

    if ($stmt->rowCount() > 0) {
        echo "Başarılı! '" . htmlspecialchars($user_email_to_promote) . "' e-postalı kullanıcı artık bir Firma Admini.";
    } else {
        echo "Kullanıcı bulunamadı veya bir hata oluştu.";
    }

} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>