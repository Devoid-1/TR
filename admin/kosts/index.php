<?php
require '../../config.php';

// --- FLAG WELCOME POPUP SETELAH SIGNUP/LOGIN ---
$showWelcome = !empty($_SESSION['show_welcome']);
$welcomeName = $_SESSION['user_name'] ?? 'User';
if ($showWelcome) {
    // supaya popup hanya sekali
    unset($_SESSION['show_welcome']);
}

$loginError = $_SESSION['login_error'] ?? '';
$openLoginModal = !empty($_SESSION['login_open']);
if ($openLoginModal) {
    unset($_SESSION['login_open']);
}
unset($_SESSION['login_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'delete') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($id > 0) {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $stmt = $conn->prepare("DELETE FROM kosts WHERE id = ?");
                $stmt->bind_param("i", $id);
            } else {
                $userId = $_SESSION['user_id'] ?? 0;
                $stmt = $conn->prepare("DELETE FROM kosts WHERE id = ? AND user_id = ?");
                $stmt->bind_param("ii", $id, $userId);
            }

            $stmt->execute();
            $stmt->close();
            $_SESSION['flash_success'] = 'Kost berhasil dihapus.';
        }

        header('Location: admin.php');
        exit;
    }
    
    if ($action === 'add_kost') {
        $user_id = isset($_POST['host_id']) && $_POST['host_id'] !== ''
            ? (int)$_POST['host_id']
            : ($_SESSION['user_id'] ?? 0);

        if ($user_id <= 0) {
            $_SESSION['flash_errors'] = ['Pilih host untuk kost ini.'];
            header('Location: admin.php');
            exit;
        }

        $name           = trim($_POST['name'] ?? '');
        $city           = trim($_POST['city'] ?? '');
        $address        = trim($_POST['address'] ?? '');
        $price_month    = (int)($_POST['price_month'] ?? 0);
        $room_total     = (int)($_POST['room_total'] ?? 0);
        $room_available = (int)($_POST['room_available'] ?? 0);
        $bathroom_type  = $_POST['bathroom_type'] ?? 'private';
        $gender_type    = $_POST['gender_type'] ?? 'mixed';
        $description    = $_POST['description'] ?? '';

        $facilities = isset($_POST['facilities']) ? (array)$_POST['facilities'] : [];
        $parking  = in_array('parking', $facilities) ? 1 : 0;
        $wifi     = in_array('wifi', $facilities) ? 1 : 0;
        $ac       = in_array('ac', $facilities) ? 1 : 0;
        $kitchen  = in_array('kitchen', $facilities) ? 1 : 0;

        // Upload gambar utama
        $imagePath = '';
        if (!empty($_FILES['main_image']['name']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'imgfilter/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName  = time() . '_' . basename($_FILES['main_image']['name']);
            $imagePath = $uploadDir . $fileName;
            move_uploaded_file($_FILES['main_image']['tmp_name'], $imagePath);
        }

        if ($imagePath === '') {
            $imagePath = '';
        }

        $stmt = $conn->prepare("INSERT INTO kosts (
                user_id,
                name,
                city,
                address,
                price_month,
                bathroom_type,
                parking,
                wifi,
                gender_type,
                ac,
                kitchen,
                room_total,
                room_available,
                main_image,
                description
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->bind_param(
            "isssisiisiiiiss",
            $user_id,
            $name,
            $city,
            $address,
            $price_month,
            $bathroom_type,
            $parking,
            $wifi,
            $gender_type,
            $ac,
            $kitchen,
            $room_total,
            $room_available,
            $imagePath,
            $description
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['flash_success'] = 'Kost baru berhasil dibuat.';
        header('Location: index.php');
        exit;
    }
}


$sql = "SELECT * FROM kosts ORDER BY created_at DESC";
$result = $conn->query($sql);
$kosts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kosts[] = $row;
    }
}

// // var_dump($kosts);
// $conn->close();



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
