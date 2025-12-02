<?php
require 'config.php';

// wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id     = $_SESSION['user_id'];
$uploadError = "";
$updateError = "";

// ========== HANDLE UPLOAD FOTO (SAMA SEPERTI DI profile.php) ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        if (!array_key_exists($file['type'], $allowedMime)) {
            $uploadError = "Format file harus JPG, PNG, atau WEBP.";
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $uploadError = "Ukuran file maksimal 2MB.";
        } else {
            $ext        = $allowedMime[$file['type']];
            $targetDir  = __DIR__ . '/uploads';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName     = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            $targetPath   = $targetDir . '/' . $fileName;
            $relativePath = 'uploads/' . $fileName; // disimpan ke DB

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // hapus foto lama
                $stmtOld = $conn->prepare("SELECT photo FROM users WHERE id = ?");
                $stmtOld->bind_param("i", $user_id);
                $stmtOld->execute();
                $resultOld = $stmtOld->get_result();
                $old       = $resultOld->fetch_assoc();
                $stmtOld->close();

                if ($old && !empty($old['photo'])) {
                    $oldPath = __DIR__ . '/' . $old['photo'];
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                // simpan foto baru
                $stmt = $conn->prepare("UPDATE users SET photo = ? WHERE id = ?");
                $stmt->bind_param("si", $relativePath, $user_id);
                $stmt->execute();
                $stmt->close();

                // reload halaman ini supaya preview fotonya update
                header("Location: edit_profile.php?upload=success");
                exit;
            } else {
                $uploadError = "Gagal mengunggah file. Coba lagi.";
            }
        }
    } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadError = "Terjadi kesalahan saat upload. Kode error: " . $file['error'];
    }
}

// ========== HANDLE UPDATE TEXT (FULL NAME / EMAIL / PHONE) ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newName  = trim($_POST['full_name'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newPhone = trim($_POST['phone'] ?? '');

    if ($newName === '' || $newEmail === '') {
        $updateError = "Full name dan email wajib diisi.";
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $updateError = "Format email tidak valid.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $newName, $newEmail, $newPhone, $user_id);

        try {
            $stmt->execute();

            $stmt->close();
            header("Location: profile.php?updated=1");
            exit;
        } catch (mysqli_sql_exception $e) {
            // 1062 = duplicate entry
            if ($e->getCode() == 1062) {
                // pesan untuk user
                $updateError = "Nomor handphone sudah digunakan oleh akun lain.";
            } else {
                $updateError = "Gagal mengupdate profile. Coba lagi.";
            }

            $stmt->close();
            // jangan throw lagi, biar nggak Fatal error
        }
    }
}


// ========== AMBIL DATA USER SAAT INI ==========
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: logout.php");
    exit;
}

