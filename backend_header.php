<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">Yönetim Paneli</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link" href="#">Yönetici: <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-light text-dark ms-2" href="index.php">Siteyi Görüntüle</a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-danger text-white ms-2" href="cikis.php">Çıkış Yap</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">