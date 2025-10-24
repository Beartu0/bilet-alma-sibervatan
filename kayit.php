<?php 
include 'db.php';
include 'frontend_header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h2>Yeni Kullanıcı Kaydı</h2>
            </div>
            <div class="card-body">
                <form action="kayit_isle.php" method="POST">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Ad Soyad:</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta Adresi:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                </form>
            </div>
            <div class="card-footer text-center">
                Zaten bir hesabın var mı? <a href="giris.php">Giriş Yap</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>