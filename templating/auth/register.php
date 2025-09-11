<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $conn->query("INSERT INTO users(username, password) VALUES('$username','$password')");
        $_SESSION['success'] = "Registrasi berhasil, silakan login!";
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow p-4">
                <h3 class="text-center mb-3">Register</h3>
                <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" required class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" required class="form-control">
                    </div>
                    <button class="btn btn-primary w-100">Register</button>
                </form>
                <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
