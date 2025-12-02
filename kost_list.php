<?php
require 'config.php';

// Ambil semua kost (tanpa filter)
$sql = "SELECT * FROM kosts ORDER BY id DESC";
$result = $conn->query($sql);

// Ambil wishlist user (kalau login)
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
  <title>Find a Kost | ThreeKost</title>

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
<body class="bg-[#F5F7FB] text-gray-900">
  <?php include 'navbar.php'; ?>

  <!-- MAIN -->
  <main class="pt-28 pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
        <div>
          <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">
            Find a Kost
          </h1>
        </div>

        <!-- Tombol filter  -->
        <div class="flex items-center gap-3">
          <button
            type="button"
            id="openFilterList"
            class="flex items-center gap-2 rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100"
          >
            <i class="fas fa-sliders-h"></i>
            <span>Filters</span>
          </button>

        </div>
      </div>

      <!-- Grid Kost -->
      <?php if ($result && $result->num_rows > 0): ?>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <?php while ($row = $result->fetch_assoc()): ?>
            <?php
              $isWishlisted = $userId && in_array((int)$row['id'], $wishlistIds);
            ?>
            <article
              onclick="window.location='kost_detail.php?id=<?php echo (int)$row['id']; ?>';"
              class="group bg-white rounded-3xl shadow-[0_18px_40px_rgba(0,0,0,0.12)] overflow-hidden cursor-pointer transition-transform hover:-translate-y-1"
            >
              <!-- Gambar -->
              <div class="relative">
                <img
                  src="<?php echo htmlspecialchars('admin/kosts/'.$row['main_image']); ?>"
                  alt="<?php echo htmlspecialchars($row['name']); ?>"
                  class="w-full h-52 sm:h-56 lg:h-60 object-cover"
                />

                <!-- Harga -->
                <div class="absolute bottom-4 left-4">
                  <div class="bg-[#6E8CFB] text-white text-xs sm:text-sm font-semibold px-4 sm:px-6 py-2 rounded-tr-3xl rounded-bl-3xl shadow-md">
                    Rp. <?php echo number_format($row['price_month'], 0, ',', '.'); ?> /Month
                  </div>
                </div>

                <!-- Wishlist Heart -->
                <form
                  method="POST"
                  action="wishlist_toggle.php"
                  class="absolute top-4 right-4"
                  onclick="event.stopPropagation();"
                >
                  <input type="hidden" name="kost_id" value="<?php echo (int)$row['id']; ?>">
                  <button
                    type="submit"
                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-white/95 flex items-center justify-center shadow-md hover:scale-105 transition"
                  >
                    <i class="<?php
                      echo $isWishlisted
                        ? 'fa-solid fa-heart text-red-500'
                        : 'fa-regular fa-heart text-gray-700';
                    ?> text-xl"></i>
                  </button>
                </form>
              </div>

              <!-- Detail -->
              <div class="p-4 sm:p-5">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 line-clamp-1">
                  <?php echo htmlspecialchars($row['name']); ?>
                </h3>
                <p class="text-xs sm:text-sm text-gray-500 mb-3 line-clamp-2">
                  <span class="mr-1 text-red-500">📍</span>
                  <?php echo htmlspecialchars($row['address']); ?>,
                  <?php echo htmlspecialchars($row['city']); ?>
                </p>

                <!-- Info Icon -->
                <div class="flex flex-wrap items-center gap-4 text-[11px] sm:text-xs text-gray-700">
                  <div class="flex items-center gap-1">
                    <i class="fa-solid fa-bed"></i>
                    <span><?php echo (int)$row['room_total']; ?></span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fa-solid fa-bath"></i>
                    <span>
                      <?php echo $row['bathroom_type'] === 'private' ? '1' : 'Shared'; ?>
                    </span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fa-solid fa-car-side"></i>
                    <span><?php echo $row['parking'] ? '1' : '0'; ?></span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fa-solid fa-paw"></i>
                    <span>0</span>
                  </div>
                </div>

                <!-- Fitur kecil (Wifi / AC / Kitchen) -->
                <div class="mt-3 flex flex-wrap gap-2">
                  <?php if ($row['wifi']): ?>
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-[11px] sm:text-xs text-gray-700">
                      Wifi
                    </span>
                  <?php endif; ?>
                  <?php if ($row['ac']): ?>
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-[11px] sm:text-xs text-gray-700">
                      AC
                    </span>
                  <?php endif; ?>
                  <?php if ($row['kitchen']): ?>
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-[11px] sm:text-xs text-gray-700">
                      Kitchen
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="mt-10 text-center text-gray-500">
          Belum ada kost yang tersedia.
        </p>
      <?php endif; ?>
    </div>
  </main>

      <!-- ================= FILTER POPUP (TAILWIND) ================= -->
    <div
      id="filterOverlay"
      class="fixed inset-0 z-[55] hidden items-center justify-center"
    >
      <!-- overlay gelap -->
      <div class="absolute inset-0 bg-black/50"></div>

      <!-- card filter -->
      <div
        class="relative z-10 mx-4 w-full max-w-4xl max-h-[80vh] overflow-y-auto rounded-3xl bg-white p-6 md:p-8 shadow-xl"
      >
        <!-- tombol close -->
        <button
          type="button"
          id="closeFilter"
          class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500 text-white text-2xl leading-none shadow-md hover:bg-indigo-600"
        >
          &times;
        </button>

        <!-- FORM FILTER -->
        <form action="search_kost.php" method="get" class="space-y-8 mt-6">
          <!-- TYPE -->
          <section>
            <h3 class="text-lg font-semibold text-gray-800">Type</h3>
            <div class="mt-2 h-px bg-gray-200"></div>

            <div class="mt-4 flex flex-wrap gap-3">
              <!-- bathroom type -->
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  name="bathroom_type"
                  value="private"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  Private Bathroom
                </span>
              </label>

              <label class="inline-flex items-center">
                <input
                  type="radio"
                  name="bathroom_type"
                  value="shared"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  Shared Bathroom
                </span>
              </label>

              <!-- fasilitas -->
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="parking"
                  value="1"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  Parking Area
                </span>
              </label>

              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="wifi"
                  value="1"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  Wifi
                </span>
              </label>

              <label class="inline-flex items-center">
                <input
                  type="radio"
                  name="gender_type"
                  value="male"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  Male
                </span>
              </label>

              <label class="inline-flex items-center">
                <input
                  type="radio"
                  name="gender_type"
                  value="female"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  Female
                </span>
              </label>

              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="ac"
                  value="1"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  AC
                </span>
              </label>

              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="kitchen"
                  value="1"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  Kitchen
                </span>
              </label>
            </div>
          </section>

          <!-- RANGE -->
          <section>
            <h3 class="text-lg font-semibold text-gray-800">Range</h3>
            <div class="mt-2 h-px bg-gray-200"></div>

            <div class="mt-4 grid gap-3 md:grid-cols-2">
              <label class="inline-flex items-center">
                <input type="radio" name="price_range" value="100-500" class="peer sr-only" />
                <span class="w-full px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                             peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500">
                  Rp. 100.000,00 - Rp. 500.000,00
                </span>
              </label>

              <label class="inline-flex items-center">
                <input type="radio" name="price_range" value="500-1000" class="peer sr-only" />
                <span class="w-full px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                             peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500">
                  Rp. 500.000,00 - Rp. 1.000.000,00
                </span>
              </label>

              <label class="inline-flex items-center">
                <input type="radio" name="price_range" value="1000-1500" class="peer sr-only" />
                <span class="w-full px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                             peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500">
                  Rp. 1.000.000,00 - Rp. 1.500.000,00
                </span>
              </label>

              <label class="inline-flex items-center">
                <input type="radio" name="price_range" value="1500-2000" class="peer sr-only" />
                <span class="w-full px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                             peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500">
                  Rp. 1.500.000,00 - Rp. 2.000.000,00
                </span>
              </label>

              <label class="inline-flex items-center">
                <input type="radio" name="price_range" value="2000-2500" class="peer sr-only" />
                <span class="w-full px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                             peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500">
                  Rp. 2.000.000,00 - Rp. 2.500.000,00
                </span>
              </label>

              <label class="inline-flex items-center">
                <input type="radio" name="price_range" value="2500-3000" class="peer sr-only" />
                <span class="w-full px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                             peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500">
                  Rp. 2.500.000,00 - Rp. 3.000.000,00
                </span>
              </label>
            </div>
          </section>

          <!-- CITY -->
          <section>
            <h3 class="text-lg font-semibold text-gray-800">City</h3>
            <div class="mt-2 h-px bg-gray-200"></div>

            <div class="mt-4 flex flex-wrap gap-3">
              <?php
              $cities = [
                  'Salatiga', 'Semarang', 'Boyolali', 'Banyumanik',
                  'Pekalongan', 'Banyumas', 'Surakarta', 'Yogyakarta',
                  'Solo', 'Purwakarta', 'Klaten', 'Ungaran', 'Tegal'
              ];
              foreach ($cities as $c):
              ?>
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  name="city"
                  value="<?php echo $c; ?>"
                  class="peer sr-only"
                />
                <span
                  class="px-4 py-2 rounded-full border border-gray-300 text-sm text-gray-700 cursor-pointer
                         peer-checked:bg-indigo-500 peer-checked:text-white peer-checked:border-indigo-500"
                >
                  <?php echo $c; ?>
                </span>
              </label>
              <?php endforeach; ?>
            </div>
          </section>

          <!-- BUTTON OK -->
          <div class="flex justify-center pt-2 pb-1">
            <button
              type="submit"
              class="px-10 py-2.5 rounded-full bg-indigo-500 text-white text-sm font-semibold shadow hover:bg-indigo-600"
            >
              OK
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- ================= END FILTER POPUP ================= -->\


    <script>
  document.addEventListener('DOMContentLoaded', function () {
    const filterOverlay = document.getElementById('filterOverlay');
    const openFilterBtn = document.getElementById('openFilterList');
    const closeFilterBtn = document.getElementById('closeFilter');

    function openFilter() {
      if (!filterOverlay) return;
      filterOverlay.classList.remove('hidden');
      filterOverlay.classList.add('flex');
      document.body.classList.add('overflow-hidden');
    }

    function closeFilter() {
      if (!filterOverlay) return;
      filterOverlay.classList.add('hidden');
      filterOverlay.classList.remove('flex');
      document.body.classList.remove('overflow-hidden');
    }

    if (openFilterBtn) {
      openFilterBtn.addEventListener('click', openFilter);
    }

    if (closeFilterBtn) {
      closeFilterBtn.addEventListener('click', closeFilter);
    }

    if (filterOverlay) {
      // klik area gelap di luar card untuk menutup
      filterOverlay.addEventListener('click', function (e) {
        if (e.target === filterOverlay) {
          closeFilter();
        }
      });
    }
  });
</script>



  <!-- Footer -->
    <?php 
        @include('footer.php')
      ?>
</body>
</html>