$fullName      = htmlspecialchars($user['full_name'] ?? 'User');
$email         = htmlspecialchars($user['email'] ?? '');
$phone         = htmlspecialchars($user['phone'] ?? '');
$photoPath     = (!empty($user['photo'])) ? htmlspecialchars($user['photo']) : null;
$uploadSuccess = (isset($_GET['upload']) && $_GET['upload'] === 'success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Profile - ThreeKost</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <script src="https://cdn.tailwindcss.com"></script>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />
  <style>
    body { font-family: "Poppins", sans-serif; }
  </style>
</head>
<body class="bg-white text-gray-900">
  <?php 
        @include('navbar.php')
      ?>

  <main class="mt-[80px] px-4 md:px-8 lg:px-16 pb-16">
    <div class="max-w-4xl mx-auto">
      <!-- back + title -->
      <div class="flex items-center gap-3 mt-4 mb-6">
        <a
          href="profile.php"
          class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 text-gray-700"
        >
          <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1 class="text-xl md:text-2xl font-semibold text-gray-800">
          Edit Profile
        </h1>
      </div>

      <!-- Alerts -->
      <?php if ($uploadSuccess): ?>
        <div class="mb-3 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
          Foto profil berhasil diubah.
        </div>
      <?php endif; ?>

      <?php if (!empty($uploadError)): ?>
        <div class="mb-3 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
          <?php echo $uploadError; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($updateError)): ?>
        <div class="mb-3 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
          <?php echo $updateError; ?>
        </div>
      <?php endif; ?>

      <!-- Avatar + Upload -->
      <section class="flex flex-col items-center mb-10">
        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-[#343F7A] flex items-center justify-center overflow-hidden">
          <?php if ($photoPath): ?>
            <img src="<?php echo $photoPath; ?>" alt="Profile photo"
                 class="w-full h-full object-cover" />
          <?php else: ?>
            <i class="fa-solid fa-user text-5xl md:text-6xl text-white/80"></i>
          <?php endif; ?>
        </div>

        <form
          id="photoForm"
          action="edit_profile.php"
          method="POST"
          enctype="multipart/form-data"
          class="mt-4"
        >
          <input
    type="file"
    id="photoInput"
    name="profile_photo"
    accept="image/*"
    class="hidden"
    onchange="document.getElementById('photoForm').submit();"
  />

  <!-- label ini yang jadi tombol "Upload new photo" -->
  <label
    for="photoInput"
    class="inline-flex items-center px-4 py-1.5 rounded-full border border-gray-300 text-xs md:text-sm text-gray-700 hover:bg-gray-50 transition cursor-pointer"
  >
    Upload new photo
  </label>
        </form>
      </section>

      <!-- Form edit text -->
      <section class="bg-white rounded-3xl shadow-sm border border-gray-100 px-6 py-7 md:px-10 md:py-8">
        <h2 class="text-lg md:text-xl font-semibold text-gray-800 mb-1">
          Hello, <?php echo $fullName; ?>
        </h2>
        <p class="text-xs md:text-sm text-gray-500 mb-6">
          Update your personal information below.
        </p>

        <form action="edit_profile.php" method="POST" class="space-y-5">
          <input type="hidden" name="update_profile" value="1" />

          <!-- Full Name -->
          <div>
            <label class="block text-xs md:text-sm text-gray-500 mb-1.5">
              Full Name
            </label>
            <div class="relative">
              <input
                type="text"
                name="full_name"
                value="<?php echo $fullName; ?>"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm md:text-base
                       focus:outline-none focus:ring-2 focus:ring-[#5f7cff] focus:border-[#5f7cff]"
                required
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 text-xs">
                <i class="fa-regular fa-pen-to-square"></i>
              </span>
            </div>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-xs md:text-sm text-gray-500 mb-1.5">
              Email
            </label>
            <div class="relative">
              <input
                type="email"
                name="email"
                value="<?php echo $email; ?>"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm md:text-base
                       focus:outline-none focus:ring-2 focus:ring-[#5f7cff] focus:border-[#5f7cff]"
                required
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 text-xs">
                <i class="fa-regular fa-pen-to-square"></i>
              </span>
            </div>
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-xs md:text-sm text-gray-500 mb-1.5">
              Phone
            </label>
            <div class="relative">
              <input
                type="text"
                name="phone"
                value="<?php echo $phone; ?>"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm md:text-base
                       focus:outline-none focus:ring-2 focus:ring-[#5f7cff] focus:border-[#5f7cff]"
              />
              <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 text-xs">
                <i class="fa-regular fa-pen-to-square"></i>
              </span>
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-4 pt-4">
            <button
              type="button"
              onclick="window.location.href='profile.php';"
              class="px-5 md:px-7 py-2.5 rounded-full text-sm md:text-base font-semibold
                     bg-gray-200 text-gray-700 hover:bg-gray-300 transition"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-6 md:px-8 py-2.5 rounded-full text-sm md:text-base font-semibold
                     bg-[#5f7cff] text-white hover:bg-[#4767e5] transition"
            >
              Save
            </button>
          </div>
        </form>
      </section>
    </div>
  </main>

  <!-- script.js  -->
  <script src="script.js" defer></script>

</body>
</html>
