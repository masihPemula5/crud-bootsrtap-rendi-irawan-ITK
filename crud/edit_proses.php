<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <title>Document</title>
    <style>
        * {
            font-family: "Roboto", sans-serif;
            font-weight: 400;
        }
    </style>
</head>

<body>

</body>

</html>
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';

$id = $_POST['id_mhs'];
$nama = $_POST['nama'];
$nim = $_POST['nim'];
$email = $_POST['email'];
$nomor = $_POST['nomor'];
$jurusan = $_POST['id_jurusan'];

// Cek apakah NIM sudah ada di database dan bukan milik data yang sedang diedit
$query = "SELECT * FROM mahasiswa WHERE nim = '$nim' AND id_mhs != '$id'";
$result = mysqli_query($conn, $query);




if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'NIM sudah ada!',
            text: 'Masukkan NIM yang berbeda.',
            confirmButtonText: 'oke'
        }).then((result) => {
            window.location.href = 'crud.php?id=$id';
        });
    </script>";
} else {
    // Cek apakah email sudah ada di database dan bukan milik data yang sedang diedit
    $query_email = "SELECT * FROM mahasiswa WHERE email = '$email' AND id_mhs != '$id'";
    $result_email = mysqli_query($conn, $query_email);

    if (!$result_email) {
        die("Query error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result_email) > 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Email sudah ada!',
                text: 'Masukkan email yang berbeda.',
                confirmButtonText: 'oke'
            }).then((result) => {
                window.location.href = 'crud.php?id=$id';
            });
        </script>";
    } else {
        // Cek apakah nomor sudah ada di database dan bukan milik data yang sedang diedit
        $query_nomor = "SELECT * FROM mahasiswa WHERE nomor = '$nomor' AND id_mhs != '$id'";
        $result_nomor = mysqli_query($conn, $query_nomor);

        if (!$result_nomor) {
            die("Query error: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result_nomor) > 0) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Nomor sudah ada!',
                    text: 'Masukkan nomor yang berbeda.',
                    confirmButtonText: 'oke'
                }).then((result) => {
                    window.location.href = 'crud.php?id=$id';
                });
            </script>";
            exit;
        } 
        // Validasi format nomor telepon
        $pattern = "/^08[0-9]{8,13}$/";
        if (!preg_match($pattern, $nomor)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Nomor Telepon Tidak valid',
                    text: 'Masukkan dengan benar.',
                    confirmButtonText: 'oke'
                }).then((result) => {
                    window.location.href = 'crud.php?id=$id';
                });
            </script>";
            exit;
        } else {
            // Update data jika semua validasi lolos
            $update = "UPDATE mahasiswa SET nama='$nama', nim='$nim', email='$email', nomor='$nomor', id_jurusan='$jurusan' WHERE id_mhs='$id'";
            if (mysqli_query($conn, $update)) {
                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Data berhasil diupdate!',
                    showConfirmButton: false,
                    timer: 1200,
                }).then((result) => {
                    window.location.href = 'crud.php';
                });
            </script>";
            } else {
                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan!',
                    text: 'Data gagal diupdate.',
                    confirmButtonText: 'oke'
                }).then((result) => {
                    window.location.href = 'crud.php?id=$id';
                });
            </script>";
            }
        }
    }
}

mysqli_close($conn);


?>