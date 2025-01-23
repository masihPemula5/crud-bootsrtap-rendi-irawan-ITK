<html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            *{
                font-family: sans-serif;
            }
        </style>
    </head>
</html>


<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }

    // Ambil data dari POST
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nomor = mysqli_real_escape_string($conn, $_POST['nomor']);
    $id_jurusan = mysqli_real_escape_string($conn, $_POST['id_jurusan']);

    $error_messages = [];

    // Validasi apakah NIM sudah ada di database
    $sql_cek = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
    $result = $conn->query($sql_cek);

    if ($result->num_rows > 0) {
    $error_messages[] = 'NIM sudah ada di database';
}

// Validasi email
$email_cek = "SELECT * FROM mahasiswa WHERE email = '$email'";
$result_email = $conn->query($email_cek);

if ($result_email->num_rows > 0) {
    $error_messages[] = 'Email sudah ada di database';
}

// Validasi nomor HP
$nomor_cek = "SELECT * FROM mahasiswa WHERE nomor = '$nomor'";
$result_nomor = $conn->query($nomor_cek);

if ($result_nomor->num_rows > 0) {
    $error_messages[] = 'Nomor Telepon sudah digunakan';
}

$pattern = "/^08[0-9]{8,13}$/";
if (!preg_match($pattern, $nomor)) {
    $error_messages[] = 'Format nomor telepon tidak valid';
}

// Validasi apakah jurusan_id ada di tabel jurusan
$jurusan_cek = "SELECT nama_jurusan FROM jurusan WHERE id_jurusan = '$id_jurusan'";
$result_jurusan = $conn->query($jurusan_cek);

if ($result_jurusan->num_rows === 0) {
    $error_messages[] = 'Jurusan tidak ditemukan';
}

// Jika ada error, tampilkan pesan error
if (!empty($error_messages)) {
    echo "<script>";
    foreach ($error_messages as $message) {
       echo "Swal.fire({title: 'Error', text: '$message', icon: 'error'}).then((result) => { if (result.isConfirmed) { window.location.href = 'crud.php'; } });";
    }
    echo "</script>";
    exit;
}

    // Jika ada error, tampilkan pesan error
    if (!empty($error_messages)) {
        foreach ($error_messages as $message) {
            echo "<p style='color: red;'>$message</p>";
        }
        exit;
    }

    // Ambil nama jurusan
    $jurusan_data = $result_jurusan->fetch_assoc();
    $nama_jurusan = $jurusan_data['nama_jurusan'];

    // Insert data ke tabel mahasiswa
    $sql = "INSERT INTO mahasiswa (nama, nim, email, nomor, id_jurusan) VALUES ('$nama', '$nim', '$email', '$nomor', '$id_jurusan')";

    if ($conn->query($sql) === TRUE) {
        // Ambil ID terakhir untuk digunakan di tabel
        $id = $conn->insert_id;

       header( 'Location: crud.php' ) ;
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }

    $conn->close();
}
?>