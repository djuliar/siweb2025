<?php
require 'db.php';

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