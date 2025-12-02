<?php
// login_process.php
require 'config.php'; // sudah termasuk session_start dan koneksi $conn

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$login_type = $_POST['login_type'] ?? 'phone';

// Helper untuk simpan error dan kembali ke index
function back_with_error($msg) {
    $_SESSION['login_error'] = $msg;
    $_SESSION['login_open'] = true; // supaya login modal kebuka
    header("Location: index.php");
    exit;
}

if ($login_type === 'phone') {
    $country_code = trim($_POST['country_code'] ?? '+62');
    $phone        = trim($_POST['phone'] ?? '');

    if ($phone === '') {
        back_with_error("Phone number wajib diisi.");
    }

    // Gabung kode negara + nomor, misal: +6281234567890
    $fullPhone = $country_code . $phone;

    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE phone = ? LIMIT 1");
    $stmt->bind_param("s", $fullPhone);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        back_with_error("Phone number tidak ditemukan. Pastikan nomor sudah terdaftar.");
    }

    // Berhasil login via phone (anggap OTP dsb sudah dihandle)
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['full_name'] ?? 'User';
    $_SESSION['show_welcome'] = true;

    //cek id console log
    
    header("Location: index.php");
    exit;
}

// ===== LOGIN DENGAN EMAIL + PASSWORD =====
$email    = trim($_POST['email_login'] ?? '');
$password = $_POST['password_login'] ?? '';

if ($email === '' || $password === '') {
    back_with_error("Email dan password wajib diisi.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    back_with_error("Format email tidak valid.");
}

$stmt = $conn->prepare("SELECT id, full_name, password,role FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    back_with_error("Akun dengan email tersebut tidak ditemukan.");
}

if (!password_verify($password, $user['password'])) {
    back_with_error("Password salah.");
}

// Berhasil login
$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['full_name'] ?? 'User';
$_SESSION['role']      = $user['role'] ?? 'tenant';
$_SESSION['show_welcome'] = true;

// Redirect sesuai role
if ($_SESSION['role'] === 'admin') {
    header("Location: admin/dashboard/index.php");
} elseif ($_SESSION['role'] === 'host') {
    header("Location: admin/dashboard/index.php");
} else {
    header("Location: index.php");
}
exit;

