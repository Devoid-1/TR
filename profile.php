<?php
require 'config.php';

// Kalau belum login → balik ke halaman utama
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$uploadError = "";

// ========= HANDLE UPLOAD FOTO =========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        // Batasan tipe file
        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        if (!array_key_exists($file['type'], $allowedMime)) {
            $uploadError = "Format file harus JPG, PNG, atau WEBP.";
        } elseif ($file['size'] > 2 * 1024 * 1024) { // 2MB
            $uploadError = "Ukuran file maksimal 2MB.";
        } else {
            $ext = $allowedMime[$file['type']];
            $targetDir = __DIR__ . '/uploads';

            // Pastikan folder uploads ada
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            $targetPath = $targetDir . '/' . $fileName;
            $relativePath = 'uploads/' . $fileName; // ini yang disimpan ke DB

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Hapus foto lama (kalau ada)
                $stmtOld = $conn->prepare("SELECT photo FROM users WHERE id = ?");
                $stmtOld->bind_param("i", $user_id);
                $stmtOld->execute();
                $resultOld = $stmtOld->get_result();
                $old = $resultOld->fetch_assoc();
                $stmtOld->close();

                if ($old && !empty($old['photo'])) {
                    $oldPath = __DIR__ . '/' . $old['photo'];
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                // Update foto baru ke DB
                $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
                $stmt->bind_param("si", $relativePath, $user_id);
                $stmt->execute();
                $stmt->close();

                // Redirect biar refresh & hilangkan resubmit form
                header("Location: profile.php?upload=success");
                exit;
            } else {
                $uploadError = "Gagal mengunggah file. Coba lagi.";
            }
        }
    } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadError = "Terjadi kesalahan saat upload. Kode error: " . $file['error'];
    }
}

// ========= AMBIL DATA USER =========
// Pakai SELECT * biar aman (meski nanti kamu tambah kolom baru)
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Jika user tidak ditemukan (id tidak valid) → paksa logout
if (!$user) {
    header("Location: logout.php");
    exit;
}

$fullName = htmlspecialchars($user['full_name'] ?? 'User');
$email    = htmlspecialchars($user['email'] ?? '-');

if (isset($user['phone']) && $user['phone'] !== '') {
    $phone = htmlspecialchars($user['phone']);
} else {
    $phone = 'Not set yet';
}

