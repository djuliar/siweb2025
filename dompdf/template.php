<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
    h1 { margin: 0; font-size: 18px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 6px; font-size: 12px; }
    th { background: #f2f2f2; }
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Data Mahasiswa</h1>
    <p>Politeknik Negeri Contoh - Tahun 2025</p>
</div>

<p><strong>Tanggal Cetak:</strong> {{tanggal}}</p>

<table>
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:30%;">Nama</th>
            <th style="width:20%;">NIM</th>
            <th style="width:20%;">Prodi</th>
            <th style="width:25%;">Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Nama</td>
            <td>NIM</td>
            <td>Prodi</td>
            <td>Status</td>
        </tr>
    </tbody>
</table>
</body>
</html>