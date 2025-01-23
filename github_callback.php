<?php
session_start();
include 'db.php'; // Pastikan file koneksi database sudah ada dan benar

// Konfigurasi OAuth GitHub
$client_id = 'Ov23liiNNZql4o7llMgL'; // Ganti dengan Client ID Anda
$client_secret = '11f89e18aecaf6f567826ecb3d0ba3bb8d81918d'; // Ganti dengan Client Secret Anda
$redirect_uri = 'http://localhost/UAS/github_callback.php'; // Ganti dengan URL Callback Anda

// Jika kode otorisasi diterima
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Tukar kode otorisasi dengan token akses
    $token_url = 'https://github.com/login/oauth/access_token';
    $data = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $code,
        'redirect_uri' => $redirect_uri,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        $access_token = $token_data['access_token'];

        // Mengambil data user dari GitHub
        $user_api_url = 'https://api.github.com/user';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user_api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'User-Agent: revam_crud', // User-Agent wajib di GitHub API
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $user_info_response = curl_exec($ch);
        curl_close($ch);

        $user_info = json_decode($user_info_response, true);

        // Simpan data user di session
        if (isset($user_info['login'])) {
            $_SESSION['username'] = $user_info['login'];
            $_SESSION['email'] = $user_info['email'] ?? 'Email tidak tersedia';
            $_SESSION['avatar'] = $user_info['avatar_url'];

            // Simpan data user ke database
            $username = $_SESSION['username'];
            $email = $_SESSION['email'];

            // Cek apakah user sudah ada di database
            $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                // Jika user belum ada, tambahkan ke database
                $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
            }

            // Redirect ke halaman dashboard
            header('Location: crud/crud.php');
            exit();
        } else {
            echo "Gagal mendapatkan informasi user.";
        }
    } else {
        echo "Gagal mendapatkan token akses.";
    }
}
?>