$photoPath = (isset($user['photo']) && $user['photo'] !== '') ? htmlspecialchars($user['photo']) : null;
$uploadSuccess = (isset($_GET['upload']) && $_GET['upload'] === 'success');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Profile - ThreeKost</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <style>
      body {
        font-family: "Poppins", sans-serif;
      }
    </style>
  </head>
  <body class="bg-white text-gray-900">
    <!-- NAVBAR (sama seperti di index.php) -->
    <header class="fixed top-0 left-0 z-50 w-full bg-white shadow-md">
      <div
        class="flex items-center justify-between h-[70px] px-4 md:px-8 lg:px-16"
      >
        <div class="flex items-center">
          <a href="index.php">
            <img
              src="img/logo1.jpg"
              alt="ThreeKost Logo"
              class="h-12 md:h-16 w-auto object-contain"
            />
          </a>
        </div>

        <!-- Menu desktop -->
        <nav class="hidden md:flex space-x-6 text-gray-900 font-medium">
          <a href="#" class="hover:text-blue-600 transition">About Us</a>
          <a href="#" class="hover:text-blue-600 transition">Search Kost</a>
          <a href="#" class="hover:text-blue-600 transition">Wishlist</a>
          <a href="#" class="hover:text-blue-600 transition"
            >Download Mobile App</a
          >
        </nav>

        <!-- Kanan -->
        <div class="flex items-center gap-3">
          <button
            class="hidden sm:inline-flex items-center rounded-full bg-[#5f7cff] px-5 py-2 text-sm font-medium text-white hover:bg-[#4767e5] transition"
          >
            Become A Host
          </button>

          <!-- Burger hanya di mobile/tablet -->
          <i class="fas fa-bars text-2xl cursor-pointer md:hidden"></i>

          <!-- Icon user (sudah login → ke profile) -->
          <a
            href="profile.php"
            class="hidden md:inline-flex items-center justify-center"
          >
            <i class="fas fa-user-circle text-2xl cursor-pointer"></i>
          </a>
        </div>
      </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="mt-[80px] px-4 md:px-8 lg:px-16 pb-16">
      <div class="max-w-6xl mx-auto">
        <!-- Back + Title -->
        <div class="flex items-center gap-3 mt-4">
          <a
            href="index.php"
            class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 text-gray-700"
          >
            <i class="fa-solid fa-chevron-left"></i>
          </a>
          <h1 class="text-xl md:text-2xl font-semibold text-gray-800">
            Profile
          </h1>
        </div>

        <!-- Alert upload -->
        <?php if ($uploadSuccess): ?>
          <div class="mt-4 mb-2 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
            Foto profil berhasil diubah.
          </div>
        <?php elseif (!empty($uploadError)): ?>
          <div class="mt-4 mb-2 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            <?php echo $uploadError; ?>
          </div>
        <?php endif; ?>

        <!-- PROFILE CARD -->
        <section
          class="mt-4 md:mt-6 bg-[#343F7A] rounded-3xl md:rounded-[30px] text-white px-6 py-8 md:px-10 md:py-10 lg:px-14 lg:py-12"
        >
          <div
            class="flex flex-col md:flex-row gap-8 md:gap-10 lg:gap-14 items-start"
          >
            <!-- LEFT: Avatar + upload -->
            <div
              class="flex flex-col items-center md:items-start md:w-[260px] flex-shrink-0"
            >
              <div
                class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden"
              >
                <?php if ($photoPath): ?>
                  <img
                    src="<?php echo $photoPath; ?>"
                    alt="Profile photo"
                    class="w-full h-full object-cover"
                  />
                <?php else: ?>
                  <i class="fa-solid fa-user text-5xl md:text-6xl text-gray-500"></i>
                <?php endif; ?>
              </div>

              <!-- Form upload foto -->
              <form
                id="photoForm"
                action="profile.php"
                method="POST"
                enctype="multipart/form-data"
                class="mt-5 flex flex-col items-center md:items-start"
              >
                <input
                  type="file"
                  id="photoInput"
                  name="profile_photo"
                  accept="image/*"
                  class="hidden"
                />
                <button
                  type="button"
                  id="photoButton"
                  class="inline-flex items-center px-4 py-2 rounded-full border border-white/70 text-xs md:text-sm font-medium hover:bg-white/10 transition"
                >
                  Upload new photo
                </button>
              </form>
            </div>

            <!-- RIGHT: Info -->
            <div class="flex-1 w-full">
              <!-- Nama Besar -->
              <div class="mb-6">
                <h2
                  class="text-2xl md:text-3xl lg:text-4xl font-bold tracking-tight"
                >
                  <?php echo $fullName; ?>
                </h2>
                <div
                  class="mt-3 border-t border-dotted border-white/40 max-w-xl"
                ></div>
              </div>

              <!-- Card Personal Info -->
              <div
                class="bg-[#EEF0F9] text-[#343F7A] rounded-2xl md:rounded-3xl px-5 py-6 md:px-8 md:py-7 flex flex-col gap-5"
              >
                <div class="flex items-center justify-between gap-4">
                  <h3 class="text-base md:text-lg font-semibold">
                    Personal Info
                  </h3>

                  <!-- Edit button (belum ada fungsi edit detail, nanti bisa ditambah) -->
                  <button
                    type="button"
                    class="inline-flex items-center gap-2 border border-[#343F7A] text-[#343F7A] rounded-lg px-3 py-1.5 text-xs md:text-sm font-medium hover:bg-[#343F7A] hover:text-white transition"
                  >
                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                    Edit
                  </button>
                </div>

                <!-- Detail Info -->
                <div
                  class="grid grid-cols-1 sm:grid-cols-3 gap-y-4 gap-x-10 text-sm md:text-base"
                >
                  <!-- Full Name -->
                  <div>
                    <p class="text-xs md:text-sm text-gray-500">Full Name</p>
                    <p class="mt-1 font-semibold text-[#343F7A]">
                      <?php echo $fullName; ?>
                    </p>
                  </div>

                  <!-- Email -->
                  <div>
                    <p class="text-xs md:text-sm text-gray-500">Email</p>
                    <p class="mt-1 font-semibold text-[#343F7A] break-all">
                      <?php echo $email; ?>
                    </p>
                  </div>

                  <!-- Phone -->
                  <div>
                    <p class="text-xs md:text-sm text-gray-500">Phone</p>
                    <p class="mt-1 font-semibold text-[#343F7A]">
                      <?php echo $phone; ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Logout button di bawah (mobile friendly) -->
        <div class="mt-6">
          <form action="logout.php" method="POST">
            <button
              type="submit"
              class="inline-flex items-center px-5 py-2.5 rounded-full bg-red-500 text-white text-sm md:text-base font-semibold hover:bg-red-600 transition"
            >
              <i class="fa-solid fa-right-from-bracket mr-2"></i>
              Logout
            </button>
          </form>
        </div>
      </div>
    </main>

    <!-- JS kecil untuk auto-submit upload foto -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const photoBtn = document.getElementById("photoButton");
        const photoInput = document.getElementById("photoInput");
        const photoForm = document.getElementById("photoForm");

        if (photoBtn && photoInput && photoForm) {
          photoBtn.addEventListener("click", function () {
            photoInput.click();
          });

          photoInput.addEventListener("change", function () {
            if (photoInput.files.length > 0) {
              photoForm.submit();
            }
          });
        }
      });
    </script>
  </body>
</html>
