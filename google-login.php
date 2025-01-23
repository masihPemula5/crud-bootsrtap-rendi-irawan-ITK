<?php
session_start();
include 'db.php'; // Pastikan file koneksi database tersedia dan benar

if (isset($_GET['code'])) {
    $client_id = '276617149835-tu30ido5i0rn1q8jnmm6nnkiouqk7jja.apps.googleusercontent.com';
    $client_secret = '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';
    $redirect_uri = 'http://localhost/UAS/google-login.php';

    // Tukar kode otorisasi dengan token akses
    $token_request = [
        'code' => $_GET['code'],
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_request));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        $access_token = $token_data['access_token'];

        // Ambil data user dari Google
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $user_info_response = curl_exec($ch);
        curl_close($ch);

        $user_info = json_decode($user_info_response, true);

        if (isset($user_info['email']) && isset($user_info['name'])) {
            // Simpan data user di session
            $_SESSION['email'] = $user_info['email'];
            $_SESSION['username'] = $user_info['name'];

            // Simpan data user ke database
            $email = $user_info['email'];
            $username = $user_info['name'];

            // Cek apakah user sudah ada di database
            $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                // Jika belum ada, simpan data ke database
                $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
            }

            // Redirect ke halaman dashboard
            header('Location: crud/crud.php');
            exit();
        } else {
            echo 'Error retrieving user info.';
        }
    } else {
        echo 'Error retrieving access token.';
    }
}
?>
