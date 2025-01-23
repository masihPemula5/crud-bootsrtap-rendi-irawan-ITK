<?php
include 'koneksi.php';

$delete_sql = "DELETE FROM mahasiswa_backup";
if ($conn->query($delete_sql) === TRUE) {
    echo "Data berhasil dihapus";
    header("Location: crud.php?success=true"); 
exit();
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>
