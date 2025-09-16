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

    $id = $_GET['id'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    $data = mysqli_fetch_assoc($result);
    
    if (isset($_POST['submit'])) {
        $username      = $_POST['username'];
        // $password      = password_verify($_POST['password']);
        $name          = $_POST['name'];
        $email         = $_POST['email'];
        $address       = $_POST['address'];
        $phone         = $_POST['phone'];

        $sql = "UPDATE users SET username='$username', password='$password', name='$name', email='$email', address='$address', phone='$phone' WHERE id=$id";
        mysqli_query($conn, $sql);

        header("Location: index.php");
    }
    ?>
    <h2>Tambah Data User</h2>
    <form method="post">
        Username: <input type="text" name="username" value="<?php echo $data['username'] ?>" required><br>
        Password: <input type="text" name="password"><br>
        Name: <input type="text" name="name"  value="<?php echo $data['name'] ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $data['email'] ?>" required><br>
        Address: <textarea name="address" row="3"><?php echo $data['address'] ?></textarea><br>
        Phone: <input type="text" name="phone" value="<?php echo $data['phone'] ?>" required><br>
        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>