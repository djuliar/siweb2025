<?php
include 'db.php';
if(isset($_POST['kabupaten_id'])){
    $kabupaten_id = $_POST['kabupaten_id'];
    $query = "SELECT * FROM kecamatan WHERE kota_id = $kabupaten_id";
    $result = mysqli_query($conn, $query);

    echo '<option value="">Pilih Kecamatan</option>';
    while($row = mysqli_fetch_assoc($result)){
        echo "<option value='".$row['id']."'>".$row['nama']."</option>";
    }
}