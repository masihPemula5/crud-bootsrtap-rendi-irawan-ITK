<?php

// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$database = "kampus";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
