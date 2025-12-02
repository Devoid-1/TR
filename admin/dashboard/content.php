<?php
    require '../../config.php';
// $roleValue = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'host' : 'tenant';
// var_dump($_SESSION['role']);

$showWelcome = !empty($_SESSION['show_welcome']);
$welcomeName = $_SESSION['user_name'] ?? 'User';
if ($showWelcome) {
    unset($_SESSION['show_welcome']);
}

$loginError = $_SESSION['login_error'] ?? '';
$openLoginModal = !empty($_SESSION['login_open']);
if ($openLoginModal) {
    unset($_SESSION['login_open']);
}
unset($_SESSION['login_error']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($action === 'add_host') {
        $full_name = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $password  = $_POST['password'] ?? '';

        $errors = [];

        if ($full_name === '' || $email === '' || $password === '') {
            $errors[] = 'Nama lengkap, email, dan password wajib diisi.';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }

        // Cek email / phone sudah dipakai atau belum
        if (!$errors) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
            $stmt->bind_param("ss", $email, $phone);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors[] = 'Email atau nomor telepon sudah digunakan.';
            }

            $stmt->close();
        }

        if ($errors) {
            $_SESSION['flash_errors'] = $errors;
        } else {
            // Sesuaikan dengan cara hash password di project-mu
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $photo = 'uploads/default.jpg'; // sesuaikan dengan default foto di project Anda

            if($_SESSION['role'] === 'admin'){
                $roleValue = 'host';
            } else {
                $roleValue = 'tenant';
            }

            $stmt = $conn->prepare("
                INSERT INTO users (full_name, email, phone, photo, password, role)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("ssssss", $full_name, $email, $phone, $photo, $passwordHash, $roleValue);
            $stmt->execute();
            $stmt->close();

            $_SESSION['flash_success'] = 'Host baru berhasil dibuat.';
        }

        header('Location: index.php');
        exit;
    }
}
?>