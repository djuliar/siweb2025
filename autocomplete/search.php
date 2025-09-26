<?php
include 'db.php';

if(isset($_POST['query'])){
    $query = $_POST['query'];
    $sql = "SELECT * FROM produk WHERE nama LIKE '%$query%'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            echo "<div class='item'>".$row['nama']."</div>";
        }
    } else {
        echo "<div class='item'>Tidak ada hasil</div>";
    }
}