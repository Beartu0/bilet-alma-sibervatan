<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilet Satın Alma Platformu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">🚌 Bilet Platformu</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="#">Hoş Geldin, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="bakiye_yukle.php">
                    Bakiye: <strong><?php echo number_format($_SESSION['user_balance'] ?? 0, 2); ?> TL</strong>
                </a>
            </li>
            <li class="nav-item"><a class="nav-link" href="biletlerim.php">Biletlerim</a></li>
            <li class="nav-item"><a class="nav-link" href="bakiye_yukle.php">Bakiye Yükle</a></li>
            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                <li class="nav-item"><a class="nav-link btn btn-outline-light ms-2" href="admin_panel.php">Yönetim Paneli</a></li>
            <?php elseif ($_SESSION['user_role'] === 'Firma Admin'): ?>
                <li class="nav-item"><a class="nav-link btn btn-outline-light ms-2" href="firma_panel.php">Firma Paneli</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2" href="cikis.php">Çıkış Yap</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="giris.php">Giriş Yap</a></li>
            <li class="nav-item"><a class="nav-link" href="kayit.php">Kayıt Ol</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">