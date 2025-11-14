<?php
// signup_process.php

session_start(); // penting: supaya bisa set $_SESSION

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
$full_name         = trim($_POST['full_name'] ?? '');
$email             = trim($_POST['email'] ?? '');
$password          = $_POST['password'] ?? '';
$confirm_password  = $_POST['confirm_password'] ?? '';

// 4. Validasi sederhana
if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
    die("Semua field wajib diisi.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}

if ($password !== $confirm_password) {
    die("Password dan konfirmasi password tidak sama.");
}

if (strlen($password) < 8) {
    die("Password minimal 8 karakter.");
}
if (!preg_match('/[A-Z]/', $password)) {
    die("Password harus mengandung minimal 1 huruf kapital.");
}
if (!preg_match('/[\d\W]/', $password)) {
    die("Password harus mengandung minimal 1 angka atau simbol.");
}
if (stripos($password, $email) !== false) {
    die("Password tidak boleh mengandung alamat email.");
}

// 5. Cek apakah email sudah terdaftar
$check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    $conn->close();
    echo "Email sudah terdaftar. Silakan gunakan email lain atau <a href='login.php'>login di sini</a>.";
    exit;
}
$check->close();

// 6. Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 7. Simpan ke database
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $full_name, $email, $password_hash);

if ($stmt->execute()) {
    // === SET SESSION UNTUK USER YANG BARU SIGN UP ===
    $_SESSION['user_id']   = $conn->insert_id;
    $_SESSION['user_name'] = $full_name;

    // flag untuk munculkan welcome popup di index.php
    $_SESSION['show_welcome'] = true;

    // redirect ke index
    header("Location: index.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
