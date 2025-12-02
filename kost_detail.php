<?php
session_start();
require 'config.php';

// Ambil id kost dari URL ?id=...
$kostId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($kostId <= 0) {
    header('Location: search_kost.php');
    exit;
}

// Ambil data kost dari database
$stmt = $conn->prepare("SELECT * FROM kosts WHERE id = ?");
$stmt->bind_param("i", $kostId);
$stmt->execute();
$result = $stmt->get_result();
$kost = $result->fetch_assoc();
$stmt->close();

if (!$kost) {
    header('Location: search_kost.php');
    exit;
}

$mainImage   = htmlspecialchars( 'admin/kosts/'. $kost['main_image']);
$name        = htmlspecialchars($kost['name']);
$city        = htmlspecialchars($kost['city']);
$address     = htmlspecialchars($kost['address']);
$description = nl2br(htmlspecialchars($kost['description']));
$priceMonth  = number_format($kost['price_month'], 0, ',', '.');

// contoh data tambahan
$bedrooms = (int) ($kost['room_total'] ?? 1);
$pets     = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?php echo $name; ?> | ThreeKost</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind -->
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
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />

  <style>
    body { font-family: "Poppins", sans-serif; }
  </style>
</head>
<body class="bg-gray-50 text-slate-900">

  <?php 
        @include('navbar.php')
      ?>

  <!-- MAIN CONTENT -->
  <main class="pt-24 md:pt-28 pb-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- GALLERY -->
      <section
        class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8 items-stretch"
      >
        <!-- MAIN IMAGE -->
        <div class="lg:col-span-2">
          <div class="relative rounded-[28px] md:rounded-[32px] overflow-hidden shadow-lg">
            <img
              src="<?php echo $mainImage; ?>"
              alt="<?php echo $name; ?>"
              class="w-full h-[260px] sm:h-[320px] md:h-[380px] lg:h-[420px] object-cover"
            />

            <!-- overlay host -->
            <div class="absolute bottom-4 left-4 sm:left-6">
              <div
                class="flex items-center gap-3 px-4 py-3 sm:px-5 sm:py-3.5 rounded-2xl bg-black/55 text-white backdrop-blur-md"
              >
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden bg-white/10 flex items-center justify-center">
                  <img
                    src="img/host2.jpg"
                    alt="Host"
                    class="w-full h-full object-cover"
                    onerror="this.style.display='none';"
                  />
                </div>
                <div class="space-y-0.5 text-xs sm:text-sm leading-tight">
                  <p class="text-gray-200">Managed by</p>
                  <p class="font-semibold">
                    <?php echo $name; ?> Team
                  </p>
                  <p class="text-gray-300 text-[11px] sm:text-xs">
                    Price: Rp. <?php echo $priceMonth; ?> / month
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SMALL IMAGES -->
        <div class="grid grid-cols-2 gap-3 sm:gap-4">
          <div class="rounded-2xl overflow-hidden shadow-md">
            <img
              src="<?php echo $mainImage; ?>"
              alt="Photo 1"
              class="w-full h-32 sm:h-36 md:h-40 object-cover"
            />
          </div>

          <div class="rounded-2xl overflow-hidden shadow-md">
            <img
              src="<?php echo $mainImage; ?>"
              alt="Photo 2"
              class="w-full h-32 sm:h-36 md:h-40 object-cover"
            />
          </div>

          <div class="rounded-2xl overflow-hidden shadow-md">
            <img
              src="<?php echo $mainImage; ?>"
              alt="Photo 3"
              class="w-full h-32 sm:h-36 md:h-40 object-cover"
            />
          </div>

          <div class="relative rounded-2xl overflow-hidden shadow-md">
            <img
              src="<?php echo $mainImage; ?>"
              alt="Photo 4"
              class="w-full h-32 sm:h-36 md:h-40 object-cover blur-[1px] brightness-75"
            />
            <div
              class="absolute inset-0 flex flex-col items-center justify-center text-white bg-black/35"
            >
              <span class="text-3xl sm:text-4xl font-bold">+2</span>
              <span class="text-xs sm:text-sm font-medium tracking-wide">
                More Photos
              </span>
            </div>
          </div>
        </div>
      </section>

      <!-- INFO + PRICE SECTION -->
      <section class="mt-10 lg:mt-12 flex flex-col lg:flex-row gap-8 lg:gap-10">
        <!-- LEFT INFO -->
        <div class="flex-1">
          <!-- Title + icons -->
          <div class="flex items-start justify-between gap-4">
            <div>
              <h1 class="text-2xl md:text-3xl font-semibold text-slate-900">
                <?php echo $name; ?>
              </h1>
              <p class="mt-2 flex items-center text-sm md:text-base text-slate-500">
                <i class="fa-solid fa-location-dot mr-2 text-pink-500"></i>
                <?php echo $address; ?>, <?php echo $city; ?>
              </p>
            </div>

            <div class="flex items-center gap-3">
              <button
                class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 hover:bg-slate-50"
              >
                <i class="fa-regular fa-heart text-lg"></i>
              </button>
              <button
                class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 hover:bg-slate-50"
              >
                <i class="fa-solid fa-share-nodes text-sm"></i>
              </button>
            </div>
          </div>

          <!-- Feature cards -->
          <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Bedrooms -->
            <div class="rounded-3xl bg-[#f5f5f7] px-8 py-7 flex flex-col items-start sm:items-center justify-center">
              <i class="fa-solid fa-bed text-3xl text-slate-600 mb-4"></i>
              <p class="text-sm font-medium text-slate-500 mb-1">
                <?php echo $bedrooms; ?> Bedrooms
              </p>
            </div>

            <!-- Bathrooms (contoh 1) -->
            <div class="rounded-3xl bg-[#f5f5f7] px-8 py-7 flex flex-col items-start sm:items-center justify-center">
              <i class="fa-solid fa-shower text-3xl text-slate-600 mb-4"></i>
              <p class="text-sm font-medium text-slate-500 mb-1">
                1 Bathrooms
              </p>
            </div>

            <!-- Cars / Bikes (contoh statis) -->
            <div class="rounded-3xl bg-[#f5f5f7] px-8 py-7 flex flex-col items-start sm:items-center justify-center">
              <i class="fa-solid fa-car-side text-3xl text-slate-600 mb-4"></i>
              <p class="text-sm font-medium text-slate-500 mb-1">
                3 Cars/10 Bikes
              </p>
            </div>

            <!-- Pets -->
            <div class="rounded-3xl bg-[#f5f5f7] px-8 py-7 flex flex-col items-start sm:items-center justify-center">
              <i class="fa-solid fa-paw text-3xl text-slate-600 mb-4"></i>
              <p class="text-sm font-medium text-slate-500 mb-1">
                <?php echo $pets; ?> Pets Allowed
              </p>
            </div>
          </div>

          <!-- Apartment Description -->
          <div class="mt-10">
            <h2 class="text-xl md:text-2xl font-semibold text-slate-900 mb-4">
              Apartment Description
            </h2>
            <p class="text-sm md:text-base leading-relaxed text-slate-600">
              <?php echo $description; ?>
            </p>
          </div>
        </div>

        <!-- RIGHT PRICE CARD -->
        <aside class="w-full lg:w-[400px] xl:w-[420px]">
          <div class="rounded-3xl bg-white shadow-[0_18px_45px_rgba(15,23,42,0.08)] px-7 py-7 md:px-8 md:py-8">
            <!-- Label PRICE -->
            <p class="text-xs font-semibold tracking-[0.25em] text-slate-400 uppercase mb-2">
              Price
            </p>

            <!-- Harga satu baris -->
            <div class="flex items-baseline gap-2">
              <span class="text-2xl md:text-3xl font-semibold text-slate-900">
                Rp <?php echo $priceMonth; ?>
              </span>
              <span class="text-sm md:text-base font-normal text-slate-500">
                / month
              </span>
            </div>

            <div class="mt-5 mb-6 h-px bg-slate-100"></div>

            <div class="space-y-1.5 text-sm md:text-base text-slate-500 mb-7">
              <p>
                Short Stay:
                <span class="font-medium text-slate-700">Monthly only</span>
              </p>
              <p>
                Yearly Rent:
                <span class="font-medium text-slate-700">
                  Rp <?php echo number_format($kost['price_month'] * 12, 0, ',', '.'); ?>
                </span>
              </p>
            </div>

            <!-- Tombol lebih panjang -->
            <a href="booking.php?id=<?php echo $kostId; ?>"
   class="block w-full rounded-full bg-[#001b6f] py-3.5 md:py-4 text-sm md:text-base font-semibold text-white shadow-md hover:bg-[#001454] transition text-center">
  Book Now
