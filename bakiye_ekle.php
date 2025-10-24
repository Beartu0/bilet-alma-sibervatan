<?php
include 'db.php';

// Bakiye eklemek için giriş yapmış olmalısın.
if (!isset($_SESSION['user_id'])) {
    die("Lütfen önce giriş yapın.");
}

$user_id = $_SESSION['user_id'];
$amount_to_add = 5000.0; // Eklenecek miktar

try {
    $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$amount_to_add, $user_id]);

    echo "Hesabınıza " . $amount_to_add . " TL sanal bakiye eklendi. Ana sayfaya yönlendiriliyorsunuz...";
    header("refresh:2;url=index.php");
} catch (PDOException $e) {
    die("Bakiye eklenirken bir hata oluştu: " . $e->getMessage());
}
?>