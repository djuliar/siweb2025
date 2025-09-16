Struktur CRUD yang umum:

```
project-crud/
│── config.php
│── index.php       (Read - dengan DataTables)
│── create.php      (Create)
│── update.php      (Update)
│── delete.php      (Delete)
│── assets/
│    ├── jquery.min.js
│    ├── datatables.min.css
│    ├── datatables.min.js
```

---

## 1. `config.php`

File koneksi ke database MySQL.

```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_crud";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

---

## 2. `index.php` (Read dengan DataTables)

```php
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD PHP Native + DataTables</title>
    <link rel="stylesheet" href="assets/datatables.min.css">
    <script src="assets/jquery.min.js"></script>
    <script src="assets/datatables.min.js"></script>
</head>
<body>
    <h2>Daftar Mahasiswa</h2>
    <a href="create.php">+ Tambah Data</a>
    <br><br>
    <table id="tabel-data" class="display">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no=1;
        $result = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>
                    <td>".$no++."</td>
                    <td>".$row['nama']."</td>
                    <td>".$row['nim']."</td>
                    <td>".$row['jurusan']."</td>
                    <td>
                        <a href='update.php?id=".$row['id']."'>Edit</a> |
                        <a href='delete.php?id=".$row['id']."' onclick='return confirm(\"Yakin hapus data?\")'>Hapus</a>
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>

    <script>
    $(document).ready(function(){
        $('#tabel-data').DataTable();
    });
    </script>
</body>
</html>
```

---

## 3. `create.php` (Tambah Data)

```php
<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $nama    = $_POST['nama'];
    $nim     = $_POST['nim'];
    $jurusan = $_POST['jurusan'];

    $sql = "INSERT INTO mahasiswa (nama, nim, jurusan) VALUES ('$nama','$nim','$jurusan')";
    mysqli_query($conn, $sql);

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Tambah Data</title></head>
<body>
    <h2>Tambah Data Mahasiswa</h2>
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

## 4. `update.php` (Edit Data)

```php
<?php
include 'config.php';
$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $nama    = $_POST['nama'];
    $nim     = $_POST['nim'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($conn, "UPDATE mahasiswa SET nama='$nama', nim='$nim', jurusan='$jurusan' WHERE id=$id");

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Edit Data</title></head>
<body>
    <h2>Edit Data Mahasiswa</h2>
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

## 5. `delete.php`

```php
<?php
include 'config.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM mahasiswa WHERE id=$id");

header("Location: index.php");
?>
```

---

## 6. Struktur Database

Buat database `db_crud` dengan tabel `mahasiswa`:

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
