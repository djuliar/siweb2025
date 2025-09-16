## Struktur File Tambahan

```
project-crud/
│── api.php         (JSON API untuk DataTables server-side)
│── index.php       (Read - sekarang pakai Ajax DataTables)
│── config.php
│── create.php
│── update.php
│── delete.php
│── assets/
│    ├── jquery.min.js
│    ├── datatables.min.css
│    ├── datatables.min.js
```

---

## 1. `index.php` (Read dengan Ajax DataTables server-side)

```php
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD PHP Native + DataTables Server-side</title>
    <link rel="stylesheet" href="assets/datatables.min.css">
    <script src="assets/jquery.min.js"></script>
    <script src="assets/datatables.min.js"></script>
</head>
<body>
    <h2>Daftar Mahasiswa</h2>
    <a href="create.php">+ Tambah Data</a>
    <br><br>

    <table id="tabel-data" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>

    <script>
    $(document).ready(function(){
        $('#tabel-data').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "api.php",
            "columns": [
                { "data": "no" },
                { "data": "nama" },
                { "data": "nim" },
                { "data": "jurusan" },
                { "data": "aksi" }
            ]
        });
    });
    </script>
</body>
</html>
```

---

## 2. `api.php` (JSON API untuk DataTables)

```php
<?php
include 'config.php';

// Ambil request DataTables
$draw   = $_GET['draw'];
$start  = $_GET['start'];
$length = $_GET['length'];
$search = $_GET['search']['value'];

// Hitung total data
$totalRecords = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa"))['total'];

// Query dasar
$sql = "SELECT * FROM mahasiswa";

// Jika ada pencarian
if (!empty($search)) {
    $sql .= " WHERE nama LIKE '%".$search."%' OR nim LIKE '%".$search."%' OR jurusan LIKE '%".$search."%'";
}

// Hitung total setelah filter
$totalFiltered = mysqli_num_rows(mysqli_query($conn, $sql));

// Tambahkan limit untuk paging
$sql .= " ORDER BY id DESC LIMIT ".$start.",".$length;

$query = mysqli_query($conn, $sql);

$data = [];
$no = $start + 1;
while ($row = mysqli_fetch_assoc($query)) {
    $subdata = [];
    $subdata['no'] = $no++;
    $subdata['nama'] = $row['nama'];
    $subdata['nim'] = $row['nim'];
    $subdata['jurusan'] = $row['jurusan'];
    $subdata['aksi'] = '
        <a href="update.php?id='.$row['id'].'">Edit</a> | 
        <a href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Yakin hapus?\')">Hapus</a>
    ';
    $data[] = $subdata;
}

// Output JSON
$response = [
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

echo json_encode($response);
```

---

## 3. Database (sama seperti sebelumnya)

```sql
CREATE DATABASE db_crud;

USE db_crud;

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    jurusan VARCHAR(50) NOT NULL
);
```