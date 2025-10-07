<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat object spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Isi data ke cell
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nama');
$sheet->setCellValue('C1', 'Email');

for ($i=2; $i <= 10 ; $i++) { 
    $sheet->setCellValue('A'.$i, '1');
    $sheet->setCellValue('B'.$i, 'Data '.$i);
    $sheet->setCellValue('C'.$i, 'data'.$i.'@example.com');
}

// Simpan sebagai file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('data.xlsx');

echo "File Excel berhasil dibuat!";