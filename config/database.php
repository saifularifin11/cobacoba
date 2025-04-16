<?php
// Deklarasi parameter koneksi database PostgreSQL
$host     = "localhost";   // Server database, default “localhost” atau “127.0.0.1”
$port     = "5432";        // Port PostgreSQL, default “5432”
$dbname   = "db_survei";   // Nama database yang digunakan
$username = "postgres";    // Username PostgreSQL, default "postgres"
$password = "lufias112"; // Ganti dengan password PostgreSQL Anda

// Buat string koneksi
$conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";

// Buat koneksi database
$pg_conn = pg_connect($conn_string);

// Cek koneksi
if (!$pg_conn) {
    die("Koneksi Database Gagal: " . pg_last_error());
} else {
    echo "Koneksi berhasil ke PostgreSQL!";
}
?>
