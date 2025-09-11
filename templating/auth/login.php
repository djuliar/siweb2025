<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow p-4">
                <h3 class="text-center mb-3">Login</h3>
                <?php 
                if (!empty($_SESSION['success'])) {
                    echo "<div class='alert alert-success'>".$_SESSION['success']."</div>";
                    unset($_SESSION['success']);
                }
                if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; 
                ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" required class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" required class="form-control">
                    </div>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Register</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
