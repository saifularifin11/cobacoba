<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_survei";

header("Content-Type: application/json");

$conn = new mysqli($servername, $username, $password, $dbname, 3306);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $conn->connect_error]));
}
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori = $_POST['kategori'] ?? '';
    $ulasan = $_POST['ulasan'] ?? '';
    $fotoBase64 = $_POST['foto'] ?? '';
    $fotoPath = null;

    if (empty($kategori) || empty($ulasan)) {
        die(json_encode(["status" => "error", "message" => "Kategori dan ulasan wajib diisi."]));
    }

    // Jika ada gambar, simpan ke folder uploads/
    if (!empty($fotoBase64)) {
        if (preg_match('/^data:image\/(png|jpeg|gif);base64,/', $fotoBase64, $match)) {
            $ext = $match[1] === "jpeg" ? "jpg" : $match[1];  // Ubah jpeg ke jpg
            $fotoBase64 = substr($fotoBase64, strpos($fotoBase64, ",") + 1);
            $fotoBase64 = base64_decode($fotoBase64);
            
            if ($fotoBase64 === false) {
                die(json_encode(["status" => "error", "message" => "Format gambar tidak valid."]));
            }

            $uploadDir = "uploads/";
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
                die(json_encode(["status" => "error", "message" => "Gagal membuat folder upload."]));
            }

            $fileName = $uploadDir . uniqid("img_") . "." . $ext;
            if (!file_put_contents($fileName, $fotoBase64)) {
                die(json_encode(["status" => "error", "message" => "Gagal menyimpan gambar."]));
            }
            $fotoPath = $fileName;
        } else {
            die(json_encode(["status" => "error", "message" => "Format gambar tidak didukung."]));
        }
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO tbl_survei (nilai, ulasan, foto) VALUES (?, ?, ?)");
    if (!$stmt) {
        die(json_encode(["status" => "error", "message" => "Query gagal: " . $conn->error]));
    }

    $stmt->bind_param("sss", $kategori, $ulasan, $fotoPath);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data berhasil disimpan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan ke database: " . $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?>
