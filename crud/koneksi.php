<?php
// Konfigurasi database
$servername = "localhost"; 
$username = "root";
$password = ""; 
$database = "kampus"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