</a>


            <!-- Property Inquiry & Contact Host sejajar -->
            <div class="mt-7 flex items-center justify-between gap-6 text-sm md:text-base text-slate-700">
              <button class="flex items-center gap-2 hover:text-[#001b6f]">
                <i class="fa-solid fa-building text-base md:text-lg"></i>
                <span class="whitespace-nowrap">Property Inquiry</span>
              </button>

              <a
                href="messages.php?kost_id=<?php echo (int)$kostId; ?>"
                class="flex items-center gap-2 hover:text-[#001b6f]"
              >
                <i class="fa-regular fa-comment-dots text-base md:text-lg"></i>
                <span class="whitespace-nowrap">Contact Host</span>
              </a>
            </div>
          </div>
        </aside>
      </section>

      <!-- ====== AMENITIES + SAFETY + MAP + NEARBY SERVICES ====== -->
      <section class="mt-12 lg:mt-14 border-t border-slate-100 pt-8 md:pt-10">
        <!-- Offered Amenities -->
        <div>
          <h2 class="text-xl md:text-2xl font-semibold text-slate-900">
            Offered Amenities
          </h2>

          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-10">
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-kitchen-set text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Kitchen</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-car text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Spacious Parking Area</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-regular fa-snowflake text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Air Conditioner</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-wifi text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Free Wireless Internet</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-soap text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Washer</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-house-chimney text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Balcony or Patio</span>
            </div>
          </div>

          <button
            class="mt-7 inline-flex items-center justify-center rounded-xl border border-slate-300 px-6 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
          >
            Show All 10 Amenities
          </button>
        </div>

        <!-- Safety and Hygiene -->
        <div class="mt-10">
          <h2 class="text-xl md:text-2xl font-semibold text-slate-900">
            Safety and Hygiene
          </h2>

          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-10">
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-spray-can-sparkles text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Daily Cleaning</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-fire-extinguisher text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Fire Extinguishers</span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-pump-soap text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">
                Disinfections and Sterilizations
              </span>
            </div>
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-bell text-lg text-slate-600"></i>
              <span class="text-sm md:text-base text-slate-700">Smoke Detectors</span>
            </div>
          </div>
        </div>

        <!-- Map -->
        <div class="mt-10">
          <!-- ganti src dengan gambar map kamu sendiri -->
          <img
            src="img/map.png"
            alt="Location map"
            class="w-full h-[260px] md:h-[320px] lg:h-[360px] rounded-3xl object-cover shadow-md"
          />
        </div>

        <!-- Nearby Services -->
        <div class="mt-12">
          <h2 class="text-xl md:text-2xl font-semibold text-slate-900">
            Nearby Services
          </h2>

          <!-- cards -->
          <div class="mt-6 flex items-stretch gap-4 overflow-x-auto pb-2">
            <!-- Card 1 -->
            <div class="min-w-[220px] rounded-2xl bg-white shadow-md px-5 py-4">
              <p class="text-sm md:text-base font-semibold text-slate-800">
                Ayam Geprek Express
              </p>
              <p class="mt-1 text-xs md:text-sm text-slate-500">
                250 meters away
              </p>
              <div class="mt-2 text-[#484848] text-sm">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-regular fa-star"></i>
              </div>
            </div>

            <!-- Card 2 -->
            <div class="min-w-[220px] rounded-2xl bg-white shadow-md px-5 py-4">
              <p class="text-sm md:text-base font-semibold text-slate-800">
                The Grillhouse Bistro
              </p>
              <p class="mt-1 text-xs md:text-sm text-slate-500">
                560 meters away
              </p>
              <div class="mt-2 text-[#484848] text-sm">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
              </div>
            </div>

            <!-- Card 3 -->
            <div class="min-w-[220px] rounded-2xl bg-white shadow-md px-5 py-4">
              <p class="text-sm md:text-base font-semibold text-slate-800">
                Fresh Bowl
              </p>
              <p class="mt-1 text-xs md:text-sm text-slate-500">
                100 meters away
              </p>
              <div class="mt-2 text-[#484848] text-sm">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-regular fa-star"></i>
              </div>
            </div>

            <!-- Arrow button -->
            <button
              class="flex items-center justify-center self-center h-12 w-12 rounded-full bg-[#2459ff] text-white shadow-md flex-shrink-0"
            >
              <i class="fa-solid fa-arrow-right text-lg"></i>
            </button>
          </div>

          <!-- Show On Map button -->
          <button
            class="mt-6 inline-flex items-center justify-center rounded-full bg-[#5f7cff] px-8 py-3 text-sm md:text-base font-medium text-white shadow-md hover:bg-[#4767e5] transition"
          >
            Show On Map
          </button>
        </div>
      </section>

      <!-- ========= REVIEWS SECTION ========= -->
      <section class="mt-12 lg:mt-14 border-t border-slate-100 pt-8 md:pt-10">
        <!-- Header + rating -->
        <div class="flex items-center gap-3">
          <h2 class="text-xl md:text-2xl font-semibold text-slate-900">
            Reviews
          </h2>
          <i class="fa-solid fa-star text-[#484848] text-lg"></i>
          <span class="text-lg md:text-xl font-semibold text-slate-900">4.8</span>
        </div>

        <!-- Rating summary -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl">
          <!-- Left side -->
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-4">
              <span class="text-sm md:text-base text-slate-700">Amenities</span>
              <div class="flex items-center gap-3">
                <div class="text-[#484848] text-sm">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                </div>
                <span class="text-sm md:text-base text-slate-800 font-medium">4.8</span>
              </div>
            </div>

            <div class="flex items-center justify-between gap-4">
              <span class="text-sm md:text-base text-slate-700">Communication</span>
              <div class="flex items-center gap-3">
                <div class="text-[#484848] text-sm">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
                <span class="text-sm md:text-base text-slate-800 font-medium">5.0</span>
              </div>
            </div>

            <div class="flex items-center justify-between gap-4">
              <span class="text-sm md:text-base text-slate-700">Value for Money</span>
              <div class="flex items-center gap-3">
                <div class="text-[#484848] text-sm">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                </div>
                <span class="text-sm md:text-base text-slate-800 font-medium">4.7</span>
              </div>
            </div>
          </div>

          <!-- Right side -->
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-4">
              <span class="text-sm md:text-base text-slate-700">Hygiene</span>
              <div class="flex items-center gap-3">
                <div class="text-[#484848] text-sm">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </div>
                <span class="text-sm md:text-base text-slate-800 font-medium">5.0</span>
              </div>
            </div>

            <div class="flex items-center justify-between gap-4">
              <span class="text-sm md:text-base text-slate-700">Comfort</span>
              <div class="flex items-center gap-3">
                <div class="text-[#484848] text-sm">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                </div>
                <span class="text-sm md:text-base text-slate-800 font-medium">4.5</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Review cards -->
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-10">
          <!-- Review 1 -->
          <div>
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-200">
                <img src="img/oliver.png" alt="Oliver Mitchell" class="w-full h-full object-cover"
                     onerror="this.style.display='none';">
              </div>
              <div>
                <p class="text-sm md:text-base font-semibold text-slate-900">
                  Oliver Mitchell
                </p>
                <p class="text-xs md:text-sm text-slate-500">
                  Mar 12 2024
                </p>
              </div>
            </div>
            <p class="mt-3 text-sm md:text-base text-slate-600 leading-relaxed">
              Great room, clean and modern. The bed is comfy, but water pressure could be stronger.
            </p>
          </div>

          <!-- Review 2 -->
          <div>
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-200">
                <img src="img/alexander.png" alt="Alexander Lewis" class="w-full h-full object-cover"
                     onerror="this.style.display='none';">
              </div>
              <div>
                <p class="text-sm md:text-base font-semibold text-slate-900">
                  Alexander Lewis
                </p>
                <p class="text-xs md:text-sm text-slate-500">
                  Nov 17 2024
                </p>
              </div>
            </div>
            <p class="mt-3 text-sm md:text-base text-slate-600 leading-relaxed">
              Well-maintained place and quick response from the owner. Worth the price!
            </p>
          </div>

          <!-- Review 3 -->
          <div>
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-200">
                <img src="img/noah.png" alt="Noah Wilson" class="w-full h-full object-cover"
                     onerror="this.style.display='none';">
              </div>
              <div>
                <p class="text-sm md:text-base font-semibold text-slate-900">
                  Noah Wilson
                </p>
                <p class="text-xs md:text-sm text-slate-500">
                  Jan 22 2025
                </p>
              </div>
            </div>
            <p class="mt-3 text-sm md:text-base text-slate-600 leading-relaxed">
              Nice place, but the AC wasn’t cold enough some nights. Fixed fast though.
            </p>
          </div>

          <!-- Review 4 -->
          <div>
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-200">
                <img src="img/jordan.png" alt="Jordan Lee" class="w-full h-full object-cover"
                     onerror="this.style.display='none';">
              </div>
              <div>
                <p class="text-sm md:text-base font-semibold text-slate-900">
                  Jordan Lee
                </p>
                <p class="text-xs md:text-sm text-slate-500">
                  Apr 29 2025
                </p>
              </div>
            </div>
            <p class="mt-3 text-sm md:text-base text-slate-600 leading-relaxed">
              Calm and safe area, feels like home. WiFi sometimes slows down, but overall great.
            </p>
          </div>
        </div>

        <!-- Show all reviews button -->
        <button
          class="mt-8 inline-flex items-center justify-center rounded-xl border border-slate-300 px-8 py-3 text-sm md:text-base font-medium text-slate-800 hover:bg-slate-50 transition"
        >
          Show All 100 Reviews
        </button>
      </section>

    </div>
  </main>

  <!-- FOOTER -->
      <?php 
        @include('footer.php')
      ?>

</body>
</html>
