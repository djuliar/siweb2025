<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

// === Ambil template HTML ===
$template = file_get_contents("template.php");

// === Generate PDF ===
$dompdf = new Dompdf();
$dompdf->loadHtml($template);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("laporan_mahasiswa.pdf", ["Attachment" => false]);