<?php
include 'koneksi.php';

// Validasi input ID
if (isset($_GET['id_mhs']) && is_numeric($_GET['id_mhs'])) {
    $id = $_GET['id_mhs'];

    // Periksa apakah data ada di database sebelum menghapus
    $cek_sql = "SELECT id_mhs FROM mahasiswa WHERE id_mhs = $id";
    $result = $conn->query($cek_sql);

    if ($result->num_rows > 0) {
        // Jika data ditemukan, hapus
        $sql = "DELETE FROM mahasiswa WHERE id_mhs=$id";

        if ($conn->query($sql) === TRUE) {
            // Redirect ke halaman setelah berhasil
            header("Location: crud.php");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Data dengan ID $id tidak ditemukan.";
    }
} else {
    echo "ID tidak valid.";
}

$conn->close();
?>
