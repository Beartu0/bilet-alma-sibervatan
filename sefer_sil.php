<?php
include 'db.php';

// Güvenlik Kontrolleri
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Firma Admin') {
    die("Bu işlemi yapma yetkiniz yok.");
}
if (!isset($_GET['id'])) {
    header('Location: firma_panel.php');
    exit();
}

$trip_id = $_GET['id'];
$company_id = $_SESSION['user_company_id'];

try {
    // SİLMEDEN ÖNCE KONTROL ET: Bu sefer, gerçekten bu adminin firmasına mı ait?
    // Bu, başka bir firma admininin, URL'yi değiştirerek sizin seferinizi silmesini engeller.
    $stmt_check = $db->prepare("SELECT id FROM trips WHERE id = ? AND company_id = ?");
    $stmt_check->execute([$trip_id, $company_id]);

    if ($stmt_check->rowCount() > 0) {
        // Kontrol başarılı, sefer bu firmaya ait. Şimdi silebiliriz.
        $stmt_delete = $db->prepare("DELETE FROM trips WHERE id = ?");
        $stmt_delete->execute([$trip_id]);

        echo "Sefer başarıyla silindi. Panele yönlendiriliyorsunuz...";
        header("refresh:2;url=firma_panel.php");
    } else {
        // Sefer bulunamadı veya bu firmaya ait değil.
        die("Silme yetkiniz olmayan bir seferi silmeye çalıştınız.");
    }
} catch (PDOException $e) {
    die("Hata: " . $e->getMessage());
}
?>