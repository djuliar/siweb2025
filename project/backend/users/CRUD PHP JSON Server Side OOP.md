## Struktur Project

```
crud-oop/
│── config/
│   └── Database.php
│── classes/
│   └── Mahasiswa.php
│── index.php       (Read + DataTables)
│── create.php      (Create)
│── update.php      (Update)
│── delete.php      (Delete)
│── api.php         (JSON API untuk DataTables server-side)
│── assets/
│    ├── jquery.min.js
│    ├── datatables.min.css
│    ├── datatables.min.js
```

---

## 1. `config/Database.php`

Class untuk koneksi database.

```php
<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "db_crud";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
}
```

---

## 2. `classes/Mahasiswa.php`

Class untuk operasi CRUD mahasiswa.

```php
<?php
require_once __DIR__ . '/../config/Database.php';

class Mahasiswa {
    private $conn;
    private $table = "mahasiswa";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    public function create($nama, $nim, $jurusan) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (nama, nim, jurusan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $nim, $jurusan);
        return $stmt->execute();
    }

    public function read() {
        $result = $this->conn->query("SELECT * FROM $this->table ORDER BY id DESC");
        return $result;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $nama, $nim, $jurusan) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET nama=?, nim=?, jurusan=? WHERE id=?");
        $stmt->bind_param("sssi", $nama, $nim, $jurusan, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Server-side untuk DataTables
    public function getDataTables($start, $length, $search) {
        $sql = "SELECT * FROM $this->table";
        if (!empty($search)) {
            $sql .= " WHERE nama LIKE '%$search%' OR nim LIKE '%$search%' OR jurusan LIKE '%$search%'";
        }
        $totalFiltered = $this->conn->query($sql)->num_rows;

        $sql .= " ORDER BY id DESC LIMIT $start, $length";
        $result = $this->conn->query($sql);

        return [$result, $totalFiltered];
    }

    public function countAll() {
        return $this->conn->query("SELECT COUNT(*) as total FROM $this->table")->fetch_assoc()['total'];
    }
}
```

---

## 3. `index.php` (Read dengan DataTables Ajax)

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD PHP OOP + DataTables</title>
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

## 4. `create.php`

```php
<?php
require_once 'classes/Mahasiswa.php';
$mahasiswa = new Mahasiswa();

if (isset($_POST['submit'])) {
    $mahasiswa->create($_POST['nama'], $_POST['nim'], $_POST['jurusan']);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Tambah Data</title></head>
<body>
    <h2>Tambah Mahasiswa</h2>
    <form method="post">
        Nama: <input type="text" name="nama" required><br>
        NIM: <input type="text" name="nim" required><br>
        Jurusan: <input type="text" name="jurusan" required><br>
        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>
```

---

## 5. `update.php`

```php
<?php
require_once 'classes/Mahasiswa.php';
$mahasiswa = new Mahasiswa();

$id = $_GET['id'];
$data = $mahasiswa->getById($id);

if (isset($_POST['update'])) {
    $mahasiswa->update($id, $_POST['nama'], $_POST['nim'], $_POST['jurusan']);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Data</title></head>
<body>
    <h2>Edit Mahasiswa</h2>
    <form method="post">
        Nama: <input type="text" name="nama" value="<?= $data['nama']; ?>" required><br>
        NIM: <input type="text" name="nim" value="<?= $data['nim']; ?>" required><br>
        Jurusan: <input type="text" name="jurusan" value="<?= $data['jurusan']; ?>" required><br>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
```

---

## 6. `delete.php`

```php
<?php
require_once 'classes/Mahasiswa.php';
$mahasiswa = new Mahasiswa();

$id = $_GET['id'];
$mahasiswa->delete($id);

header("Location: index.php");
```

---

## 7. `api.php` (Server-side JSON DataTables)

```php
<?php
require_once 'classes/Mahasiswa.php';
$mahasiswa = new Mahasiswa();

// Ambil parameter DataTables
$draw   = $_GET['draw'];
$start  = $_GET['start'];
$length = $_GET['length'];
$search = $_GET['search']['value'];

// Hitung total data
$totalRecords = $mahasiswa->countAll();

// Ambil data dengan filter + paging
list($result, $totalFiltered) = $mahasiswa->getDataTables($start, $length, $search);

// Format data
$data = [];
$no = $start + 1;
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "no" => $no++,
        "nama" => $row['nama'],
        "nim" => $row['nim'],
        "jurusan" => $row['jurusan'],
        "aksi" => '
            <a href="update.php?id='.$row['id'].'">Edit</a> | 
            <a href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Yakin?\')">Hapus</a>'
    ];
}

// Response JSON
echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
]);
```

---

## 8. Database

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