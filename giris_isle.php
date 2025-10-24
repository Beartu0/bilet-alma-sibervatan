<?php
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_fullname'] = $user['fullname'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_company_id'] = $user['company_id'];
    $_SESSION['user_balance'] = $user['balance']; // BAKIYEYİ SESSION'A EKLİYORUZ

    // GİRİŞ SONRASI ROLE GÖRE YÖNLENDİRME
    if ($user['role'] === 'Admin') {
        header("Location: admin_panel.php");
    } elseif ($user['role'] === 'Firma Admin') {
        header("Location: firma_panel.php");
    } else {
        header("Location: index.php");
    }
    exit();

} else {
    die("Hata: E-posta veya şifre yanlış. <a href='giris.php'>Geri Dön</a>");
}
?>