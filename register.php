
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
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'] ,PASSWORD_BCRYPT);

    $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if (mysqli_query($conn, $query)) {
        // Simpan username ke session
        $_SESSION['username'] = $username;
        
        // JavaScript untuk redirect menggunakan Swal.fire
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil!',
                text: 'Silakan login.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        </script>";
    } else {
        // Tampilkan error jika query gagal
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '" . mysqli_error($conn) . "',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>
