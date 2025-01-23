<?php
session_start();
?>

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

include 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Email atau password kosong!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php';
            }
        });

        </script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: crud/crud.php');
        exit();
    }  else {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Password salah!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
            </script>";
        }
    } else {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Email tidak ditemukan!',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php';
            }
        });
        </script>";
    }
}
?>
```