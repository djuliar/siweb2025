<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3>Selamat Datang, <?= $_SESSION['user'] ?> 🎉</h3>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>
</div>
</body>
</html>
