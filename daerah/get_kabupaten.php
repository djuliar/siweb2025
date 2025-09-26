<?php
include 'db.php';
if(isset($_POST['provinsi_id'])){
    $provinsi_id = $_POST['provinsi_id'];
    $query = "SELECT * FROM kota WHERE provinsi_id = $provinsi_id";
    $result = mysqli_query($conn, $query);
    
    echo '<option value="">Pilih Kabupaten</option>';
    while($row = mysqli_fetch_assoc($result)){
        echo "<option value='".$row['id']."'>".$row['nama']."</option>";
    }
}