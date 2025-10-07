## Struktur Folder

```
project-upload-db/
│
├─ index.php          ← Form upload + tampil data file
├─ upload.php         ← Proses upload & simpan metadata ke DB
├─ config.php         ← Koneksi database
└─ uploads/           ← Folder penyimpanan file
```

---

## 1️ File: `config.php`

Konfigurasi koneksi ke database MySQL.

```php
<?php
// config.php

$host = 'localhost';
$user = 'root';      // sesuaikan
$pass = '';          // sesuaikan
$db   = 'upload_db'; // nama database

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
?>
```

---

## 2️ Struktur Database MySQL

Buat database dan tabel:

```sql
CREATE DATABASE upload_db;
USE upload_db;

CREATE TABLE files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  original_name VARCHAR(255),
  new_name VARCHAR(255),
  mime_type VARCHAR(100),
  size BIGINT,
  upload_date DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

---

## 3️ File: `index.php`

Form upload + daftar file yang sudah di-upload.

```php
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Upload File dengan Database</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      padding: 30px;
    }
    .container {
      background: white;
      width: 600px;
      margin: auto;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    input[type="file"] {
      display: block;
      margin: 20px auto;
    }
    button {
      width: 100%;
      background: #007BFF;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #0056b3;
    }
    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #007BFF;
      color: white;
    }
    a {
      color: #007BFF;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Upload File (Simpan ke Database)</h2>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Upload Sekarang</button>
  </form>

  <h3>Daftar File Upload</h3>
  <table>
    <tr>
      <th>No</th>
      <th>Nama Asli</th>
      <th>Nama Tersimpan</th>
      <th>Tipe</th>
      <th>Ukuran (KB)</th>
      <th>Tanggal Upload</th>
      <th>Aksi</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM files ORDER BY id DESC");
    if ($result->num_rows > 0):
        $no = 1;
        while ($row = $result->fetch_assoc()):
    ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['original_name']) ?></td>
      <td><?= htmlspecialchars($row['new_name']) ?></td>
      <td><?= htmlspecialchars($row['mime_type']) ?></td>
      <td><?= round($row['size']/1024, 2) ?></td>
      <td><?= $row['upload_date'] ?></td>
      <td><a href="uploads/<?= urlencode($row['new_name']) ?>" target="_blank">Lihat</a></td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="7">Belum ada file diunggah.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>
```

---

## 4️ File: `upload.php`

Proses upload + validasi + simpan metadata ke database.

```php
<?php
require 'config.php';

// === Konfigurasi dasar ===
$uploadDir = __DIR__ . '/uploads/';
$maxSize = 5 * 1024 * 1024; // 5 MB
$allowedExt = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
$allowedMime = [
    'image/jpeg',
    'image/png',
    'application/pdf',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];

// === Pastikan folder ada ===
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// === Validasi keberadaan file ===
if (!isset($_FILES['file'])) {
    die('Tidak ada file yang dikirim!');
}

$file = $_FILES['file'];

// === Tangani error ===
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errCodes = [
        UPLOAD_ERR_INI_SIZE => 'File melebihi upload_max_filesize di php.ini',
        UPLOAD_ERR_FORM_SIZE => 'File terlalu besar dari form',
        UPLOAD_ERR_PARTIAL => 'Upload hanya sebagian',
        UPLOAD_ERR_NO_FILE => 'Tidak ada file diupload',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder tmp hilang',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
        UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP'
    ];
    die('Upload gagal: ' . ($errCodes[$file['error']] ?? 'Error tidak diketahui.'));
}

// === Validasi ukuran ===
if ($file['size'] > $maxSize) {
    die('Ukuran file terlalu besar. Maks 5 MB.');
}

// === Validasi ekstensi ===
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
    die('Ekstensi tidak diizinkan.');
}

// === Validasi MIME ===
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
if (!in_array($mime, $allowedMime)) {
    die('Tipe file tidak diizinkan. MIME: ' . htmlspecialchars($mime));
}

// === Buat nama file baru ===
$newName = uniqid('file_', true) . '.' . $ext;
$destination = $uploadDir . $newName;

// === Pindahkan file ===
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    die('Gagal menyimpan file ke folder uploads.');
}

// === Set permission ===
chmod($destination, 0644);

// === Simpan metadata ke database ===
$stmt = $conn->prepare("INSERT INTO files (original_name, new_name, mime_type, size) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $file['name'], $newName, $mime, $file['size']);
$stmt->execute();
$stmt->close();

// === Kembali ke halaman utama ===
header('Location: index.php');
exit;
```

---

## 5️ Folder `uploads/`

* Buat folder bernama `uploads` di dalam project.
* Pastikan writable:
  di Linux: `chmod 755 uploads/`
  di Windows biasanya otomatis sudah bisa ditulis.

---

## Cara Menjalankan

1. Simpan semua file di `C:\xampp\htdocs\project-upload-db\`
2. Jalankan XAMPP → aktifkan **Apache** & **MySQL**
3. Buat database & tabel sesuai query di atas.
4. Buka browser:
   👉 `http://localhost/project-upload-db/index.php`
5. Upload file — hasilnya akan tersimpan di folder `uploads/` dan tercatat di database.

---

## Tips Keamanan Produksi

* Tambahkan `.htaccess` di `uploads/` untuk mencegah eksekusi file PHP:

  ```apache
  <FilesMatch "\.(php|php5|phtml)$">
  Deny from all
  </FilesMatch>
  ```

* Jika ingin menampilkan file via skrip (bukan langsung di folder `uploads/`), gunakan endpoint download terautentikasi.

* Pastikan `upload_max_filesize` dan `post_max_size` di `php.ini` sesuai kebutuhan.
