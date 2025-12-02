<?php
require '../../config.php';


$showWelcome = !empty($_SESSION['show_welcome']);
$welcomeName = $_SESSION['user_name'] ?? 'User';
if ($showWelcome) {
    unset($_SESSION['show_welcome']);
}

if($_SESSION['role'] === 'admin'){
    $roleValue = 'host';
} else {
    $roleValue = 'tenant';
}

$loginError = $_SESSION['login_error'] ?? '';
$openLoginModal = !empty($_SESSION['login_open']);
if ($openLoginModal) {
    unset($_SESSION['login_open']);
}
unset($_SESSION['login_error']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_host') {
        $full_name = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $password  = $_POST['password'] ?? '';
        $kostId    = isset($_POST['kost_id']) ? (int)$_POST['kost_id'] : 0;

        $errors = [];

        if ($full_name === '' || $email === '' || $password === '') {
            $errors[] = 'Nama lengkap, email, dan password wajib diisi.';
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }

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
            header('Location: index.php');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $photo = 'uploads/default.jpg';

        $stmt = $conn->prepare("
            INSERT INTO users (full_name, email, phone, photo, password, role, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssi",
            $full_name,
            $email,
            $phone,
            $photo,
            $passwordHash,
            $roleValue,
            $_SESSION['user_id']     // admin yang menambahkan
        );
        $stmt->execute();
        $newUserId = $stmt->insert_id;
        $stmt->close();

        // ==========================
        // 3. INSERT BOOKING
        // ==========================
        if ($kostId > 0) {
            $sqlBooking = "INSERT INTO bookings
                (user_id, kost_id, full_name, nik, phone_number, monthly_rent, deposit_amount, payment_status, payment_method, created_at)
                VALUES (?, ?, ?, '', ?, 0, 0, 'pending', 'cash', NOW())";

            $stmt = $conn->prepare($sqlBooking);
            $stmt->bind_param("iiss",
                $newUserId,
                $kostId,
                $full_name,
                $phone
            );
            $stmt->execute();
            $stmt->close();
        }

        $_SESSION['flash_success'] = 'Host dan Booking berhasil dibuat.';
        header('Location: index.php');
        exit;
    }

}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-100">
    <div class="min-h-screen flex">
      <!-- SIDEBAR -->
      <?php 
        @include('../component/sidebar.php')
      ?>

      <!-- CONTENT -->
      <?php
        @include('content.php')
      ?>
    </div>
  </body>
</html>

