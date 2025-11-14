<?php
// signup_process.php

// 1. Konfigurasi koneksi
$host = "localhost";
$user = "root";          // default XAMPP/WAMP
$pass = "";              // kosong kalau belum di-set
$db   = "threekost_db";  // sesuaikan dengan nama DB kamu

// 2. Konek ke MySQL
$conn = new mysqli($host, $user, $pass, $db);

// Cek error koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Ambil data dari form
$full_name = $_POST['full_name'] ?? '';
$email     = $_POST['email'] ?? '';

// 4. Validasi sederhana
if (empty($full_name) || empty($email)) {
    die("Full name dan email wajib diisi.");
}

// (opsional) validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}

// 5. Simpan ke database pakai prepared statement (lebih aman)
$stmt = $conn->prepare("INSERT INTO users (full_name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $full_name, $email);

if ($stmt->execute()) {
    // sukses → bisa redirect ke halaman lain
    // header("Location: success.html");
    echo "Sign up berhasil! Data tersimpan di database.";
} else {
    // contoh: email duplikat, dsb
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
