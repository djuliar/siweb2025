Struktur folder lebih rapi:

```
/auth-oop
 ├── classes/
 │    ├── Database.php
 │    ├── User.php
 ├── index.php
 ├── login.php
 ├── register.php
 ├── logout.php
```

---

## 1. `classes/Database.php`

```php
<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "auth_oop";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
}
```

---

## 2. `classes/User.php`

```php
<?php
require_once "Database.php";

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    public function register($username, $password) {
        $username = $this->conn->real_escape_string($username);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $check = $this->conn->query("SELECT * FROM users WHERE username='$username'");
        if ($check->num_rows > 0) {
            return "Username sudah digunakan!";
        }

        $this->conn->query("INSERT INTO users (username, password) VALUES ('$username', '$passwordHash')");
        return true;
    }

    public function login($username, $password) {
        $username = $this->conn->real_escape_string($username);

        $result = $this->conn->query("SELECT * FROM users WHERE username='$username'");
        if ($result->num_rows == 0) {
            return "Username tidak ditemukan!";
        }

        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            return true;
        } else {
            return "Password salah!";
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function logout() {
        session_destroy();
    }
}
```

---

## 3. `register.php`

```php
<?php
session_start();
require_once "classes/User.php";
$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $user->register($username, $password);
    if ($result === true) {
        $_SESSION['success'] = "Registrasi berhasil, silakan login!";
        header("Location: login.php");
        exit();
    } else {
        $error = $result;
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
```

---

## 4. `login.php`

```php
<?php
session_start();
require_once "classes/User.php";
$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $user->login($username, $password);
    if ($result === true) {
        header("Location: index.php");
        exit();
    } else {
        $error = $result;
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
```

---

## 5. `index.php`

```php
<?php
session_start();
require_once "classes/User.php";
$user = new User();

if (!$user->isLoggedIn()) {
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
```

---

## 6. `logout.php`

```php
<?php
session_start();
require_once "classes/User.php";
$user = new User();
$user->logout();
header("Location: login.php");
exit();
```

---

## 7. SQL Table

```sql
CREATE DATABASE auth_oop;
USE auth_oop;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
);
```

---