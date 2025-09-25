## 1. Struktur Database (MySQL)

Misalnya tabel `produk`:

```sql
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

INSERT INTO produk (nama) VALUES
('Laptop Asus'),
('Laptop Acer'),
('Laptop Lenovo'),
('Handphone Samsung'),
('Handphone Xiaomi'),
('Handphone iPhone'),
('Printer Canon'),
('Printer Epson'),
('Monitor LG'),
('Monitor Dell');
```

---

## 2. File `index.php`

```php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Live Search AJAX</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #result {
            border: 1px solid #ddd;
            max-width: 300px;
            background: #fff;
            position: absolute;
            z-index: 1000;
        }
        .item {
            padding: 8px;
            cursor: pointer;
        }
        .item:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2>Live Search Produk</h2>
    <input type="text" id="search" placeholder="Ketik nama produk..." autocomplete="off">
    <div id="result"></div>

    <script>
        $(document).ready(function(){
            $("#search").keyup(function(){
                var query = $(this).val();
                if(query != ""){
                    $.ajax({
                        url: "search.php",
                        method: "POST",
                        data: {query: query},
                        success: function(data){
                            $("#result").html(data);
                        }
                    });
                } else {
                    $("#result").html("");
                }
            });

            // Klik pada hasil pencarian
            $(document).on("click", ".item", function(){
                $("#search").val($(this).text());
                $("#result").html("");
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
$db   = "db_ajax";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

---

## 4. File `search.php`

```php
<?php
include "koneksi.php";

if(isset($_POST['query'])){
    $search = $_POST['query'];
    $sql = mysqli_query($conn, "SELECT * FROM produk WHERE nama LIKE '%$search%' LIMIT 5");

    if(mysqli_num_rows($sql) > 0){
        while($row = mysqli_fetch_assoc($sql)){
            echo "<div class='item'>".$row['nama']."</div>";
        }
    } else {
        echo "<div class='item'>Tidak ditemukan</div>";
    }
}
?>
```