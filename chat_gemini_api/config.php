<?php
// API Key Gemini
define("GEMINI_API_KEY", "AIzaSyCT_r88UVijSwkVtGlWMBtbW-v53qE-d3g");

// Koneksi database MySQL
$host = "localhost";
$user = "root";      // ganti sesuai user MySQL
$pass = "";          // ganti sesuai password MySQL
$db   = "gemini_chat";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>