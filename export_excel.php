<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_survei";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$query = "SELECT id, nilai, ulasan, foto, waktu FROM tbl_survei ORDER BY id DESC";
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header Excel
$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Nilai')
      ->setCellValue('C1', 'Ulasan')
      ->setCellValue('D1', 'Foto')
      ->setCellValue('E1', 'Waktu');

$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue("A$row", $data['id'])
          ->setCellValue("B$row", $data['nilai'])
          ->setCellValue("C$row", $data['ulasan'])
          ->setCellValue("D$row", $data['foto'])
          ->setCellValue("E$row", $data['waktu']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$fileName = "Data_Survei.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
$conn->close();
exit();
?>
