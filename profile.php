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

            $fileName    = 'profile_' . $user_id . '_' . time() . '.' . $ext;
            $targetPath  = $targetDir . '/' . $fileName;
            $relativePath= 'uploads/' . $fileName; // ini yang disimpan ke DB

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
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

// Jika user tidak ditemukan (id tidak valid) → paksa logout
if (!$user) {
    header("Location: logout.php");
    exit;
}

// ========= AMBIL SEMUA BOOKING USER (CURRENT + PAST) =========
$stmtB = $conn->prepare("
    SELECT b.*, k.name AS kost_name, k.main_image, k.address, k.city
    FROM bookings b
    JOIN kosts k ON b.kost_id = k.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
$stmtB->bind_param("i", $user_id);
$stmtB->execute();
$resB = $stmtB->get_result();

$bookings = [];
while ($row = $resB->fetch_assoc()) {
    $bookings[] = $row;
}
$stmtB->close();

$currentBooking = $bookings[0] ?? null;
$pastBookings   = array_slice($bookings, 1);

$fullName = htmlspecialchars($user['full_name'] ?? 'User');
$email    = htmlspecialchars($user['email'] ?? '-');

if (isset($user['phone']) && $user['phone'] !== '') {
    $phone = htmlspecialchars($user['phone']);
} else {
    $phone = 'Not set yet';
}

$photoPath      = (isset($user['photo']) && $user['photo'] !== '') ? htmlspecialchars($user['photo']) : null;
$uploadSuccess  = (isset($_GET['upload']) && $_GET['upload'] === 'success');
$profileUpdated = isset($_GET['updated']) && $_GET['updated'] === '1';

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
    <?php 
        @include('navbar.php')
      ?>

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

        <?php if ($profileUpdated): ?>
  <div class="mt-2 mb-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 text-sm">
    Profile updated successfully.
  </div>
<?php endif; ?>


        <!-- PROFILE CARD (BIRU ATAS) -->
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

              <?php if (!$photoPath): ?>
  <!-- BELUM punya foto → boleh upload langsung di profile.php -->
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
<?php else: ?>
  <!-- SUDAH punya foto → tombol diarahkan ke edit_profile.php -->
  <a
    href="edit_profile.php"
    class="mt-5 inline-flex items-center px-4 py-2 rounded-full border border-white/70 text-xs md:text-sm font-medium hover:bg-white/10 transition"
  >
    Upload new photo
  </a>
<?php endif; ?>

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

                 <a
  href="edit_profile.php"
  class="inline-flex items-center gap-2 border border-[#343F7A] text-[#343F7A] rounded-lg px-3 py-1.5 text-xs md:text-sm font-medium hover:bg-[#343F7A] hover:text-white transition"
>
  <i class="fa-regular fa-pen-to-square text-sm"></i>
  Edit
</a>

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

        <!-- TABS: CURRENT / PAST / REVIEW -->
<section class="mt-10">
  <nav
  id="tabs-nav"
  class="relative flex justify-center 
         gap-32 md:gap-48 lg:gap-64  <!-- atur jarak antar tab -->
         text-sm md:text-base font-semibold 
         border-b border-gray-300"
>
  <button
    type="button"
    class="tab-link pb-3 text-[#343F7A]"
    data-tab="current"
  >
    Current Boarding
  </button>
  <button
    type="button"
    class="tab-link pb-3 text-gray-400"
    data-tab="past"
  >
    Past Boarding History
  </button>
  <button
    type="button"
    class="tab-link pb-3 text-gray-400"
    data-tab="review"
  >
    Review History
  </button>

  <!-- underline -->
  <span
    id="tab-underline"
    class="pointer-events-none absolute bottom-0 h-[2px] bg-[#343F7A]
           transition-all duration-300 ease-out"
  ></span>
</nav>

</section>




          <!-- CURRENT BOARDING TAB -->
<section
  id="tab-current"
  class="tab-panel mt-6 opacity-100 translate-y-0 max-h-[2000px] pointer-events-auto transition-all duration-300 ease-out"
>
  <p class="text-sm md:text-base text-gray-700 mb-4">
    Information about your active boarding house or room
  </p>

  <?php if ($currentBooking): ?>
    <div class="bg-[#343F7A] text-white rounded-3xl px-6 py-6 md:px-10 md:py-8 flex flex-col md:flex-row gap-6 shadow-md">
      <!-- kiri: gambar + tombol -->
      <div class="md:w-[260px] flex flex-col items-center md:items-start">
        <img
          src="<?php echo htmlspecialchars($currentBooking['main_image']); ?>"
          alt="<?php echo htmlspecialchars($currentBooking['kost_name']); ?>"
          class="w-full h-40 md:h-48 object-cover rounded-2xl shadow-md"
        />
        <p class="mt-3 font-semibold text-base md:text-lg text-center md:text-left">
          <?php echo htmlspecialchars($currentBooking['kost_name']); ?>
        </p>

        <div class="mt-4 flex flex-wrap gap-3 justify-center md:justify-start">
          <a
            href="messages.php?kost_id=<?php echo (int)$currentBooking['kost_id']; ?>"
            class="inline-flex items-center px-4 py-2 rounded-full bg-white/15 text-xs md:text-sm font-medium hover:bg-white/25">
            Contact Host
          </a>
          <a href="payment.php?booking_id=<?php echo (int)$currentBooking['id']; ?>"
             class="inline-flex items-center px-4 py-2 rounded-full bg-[#FFC857] text-[#343F7A] text-xs md:text-sm font-semibold hover:bg-[#ffb835]">
            Payment
          </a>
        </div>

        <p class="mt-5 text-xs md:text-sm text-center md:text-left text-white/80">
          Next Payment Due<br />
          <span class="font-semibold">
            <?php
              $nextDate = new DateTime($currentBooking['created_at']);
              $nextDate->modify('+30 days');
              echo $nextDate->format('F d, Y');
            ?>
          </span>
        </p>
      </div>

      <!-- kanan: detail -->
      <div class="flex-1 grid grid-cols-[auto,1fr] gap-x-4 gap-y-2 text-xs md:text-sm">
        <span class="font-medium">Boarding Name</span>
        <span>: <?php echo htmlspecialchars($currentBooking['kost_name']); ?></span>

        <span class="font-medium">Room Number</span>
        <span>: A12</span>

        <span class="font-medium">Address</span>
        <span>: <?php echo htmlspecialchars($currentBooking['address']); ?>,&nbsp;<?php echo htmlspecialchars($currentBooking['city']); ?></span>

        <span class="font-medium">Check-in Date</span>
        <span>: <?php echo date('F d, Y', strtotime($currentBooking['created_at'])); ?></span>

        <span class="font-medium">Contract Duration</span>
        <span>: Flexible (depends on your preference)</span>

        <span class="font-medium">Monthly Rent</span>
        <span>: Rp. <?php echo number_format($currentBooking['monthly_rent'], 0, ',', '.'); ?></span>

        <span class="font-medium">Payment Status</span>
        <span>: <?php echo ($currentBooking['payment_status'] === 'paid') ? 'Paid' : 'Pending'; ?></span>

        <span class="font-medium">Facilities</span>
        <span>: AC, WiFi, Private Bathroom, Parking</span>
      </div>
    </div>
  <?php else: ?>
    <div class="mt-4 text-sm md:text-base text-gray-600">
      You don't have an active boarding house yet.
    </div>
  <?php endif; ?>
</section>

<!-- PAST BOARDING HISTORY TAB -->
<section
  id="tab-past"
  class="tab-panel mt-6 opacity-0 translate-y-3 max-h-0 pointer-events-none transition-all duration-300 ease-out"
>
  <p class="text-sm md:text-base text-gray-700 mb-4">
    A list of boarding houses you have stayed in previously
  </p>

  <?php if (!empty($pastBookings)): ?>
    <div class="space-y-5">
      <?php foreach ($pastBookings as $pb): ?>
        <?php
          $from = !empty($pb['check_in'])
            ? date('F Y', strtotime($pb['check_in']))
            : date('F Y', strtotime($pb['created_at']));
          $to   = !empty($pb['check_out'])
            ? date('F Y', strtotime($pb['check_out']))
            : 'Present';
          $period = $from . ' – ' . $to;

          $reason = isset($pb['leaving_reason']) && $pb['leaving_reason'] !== ''
            ? $pb['leaving_reason']
            : 'Contract ended';
        ?>
        <article class="bg-[#343F7A] text-white rounded-3xl px-6 py-5 md:px-8 md:py-6 flex flex-col md:flex-row gap-5 shadow-md">
          <div class="md:w-[210px] flex-shrink-0 flex items-center">
            <img
              src="<?php echo htmlspecialchars($pb['main_image']); ?>"
              alt="<?php echo htmlspecialchars($pb['kost_name']); ?>"
              class="w-full h-32 md:h-40 object-cover rounded-2xl"
            />
          </div>

          <div class="flex-1">
            <h3 class="text-base md:text-lg font-semibold mb-2">
              <?php echo htmlspecialchars($pb['kost_name']); ?>
            </h3>
            <div class="grid grid-cols-[auto,1fr] gap-x-3 gap-y-1.5 text-xs md:text-sm">
              <span class="font-medium">Stayed From</span>
              <span>: <?php echo $period; ?></span>

              <span class="font-medium">Location</span>
              <span>: <?php echo htmlspecialchars($pb['city']); ?>, <?php echo htmlspecialchars($pb['address']); ?></span>

              <span class="font-medium">Monthly Rent</span>
              <span>: Rp. <?php echo number_format($pb['monthly_rent'], 0, ',', '.'); ?></span>

              <span class="font-medium">Reason for Leaving</span>
              <span>: <?php echo htmlspecialchars($reason); ?></span>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="mt-4 text-sm md:text-base text-gray-600">
      You don't have any past boarding history yet.
    </div>
  <?php endif; ?>
</section>

<!-- REVIEW HISTORY TAB -->
<section
  id="tab-review"
  class="tab-panel mt-6 opacity-0 translate-y-3 max-h-0 pointer-events-none transition-all duration-300 ease-out"
>
  <p class="text-sm md:text-base text-gray-700 mb-4">
    A list of boarding houses you have reviewed.
  </p>

  <div class="space-y-8">
    <!-- Review 1 -->
    <article class="flex flex-col md:flex-row gap-5 pb-6 border-b border-gray-200">
      <div class="md:w-[210px] flex-shrink-0">
        <img
          src="imgfilter/coztnest.png"
          alt="CoztNest Kost"
          class="w-full h-32 md:h-40 object-cover rounded-2xl shadow-sm"
        />
      </div>

      <div class="flex-1">
        <h3 class="text-base md:text-lg font-semibold text-[#343F7A] mb-1">
          CoztNest Kost
        </h3>

        <div class="flex flex-wrap items-center text-xs md:text-sm text-gray-600 mb-2 gap-x-2">
          <span class="font-medium">Date Reviewed</span>
          <span>:</span>
          <span>February 15, 2024</span>
        </div>

        <div class="flex items-center gap-1 text-[#FFC857] text-sm mb-2">
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
        </div>

        <p class="text-xs md:text-sm text-gray-700 leading-relaxed max-w-xl">
          “Clean and quiet environment, the owner was very kind and helpful.
          Highly recommended for students.”
        </p>
      </div>
    </article>

    <!-- Review 2 -->
    <article class="flex flex-col md:flex-row gap-5 pb-6 border-b border-gray-200">
      <div class="md:w-[210px] flex-shrink-0">
        <img
          src="imgfilter/green.png"
          alt="Melati Garden Kost"
          class="w-full h-32 md:h-40 object-cover rounded-2xl shadow-sm"
        />
      </div>

      <div class="flex-1">
        <h3 class="text-base md:text-lg font-semibold text-[#343F7A] mb-1">
          Melati Garden Kost
        </h3>

        <div class="flex flex-wrap items-center text-xs md:text-sm text-gray-600 mb-2 gap-x-2">
          <span class="font-medium">Date Reviewed</span>
          <span>:</span>
          <span>October 27, 2026</span>
        </div>

        <div class="flex items-center gap-1 text-[#FFC857] text-sm mb-2">
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-regular fa-star"></i>
          <i class="fa-regular fa-star"></i>
        </div>

        <p class="text-xs md:text-sm text-gray-700 leading-relaxed max-w-xl">
          “Nice place with affordable rent. The room was cozy, but the WiFi was
          sometimes unstable.”
        </p>
      </div>
    </article>

    <!-- Review 3 -->
    <article class="flex flex-col md:flex-row gap-5 pb-6 border-b border-gray-200">
      <div class="md:w-[210px] flex-shrink-0">
        <img
          src="imgfilter/skyview.png"
          alt="SkyView Kost Exclusive"
          class="w-full h-32 md:h-40 object-cover rounded-2xl shadow-sm"
        />
      </div>

      <div class="flex-1">
        <h3 class="text-base md:text-lg font-semibold text-[#343F7A] mb-1">
          SkyView Kost Exclusive
        </h3>

        <div class="flex flex-wrap items-center text-xs md:text-sm text-gray-600 mb-2 gap-x-2">
          <span class="font-medium">Date Reviewed</span>
          <span>:</span>
          <span>August 20, 2026</span>
        </div>

        <div class="flex items-center gap-1 text-[#FFC857] text-sm mb-2">
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-solid fa-star"></i>
          <i class="fa-regular fa-star"></i>
        </div>

        <p class="text-xs md:text-sm text-gray-700 leading-relaxed max-w-xl">
          “Comfortable and modern room with great facilities. The place felt cozy
          and well-maintained, though the rent could be slightly more affordable
          for a long stay.”
        </p>
      </div>
    </article>
  </div>
</section>


        <!-- Logout button di bawah -->
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

    <!-- JS -->
  <script src="script.js" defer></script>
  </body>
</html>
