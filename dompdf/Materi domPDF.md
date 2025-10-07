## 1. Apa itu DomPDF?

**DomPDF** adalah library PHP yang digunakan untuk mengonversi file HTML dan CSS menjadi **dokumen PDF**.
Artinya, Anda bisa mendesain tampilan laporan menggunakan HTML + CSS (seperti membuat halaman web), lalu DomPDF akan merendernya menjadi PDF.

Keunggulan DomPDF:

* Mudah digunakan pada **PHP Native** (tanpa framework).
* Mendukung CSS dasar (margin, padding, font, warna, table).
* Bisa menambahkan gambar (PNG, JPG, SVG).
* Bisa menambahkan header/footer, watermark, dan orientasi halaman (potrait/landscape).

---

## 2. Instalasi DomPDF

Ada dua cara:

### a) Via Composer (disarankan)

Jika project Anda menggunakan Composer:

```bash
composer require dompdf/dompdf
```

Setelah itu library akan tersimpan di folder `vendor`.

### b) Manual (tanpa Composer)

1. Download DomPDF di GitHub: [https://github.com/dompdf/dompdf/releases](https://github.com/dompdf/dompdf/releases)
2. Ekstrak ke folder project (misal: `dompdf/`).
3. Include autoload:

   ```php
   require 'dompdf/autoload.inc.php';
   ```

---

## 3. Struktur Dasar Kode

Contoh paling sederhana membuat file `cetak.php`:

```php
<?php
// Import library
require 'vendor/autoload.php'; // jika via composer
// require 'dompdf/autoload.inc.php'; // jika manual

use Dompdf\Dompdf;

// 1. Buat instance dompdf
$dompdf = new Dompdf();

// 2. Tulis HTML yang ingin dicetak ke PDF
$html = "
    <h1 style='text-align:center;'>Laporan Data Mahasiswa</h1>
    <table border='1' cellspacing='0' cellpadding='8' width='100%'>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Prodi</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Andi</td>
            <td>Teknik Informatika</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Budi</td>
            <td>Manajemen Informatika</td>
        </tr>
    </table>
";

// 3. Masukkan HTML ke DomPDF
$dompdf->loadHtml($html);

// 4. Atur ukuran dan orientasi kertas (opsional)
$dompdf->setPaper('A4', 'portrait'); // landscape juga bisa

// 5. Render HTML menjadi PDF
$dompdf->render();

// 6. Output ke browser
$dompdf->stream("laporan_mahasiswa.pdf", array("Attachment" => false));
// Attachment false = tampil di browser, true = langsung download
?>
```

---

## 4. Fitur Penting di DomPDF

1. **Custom CSS** → Anda bisa menggunakan style CSS untuk mempercantik PDF.

   ```html
   <style>
       body { font-family: Arial, sans-serif; font-size: 12px; }
       h1 { color: darkblue; }
   </style>
   ```

2. **Header dan Footer** → Bisa ditambahkan dengan CSS `position: fixed;`

   ```html
   <div style="position: fixed; top: 0; text-align: center; font-size: 12px;">
       Header Laporan
   </div>
   <div style="position: fixed; bottom: 0; text-align: center; font-size: 12px;">
       Halaman {PAGE_NUM} dari {PAGE_COUNT}
   </div>
   ```

3. **Gambar** → Bisa menambahkan logo.

   ```html
   <img src="logo.png" width="100">
   ```

4. **Orientasi & Ukuran Kertas**

   ```php
   $dompdf->setPaper('A4', 'portrait');
   $dompdf->setPaper('A4', 'landscape');
   $dompdf->setPaper([0,0,612,792]); // custom ukuran
   ```

5. **Simpan PDF ke File** (bukan hanya tampil di browser):

   ```php
   $output = $dompdf->output();
   file_put_contents('laporan.pdf', $output);
   ```

---

## 5. Contoh Kasus Real

Misalnya ingin cetak data dari database MySQL:

```php
<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
include 'koneksi.php';

$query = $conn->query("SELECT * FROM mahasiswa");
$html = "<h2>Laporan Data Mahasiswa</h2>";
$html .= "<table border='1' cellspacing='0' cellpadding='8' width='100%'>";
$html .= "<tr><th>No</th><th>Nama</th><th>Prodi</th></tr>";
$no=1;
while($row = $query->fetch_assoc()){
    $html .= "<tr>
        <td>".$no++."</td>
        <td>".$row['nama']."</td>
        <td>".$row['prodi']."</td>
    </tr>";
}
$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream("laporan_mahasiswa.pdf", ["Attachment"=>false]);
```

---

## 6. Kelebihan & Keterbatasan

✅ **Kelebihan:**

* Mudah dipakai di PHP Native.
* Tidak perlu software tambahan.
* Mendukung HTML + CSS.

⚠️ **Keterbatasan:**

* Tidak semua CSS kompleks didukung (misal Flexbox, Grid).
* File gambar sebaiknya **path absolut** atau base64.
* Untuk data besar, performa bisa lambat.

---


