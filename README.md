# Bilet Satın Alma Platformu

[cite_start]Bu proje, PHP ve SQLite kullanılarak geliştirilmiş dinamik, veritabanı destekli ve çok kullanıcılı bir otobüs bileti satış platformudur. [cite: 6] Proje, kullanıcıların seferleri aramasına, bilet almasına/iptal etmesine olanak tanırken, firma ve süper adminler için yönetim panelleri sunar.

## Teknolojiler
- [cite_start]**Programlama Dili:** PHP [cite: 9]
- [cite_start]**Veritabanı:** SQLite [cite: 11]
- [cite_start]**Arayüz:** HTML, CSS, Bootstrap [cite: 10]
- [cite_start]**Paketleme:** Docker 

## Özellikler

### Ziyaretçi & Kullanıcı (Yolcu)
- [cite_start]Sefer arama ve listeleme [cite: 16]
- [cite_start]Kullanıcı kaydı ve girişi [cite: 19]
- [cite_start]Sanal bakiye ile bilet satın alma ve kupon kullanma [cite: 20]
- [cite_start]Satın alınmış biletleri listeleme [cite: 21]
- [cite_start]Kalkışa 1 saat kalana kadar bilet iptal etme ve ücret iadesi alma [cite: 23, 24]
- [cite_start]Biletin PDF çıktısını alabilme [cite: 21]

### Firma Admin (Firma Yetkilisi)
- [cite_start]Sadece kendi firmasına ait seferleri listeleme, ekleme, düzenleme ve silme (CRUD) [cite: 26, 28]
- [cite_start]Kendi firmasına özel indirim kuponları oluşturma ve yönetme [cite: 29]

### Admin (Süper Admin)
- [cite_start]Yeni otobüs firmaları oluşturma, düzenleme ve silme [cite: 32]
- [cite_start]Yeni "Firma Admin" kullanıcıları oluşturma ve firmalara atama [cite: 32]
- [cite_start]Tüm firmalarda geçerli genel indirim kuponları oluşturma ve yönetme [cite: 33]

## Kurulum ve Çalıştırma

Bu projeyi Docker kullanarak çalıştırmak için aşağıdaki adımları izleyin.

### Gereksinimler
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)'ın bilgisayarınızda kurulu olması gerekmektedir.

### Adımlar
1. Projeyi klonlayın veya zip olarak indirin.
2. Terminali veya PowerShell'i proje ana dizininde açın.
3. Aşağıdaki komutu çalıştırarak Docker container'ını oluşturun ve başlatın:
   ```bash
   docker-compose up --build -d
   ```
4. Uygulamaya erişmek için tarayıcınızdan **`http://localhost:8080`** adresine gidin.
5. Veritabanını sahte verilerle doldurmak için **`http://localhost:8080/dummy_veri_olustur.php`** adresini bir kereliğine çalıştırın.
6. Uygulamayı durdurmak için proje dizininde terminalde aşağıdaki komutu çalıştırın:
   ```bash
   docker-compose down
   ```

## Test Kullanıcıları
`dummy_veri_olustur.php` script'i çalıştırıldıktan sonra aşağıdaki test hesaplarını kullanabilirsiniz:

- **Normal Kullanıcı (Yolcu):**
  - **E-posta:** `yolcu@eposta.com`
  - **Şifre:** `123456`
- **Firma Admin (Pamukkale Turizm Yetkilisi):**
  - **E-posta:** `firma@eposta.com`
  - **Şifre:** `123456`
- **Süper Admin:**
  - **E-posta:** `admin@eposta.com`
  - **Şifre:** `123456`
