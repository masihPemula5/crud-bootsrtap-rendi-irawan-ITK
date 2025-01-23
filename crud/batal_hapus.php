<?php
include 'koneksi.php';

// Kembalikan data dari tabel backup ke tabel utama
$restore_sql = "INSERT INTO mahasiswa SELECT * FROM mahasiswa_backup";
if ($conn->query($restore_sql) === TRUE) {
    // Kosongkan tabel backup setelah restore
    $conn->query("DELETE FROM mahasiswa_backup");
    echo "Data berhasil dikembalikan.";
    header("Location: crud.php");
} else {
    echo "Error: " . $conn->error;
}

?>

