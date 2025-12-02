<?php
require 'config.php';

// --- AMBIL FILTER DARI QUERY STRING ---
$bathroom_type = $_GET['bathroom_type'] ?? '';
$parking       = isset($_GET['parking']);
$wifi          = isset($_GET['wifi']);
$gender_type   = $_GET['gender_type'] ?? '';
$ac            = isset($_GET['ac']);
$kitchen       = isset($_GET['kitchen']);
$price_range   = $_GET['price_range'] ?? '';
$city          = $_GET['city'] ?? '';

// --- BANGUN QUERY ---
$sql = "SELECT * FROM kosts WHERE 1=1";

if ($bathroom_type !== '') {
  $bathroom_type = $conn->real_escape_string($bathroom_type);
  $sql .= " AND bathroom_type = '$bathroom_type'";
}
if ($parking)  $sql .= " AND parking = 1";
if ($wifi)     $sql .= " AND wifi = 1";
if ($ac)       $sql .= " AND ac = 1";
if ($kitchen)  $sql .= " AND kitchen = 1";

if ($gender_type !== '') {
  $gender_type = $conn->real_escape_string($gender_type);
  $sql .= " AND gender_type = '$gender_type'";
}
if ($city !== '') {
  $city = $conn->real_escape_string($city);
  $sql .= " AND city = '$city'";
}

if ($price_range !== '') {
  switch ($price_range) {
    case '100-500':   $sql .= " AND price_month BETWEEN 100000 AND 500000";   break;
    case '500-1000':  $sql .= " AND price_month BETWEEN 500000 AND 1000000"; break;
    case '1000-1500': $sql .= " AND price_month BETWEEN 1000000 AND 1500000"; break;
    case '1500-2000': $sql .= " AND price_month BETWEEN 1500000 AND 2000000"; break;
    case '2000-2500': $sql .= " AND price_month BETWEEN 2000000 AND 2500000"; break;
    case '2500-3000': $sql .= " AND price_month BETWEEN 2500000 AND 3000000"; break;
  }
}

$result = $conn->query($sql);

// --- AMBIL WISHLIST USER YANG LOGIN ---
$userId = $_SESSION['user_id'] ?? null;
$wishlistIds = [];

