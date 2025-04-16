<?php
$host = "localhost";
$user = "root"; // Sesuaikan dengan user database
$pass = ""; // Jika ada password, tambahkan di sini
$dbname = "db_survei"; // Sesuaikan dengan nama database

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori = $_POST['kategori'];
    $ulasan = $_POST['ulasan'];
    $fotoData = $_POST['foto'];

    $folder = "uploads/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    // Nama file unik
    $filename = $folder . "foto_" . time() . ".png";

    // Simpan gambar ke folder server
    $foto = str_replace("data:image/png;base64,", "", $fotoData);
    $foto = base64_decode($foto);
    file_put_contents($filename, $foto);

    // Simpan ke database
    $sql = "INSERT INTO tbl_survei (kategori, ulasan, foto) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $kategori, $ulasan, $filename);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Foto berhasil disimpan!", "path" => $filename]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan foto."]);
    }

    $stmt->close();
    $conn->close();
}
?>
