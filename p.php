<?php
include 'koneksi.php'; // Pastikan file ini sudah benar
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


$username = $_SESSION['username']; // Ambil id_user dari session
$avatar = $_FILES['avatar'];

if ($avatar['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $file_name = uniqid() . "_" . basename($avatar['name']);
    $target_file = $target_dir . $file_name;

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($avatar['tmp_name'], $target_file)) {
        echo "File berhasil diunggah ke: " . $target_file;

        // Simpan nama file ke database
        $query = "UPDATE users SET profile_pict = '$target_file' WHERE id_user = '$username'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "Avatar berhasil diperbarui di database.";
            header('location: crud.php');
        } else {
            echo "Query gagal: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal memindahkan file.";
    }
} else {
    echo "Tidak ada file yang diunggah.";
}
