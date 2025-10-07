<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Upload File dengan Database</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      padding: 30px;
    }
    .container {
      background: white;
      width: 600px;
      margin: auto;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    input[type="file"] {
      display: block;
      margin: 20px auto;
    }
    button {
      width: 100%;
      background: #007BFF;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #0056b3;
    }
    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #007BFF;
      color: white;
    }
    a {
      color: #007BFF;
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Upload File (Simpan ke Database)</h2>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Upload Sekarang</button>
  </form>

  <h3>Daftar File Upload</h3>
  <table>
    <tr>
      <th>No</th>
      <th>Nama Asli</th>
      <th>Nama Tersimpan</th>
      <th>Tipe</th>
      <th>Ukuran (KB)</th>
      <th>Tanggal Upload</th>
      <th>Aksi</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM files ORDER BY id DESC");
    if ($result->num_rows > 0):
        $no = 1;
        while ($row = $result->fetch_assoc()):
    ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['original_name']) ?></td>
      <td><?= htmlspecialchars($row['new_name']) ?></td>
      <td><?= htmlspecialchars($row['mime_type']) ?></td>
      <td><?= round($row['size']/1024, 2) ?></td>
      <td><?= $row['upload_date'] ?></td>
      <td><a href="uploads/<?= urlencode($row['new_name']) ?>" target="_blank">Lihat</a></td>
    </tr>
    <?php endwhile; else: ?>
    <tr><td colspan="7">Belum ada file diunggah.</td></tr>
    <?php endif; ?>
  </table>
</div>

</body>
</html>