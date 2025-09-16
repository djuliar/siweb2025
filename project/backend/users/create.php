<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    include 'db.php';
    
    if (isset($_POST['submit'])) {
        $username      = $_POST['username'];
        $password      = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $name          = $_POST['name'];
        $email         = $_POST['email'];
        $address       = $_POST['address'];
        $phone         = $_POST['phone'];

        $sql = "INSERT INTO users (username, password, name, email, address, phone) VALUES ('$username','$password','$name','$email','$address','$phone')";
        mysqli_query($conn, $sql);

        header("Location: index.php");
    }
    ?>
    <h2>Tambah Data User</h2>
    <form method="post">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Address: <textarea name="address" row="3"></textarea><br>
        Phone: <input type="text" name="phone" required><br>
        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>