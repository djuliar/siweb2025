<?php
// API Key Gemini
define("GEMINI_API_KEY", "AIzaSyDZg4iZFFS6r83xxsmDJclkwbHN7Ae1ZxM");

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