if ($userId) {
  $stmt = $conn->prepare("SELECT kost_id FROM wishlists WHERE user_id = ?");
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $resWishlist = $stmt->get_result();
  while ($w = $resWishlist->fetch_assoc()) {
    $wishlistIds[] = (int)$w['kost_id'];
  }
  $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Search Kost | ThreeKost</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Poppins -->
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
    rel="stylesheet"
  />

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />

  <style>
    html {
      scroll-behavior: smooth;
    }
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
  <main class="pt-28 pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- jumlah hasil -->
      <h2 class="text-xl sm:text-2xl font-semibold mb-4">
        <?php echo $result->num_rows; ?> Results Found
      </h2>

      <!-- chip filter aktif -->
      <div class="flex flex-wrap gap-3 mb-10">
        <?php if ($bathroom_type): ?>
          <span class="inline-flex items-center gap-2 rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium text-white">
            <?php echo $bathroom_type === 'private' ? 'private bathroom' : 'shared bathroom'; ?>
            <span class="text-white/70 text-lg leading-none">&times;</span>
          </span>
        <?php endif; ?>

        <?php if ($wifi): ?>
          <span class="inline-flex items-center gap-2 rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium text-white">
            Wifi
            <span class="text-white/70 text-lg leading-none">&times;</span>
          </span>
        <?php endif; ?>

        <?php if ($parking): ?>
          <span class="inline-flex items-center gap-2 rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium text-white">
            Parking Area
            <span class="text-white/70 text-lg leading-none">&times;</span>
          </span>
        <?php endif; ?>

        <?php if ($city): ?>
          <span class="inline-flex items-center gap-2 rounded-full bg-indigo-500 px-4 py-1.5 text-xs sm:text-sm font-medium text-white">
            <?php echo htmlspecialchars($city); ?>
            <span class="text-white/70 text-lg leading-none">&times;</span>
          </span>
        <?php endif; ?>
      </div>

      <?php if ($result->num_rows === 0): ?>
        <p class="text-gray-500">Tidak ada kost yang cocok dengan filter.</p>
      <?php else: ?>

        <?php while ($row = $result->fetch_assoc()): ?>
          <!-- KARTU KOST -->
          <article
            class="max-w-5xl mx-auto mb-12 bg-white rounded-[26px] overflow-hidden shadow-[0_18px_40px_rgba(0,0,0,0.12)]"
          >

            <!-- BAGIAN GAMBAR (HANYA INI YANG BISA DIKLIK) -->
            <div class="relative">
              <a
                href="kost_detail.php?id=<?php echo (int)$row['id']; ?>"
                class="block"
              >
                <img
                  src="<?php echo htmlspecialchars($row['main_image']); ?>"
                  alt="<?php echo htmlspecialchars($row['name']); ?>"
                  class="w-full h-[260px] md:h-[320px] lg:h-[360px] object-cover cursor-pointer"
                />
              </a>

              <!-- Ribbon harga -->
              <div class="absolute -bottom-8 left-0">
                <div
                  class="bg-[#6E8CFB] text-white font-semibold text-lg md:text-2xl px-10 md:px-16 py-4 rounded-tr-[48px]"
                >
                  Rp. <?php echo number_format($row['price_month'], 0, ',', '.'); ?> /Month
                </div>
              </div>

              <?php
              $isWishlisted = $userId && in_array((int)$row['id'], $wishlistIds);
              ?>
              <form
                method="POST"
                action="wishlist_toggle.php"
                class="absolute top-5 right-5"
              >
                <input type="hidden" name="kost_id" value="<?php echo (int)$row['id']; ?>">
                <button
                  type="submit"
                  onclick="event.stopPropagation();"
                  class="w-12 h-12 rounded-[16px] bg-white/95 flex items-center justify-center shadow-md hover:scale-105 transition"
                >
                  <i class="<?php
                      echo $isWishlisted
                          ? 'fa-solid fa-heart text-red-500'
                          : 'fa-regular fa-heart text-gray-700';
                      ?> text-2xl"></i>
                </button>
              </form>

              <!-- Dots slider (dummy) -->
              <div class="absolute bottom-6 right-10 flex gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-white/80"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-white/50"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-white/50"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-white/50"></span>
              </div>
            </div>

            <!-- BAGIAN KONTEN -->
            <div
              class="pt-12 pb-8 px-6 md:px-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6"
            >
              <!-- Kiri: nama, alamat, icon info -->
              <div>
                <h3 class="text-xl md:text-2xl font-semibold text-gray-900 mb-1">
                  <?php echo htmlspecialchars($row['name']); ?>
                </h3>
                <p class="text-sm md:text-base text-gray-500 mb-4">
                  <span class="mr-1 text-red-500">📍</span>
                  <?php echo htmlspecialchars($row['address']); ?>,
                  <?php echo htmlspecialchars($row['city']); ?>.
                </p>

                <!-- Baris icon info (mirip 3 | 1 | 2 | 0) -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-700">
                  <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bed"></i>
                    <span><?php echo (int) $row['room_total']; ?></span>
                  </div>
                  <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bath"></i>
                    <span><?php echo $row['bathroom_type'] === 'private' ? '1' : 'Shared'; ?></span>
                  </div>
                  <div class="flex items-center gap-2">
                    <i class="fa-solid fa-car-side"></i>
                    <span><?php echo $row['parking'] ? '1' : '0'; ?></span>
                  </div>
                  <div class="flex items-center gap-2">
                    <i class="fa-solid fa-paw"></i>
                    <span>0</span>
                  </div>
                </div>
              </div>

              <!-- Kanan: chips fasilitas utama -->
              <div class="flex flex-wrap gap-3 md:justify-end md:min-w-[140px]">
                <?php if ($row['wifi']): ?>
                  <button
                    class="px-10 py-2 rounded-full bg-[#4F56B5] text-white text-sm md:text-base font-semibold"
                  >
                    Wifi
                  </button>
                <?php endif; ?>

                <?php if ($row['ac']): ?>
                  <span
                    class="px-6 py-2 rounded-full bg-gray-100 text-gray-700 text-xs md:text-sm font-medium"
                  >
                    AC
                  </span>
                <?php endif; ?>

                <?php if ($row['kitchen']): ?>
                  <span
                    class="px-6 py-2 rounded-full bg-gray-100 text-gray-700 text-xs md:text-sm font-medium"
                  >
                    Kitchen
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </article>
          <!-- END KARTU KOST -->
        <?php endwhile; ?>

      <?php endif; ?>
    </div>
  </main>

  <!-- FOOTER -->
  <?php 
    @include('footer.php')
  ?>

</body>
</html>
