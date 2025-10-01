# Dokumentasi Instalasi & Penggunaan REST API Thermal Printer

## 1. Install Driver Printer
- Pastikan printer thermal sudah terhubung ke komputer (USB/LAN).
- Install driver printer sesuai merk/type (misal: TP805L, Epson, dll).
- Setelah selesai, pastikan printer muncul di daftar "Devices and Printers" Windows.

## 2. Share Printer & Beri Nama
- Buka "Devices and Printers" di Windows.
- Klik kanan pada printer thermal → pilih "Printer properties".
- Masuk tab "Sharing", centang "Share this printer".
- Masukkan nama share printer, misal: `TP805L`.
- Klik OK.

## 3. Install PHP & Library escpos-php
- Pastikan PHP sudah terinstall (bisa pakai XAMPP, Laragon, dsb).
- **Wajib:** Install library escpos-php menggunakan Composer.
  ```
  composer require mike42/escpos-php
  ```
- Pastikan file `vendor/autoload.php` sudah ada di folder project.
- **Catatan:** Jika hanya download zip tanpa Composer, library tidak akan terinstall dan script tidak bisa dijalankan.

## 4. Siapkan File RestPrinter.php
- Buat file `RestPrinter.php` di folder web server (misal: `htdocs/app/printing/RestPrinter.php`).
- Contoh isi:
  ```php
  <?php
  require __DIR__ . '/vendor/autoload.php';
  use Mike42\Escpos\Printer;
  use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
  use Mike42\Escpos\CapabilityProfile;

  header('Content-Type: application/json');
  $data = json_decode(file_get_contents('php://input'), true);
  $text = isset($data['text']) ? $data['text'] : 'Default Text';

  $printer = "TP805L"; // Ganti sesuai nama share printer
  $profile = CapabilityProfile::load("simple");
  $connector = new WindowsPrintConnector($printer);
  $printer = new Printer($connector, $profile);

  $printer->text($text . "\n");
  $printer->cut();
  $printer->close();

  echo json_encode(['status' => 'ok']);
  ```
- Pastikan nama printer (`$printer`) sesuai dengan nama share printer di Windows.

## 5. Jalankan RestPrinter.php via REST API POST
- Pastikan web server (Apache/Nginx) sudah berjalan.
- Akses endpoint, misal: `http://localhost/app/printing/RestPrinter.php`
- Kirim data via HTTP POST dari aplikasi lain (Node.js, Angular, Postman, dsb).

### Contoh Request dari Node.js:
```javascript
const axios = require('axios');
axios.post('http://localhost/app/printing/RestPrinter.php', { text: 'Teks yang akan dicetak' });
```

### Contoh Request dari Angular:
```typescript
this.http.post('http://localhost/app/printing/RestPrinter.php', { text: 'Teks dari Angular' }).subscribe();
```

---

**Catatan Penting:**
- Pastikan user/service yang menjalankan web server punya akses ke printer sharing.
- Jika error, cek permission sharing printer dan service web server.
- **Wajib install library via Composer.** Jika hanya download zip tanpa Composer, script tidak akan berjalan karena file library tidak tersedia.