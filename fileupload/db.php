<?php
// config.php

$host = 'localhost';
$user = 'root';      // sesuaikan
$pass = '';          // sesuaikan
$db   = 'auth_db'; // nama database

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
?>