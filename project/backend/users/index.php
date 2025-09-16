<!DOCTYPE html>
<?php //session_start(); 
include 'db.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.css">
</head>
<body>
    <a href="create.php" class="btn btn-primary">Tambah</a>
    <table border="1" id="tabel-data" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a> 
                    <a href="delete.php?id=<?php echo $row['id']; ?>"
                    class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

   <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
   <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
   <script>
    $(document).ready(function(){
        $('#tabel-data').DataTable({
            "order": [[0, "desc"]],
        });
    });
    </script>
</body>
</html>