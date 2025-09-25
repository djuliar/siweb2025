Kasus: Pilih **Provinsi → Kota → Kecamatan**.

---

## 1. Struktur Database (MySQL)

Buat 3 tabel sederhana:

```sql
CREATE TABLE provinsi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

CREATE TABLE kota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provinsi_id INT,
    nama VARCHAR(100) NOT NULL,
    FOREIGN KEY (provinsi_id) REFERENCES provinsi(id)
);

CREATE TABLE kecamatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kota_id INT,
    nama VARCHAR(100) NOT NULL,
    FOREIGN KEY (kota_id) REFERENCES kota(id)
);
```

Contoh data dummy:

```sql
INSERT INTO provinsi (nama) VALUES ('Jawa Timur'), ('Jawa Barat');

INSERT INTO kota (provinsi_id, nama) VALUES 
(1, 'Surabaya'), (1, 'Malang'),
(2, 'Bandung'), (2, 'Bekasi');

INSERT INTO kecamatan (kota_id, nama) VALUES
(1, 'Wonokromo'), (1, 'Rungkut'),
(2, 'Klojen'), (2, 'Lowokwaru'),
(3, 'Coblong'), (3, 'Cicendo'),
(4, 'Medansatria'), (4, 'Bekasi Timur');
```

---

## 2. File `index.php`

```php
<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Combo Box Ketergantungan</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Pilih Provinsi, Kota, Kecamatan</h2>

    <form>
        <label>Provinsi:</label>
        <select id="provinsi">
            <option value="">-- Pilih Provinsi --</option>
            <?php
            $sql = mysqli_query($conn, "SELECT * FROM provinsi");
            while($row = mysqli_fetch_assoc($sql)){
                echo "<option value='".$row['id']."'>".$row['nama']."</option>";
            }
            ?>
        </select>

        <br><br>

        <label>Kota:</label>
        <select id="kota">
            <option value="">-- Pilih Kota --</option>
        </select>

        <br><br>

        <label>Kecamatan:</label>
        <select id="kecamatan">
            <option value="">-- Pilih Kecamatan --</option>
        </select>
    </form>

    <script>
        // Jika provinsi dipilih
        $("#provinsi").change(function(){
            var provinsi_id = $(this).val();
            $.ajax({
                url: "get_kota.php",
                type: "POST",
                data: {provinsi_id: provinsi_id},
                success: function(data){
                    $("#kota").html(data);
                    $("#kecamatan").html('<option value="">-- Pilih Kecamatan --</option>');
                }
            });
        });

        // Jika kota dipilih
        $("#kota").change(function(){
            var kota_id = $(this).val();
            $.ajax({
                url: "get_kecamatan.php",
                type: "POST",
                data: {kota_id: kota_id},
                success: function(data){
                    $("#kecamatan").html(data);
                }
            });
        });
    </script>
</body>
</html>
```

---

## 3. File `koneksi.php`

```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_combo";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

---

## 4. File `get_kota.php`

```php
<?php
include "koneksi.php";

if(isset($_POST['provinsi_id'])){
    $provinsi_id = $_POST['provinsi_id'];
    $sql = mysqli_query($conn, "SELECT * FROM kota WHERE provinsi_id='$provinsi_id'");
    echo "<option value=''>-- Pilih Kota --</option>";
    while($row = mysqli_fetch_assoc($sql)){
        echo "<option value='".$row['id']."'>".$row['nama']."</option>";
    }
}
?>
```

---

## 5. File `get_kecamatan.php`

```php
<?php
include "koneksi.php";

if(isset($_POST['kota_id'])){
    $kota_id = $_POST['kota_id'];
    $sql = mysqli_query($conn, "SELECT * FROM kecamatan WHERE kota_id='$kota_id'");
    echo "<option value=''>-- Pilih Kecamatan --</option>";
    while($row = mysqli_fetch_assoc($sql)){
        echo "<option value='".$row['id']."'>".$row['nama']."</option>";
    }
}
?>
```