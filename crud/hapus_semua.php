<?php
// Koneksi ke database
include 'koneksi.php';

// Backup data sebelum menghapus
$backup_sql = "INSERT INTO mahasiswa_backup SELECT * FROM mahasiswa";
$conn->query($backup_sql);

// Hapus data dari tabel utama
$delete_sql = "DELETE FROM mahasiswa";
if ($conn->query($delete_sql) === TRUE) {
    echo "Data berhasil dihapus";
    header("Location: crud.php?success=true"); 
exit();
} else {
    echo "Error: " . $conn->error;
}

?>