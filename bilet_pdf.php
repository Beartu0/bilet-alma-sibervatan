<?php
// Önce kütüphanenin ana dosyasını projemize dahil ediyoruz.
require 'dompdf/autoload.inc.php';

// Kütüphanenin içindeki ana sınıfı kullanacağımızı belirtiyoruz.
use Dompdf\Dompdf;

// Veritabanı bağlantımızı her zamanki gibi yapıyoruz.
include 'db.php';

// --- BİLET BİLGİLERİNİ ÇEKME (Öncekiyle aynı kod) ---

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("Yetkisiz erişim.");
}
$user_id = $_SESSION['user_id'];
$ticket_id = $_GET['id'];
$stmt = $db->prepare("
    SELECT
        tickets.seat_number, tickets.purchase_time,
        trips.*,
        users.fullname,
        companies.name AS company_name
    FROM tickets
    JOIN users ON tickets.user_id = users.id
    JOIN trips ON tickets.trip_id = trips.id
    JOIN companies ON trips.company_id = companies.id
    WHERE tickets.id = ? AND tickets.user_id = ?
");
$stmt->execute([$ticket_id, $user_id]);
$bilet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bilet) {
    die("Böyle bir bilet bulunamadı veya bu bileti görme yetkiniz yok.");
}
// -----------------------------------------------------------------


// --- PDF'E DÖNÜŞTÜRÜLECEK HTML KODUNU OLUŞTURMA ---

// ob_start() ile çıktı tamponlamayı başlatıyoruz.
// Bu komuttan sonraki tüm 'echo' veya HTML çıktıları ekrana basılmaz, bir hafızada biriktirilir.
ob_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>E-Bilet</title>
    <style>
        /* PDF içinde Türkçe karakterlerin doğru görünmesi için font tanımlaması önemli! */
        /* Dompdf'in varsayılan fontları Türkçe karakterleri destekler (DejaVu Sans) */
        body { font-family: 'DejaVu Sans', sans-serif; }
        .ticket-container { width: 100%; border: 2px solid black; padding: 20px; }
        .header { text-align: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .header h1 { margin: 0; }
        .details { margin-top: 20px; }
        .details table { width: 100%; border-collapse: collapse; }
        .details th, .details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .details th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: center; font-size: 0.9em; }
    </style>
</head>
<body>
<div class="ticket-container">
    <div class="header">
        <h1>E-BİLET</h1>
        <p><?php echo htmlspecialchars($bilet['company_name']); ?></p>
    </div>
    <div class="details">
        <h3>Yolculuk Bilgileri</h3>
        <table>
            <tr>
                <th>Güzergah</th>
                <td><?php echo htmlspecialchars($bilet['departure_location']); ?> -> <?php echo htmlspecialchars($bilet['arrival_location']); ?></td>
            </tr>
            <tr>
                <th>Kalkış Tarihi ve Saati</th>
                <td><?php echo date('d.m.Y H:i', strtotime($bilet['departure_time'])); ?></td>
            </tr>
             <tr>
                <th>Tahmini Varış Tarihi ve Saati</th>
                <td><?php echo date('d.m.Y H:i', strtotime($bilet['arrival_time'])); ?></td>
            </tr>
        </table>

        <h3 style="margin-top: 20px;">Yolcu ve Bilet Bilgileri</h3>
        <table>
            <tr>
                <th>Yolcu Adı Soyadı</th>
                <td><?php echo htmlspecialchars($bilet['fullname']); ?></td>
            </tr>
            <tr>
                <th>Koltuk Numarası</th>
                <td><?php echo htmlspecialchars($bilet['seat_number']); ?></td>
            </tr>
            <tr>
                <th>Satın Alma Tarihi</th>
                <td><?php echo date('d.m.Y H:i', strtotime($bilet['purchase_time'])); ?></td>
            </tr>
        </table>
    </div>
    <div class="footer">
        <p>İyi yolculuklar dileriz!</p>
    </div>
</div>
</body>
</html>
<?php
// ob_get_clean() ile hafızada biriktirilen tüm çıktıyı bir değişkene alıyoruz ve tamponlamayı bitiriyoruz.
$html = ob_get_clean();
// -----------------------------------------------------------------


// --- DOMPDF İLE PDF OLUŞTURMA ---

// Yeni bir Dompdf nesnesi oluştur
$dompdf = new Dompdf();

// HTML içeriğini Dompdf'e yükle
$dompdf->loadHtml($html);

// (İsteğe bağlı) PDF kağıt boyutunu ve yönünü ayarla
$dompdf->setPaper('A4', 'portrait');

// HTML'i PDF'e dönüştür
$dompdf->render();

// Oluşturulan PDF'i tarayıcıya gönder.
// "e-bilet.pdf" kısmı, inecek dosyanın adıdır.
// "attachment" => 1 parametresi, dosyanın direkt indirilmesini sağlar. Gösterilmesini değil.
$dompdf->stream("e-bilet.pdf", ["Attachment" => 1]);
?>