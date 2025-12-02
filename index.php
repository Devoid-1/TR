<?php
require 'config.php';

// --- FLAG WELCOME POPUP SETELAH SIGNUP/LOGIN ---
$showWelcome = !empty($_SESSION['show_welcome']);
$welcomeName = $_SESSION['user_name'] ?? 'User';
if ($showWelcome) {
    // supaya popup hanya sekali
    unset($_SESSION['show_welcome']);
}

$currentFile = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin' && $currentFile !== 'admin.php') {
        header('Location: admin.php');
        exit;
    }

    if ($_SESSION['role'] === 'host' && $currentFile !== 'host_dashboard.php') {
        header('Location: host_dashboard.php');
        exit;
    }
}



// --- FLAG LOGIN ERROR & AUTO OPEN LOGIN MODAL ---
$loginError = $_SESSION['login_error'] ?? '';
$openLoginModal = !empty($_SESSION['login_open']);
if ($openLoginModal) {
    unset($_SESSION['login_open']);
}
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ThreeKost</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Kirim flag popup dari PHP ke JS -->
    <script>
      window.SHOW_WELCOME_MODAL = <?php echo $showWelcome ? 'true' : 'false'; ?>;
      window.WELCOME_NAME = <?php echo json_encode($welcomeName); ?>;

      window.SHOW_LOGIN_MODAL = <?php echo $openLoginModal ? 'true' : 'false'; ?>;
      window.LOGIN_ERROR = <?php echo json_encode($loginError); ?>;
    </script>

    <!-- JS utama (modal dll) -->
    <script src="script.js" defer></script>

    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      html {
        scroll-behavior: smooth;
      }

      body {
        font-family: "Poppins", sans-serif;
      }
    </style>
    
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  
  <body class="bg-white text-gray-900">
    <?php 
        @include('navbar.php')
      ?>

    <!-- Hero Section -->
    <section    
    
      class="relative mt-[70px] h-[90vh] bg-center bg-cover"
      style="background-image: url('img/bghero.png')"
    >
      <div class="absolute inset-0 bg-black/40"></div>

      <div
        class="relative z-10 flex h-full flex-col items-center justify-center px-4 text-center text-white"
      >
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-wide">
          WELCOME TO THREEKOST!
        </h1>
        <p class="mt-4 max-w-2xl text-sm md:text-base">
          Filter locations, compare prices, check facilities, and save your
          favorite kosts — all in one platform, ThreeKost.
        </p>

        <div
          class="mt-6 flex w-full max-w-2xl items-center rounded-full bg-white px-4 py-2 shadow-lg"
        >
          <input
            type="text"
            placeholder="Looking for a comfy room? Near campus? Just type it here..."
            class="flex-1 border-none outline-none text-sm md:text-base px-2 text-gray-800"
          />
          <!-- tombol filter: buka popup -->
          <button
            type="button"
            id="openFilter"
            class="flex items-center justify-center rounded-full bg-blue-500 text-white p-3 hover:bg-blue-600 transition"
          >
            <i class="fas fa-sliders-h"></i>
          </button>
        </div>
      </div>
    </section>

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
    <!-- ================= END FILTER POPUP ================= -->

    <!-- About Section -->
    <section
      class="flex flex-col md:flex-row items-start justify-center gap-10 md:gap-20 px-5 md:px-10 lg:px-20 py-16 md:py-24"
    >
      <div class="flex justify-center md:justify-start">
        <img
          src="img/about.png"
          alt="ThreeKost About"
          class="w-full max-w-[470px] rounded-[35px] object-cover"
        />
      </div>

      <div class="max-w-xl">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
          About <span class="text-[#2979ff]">ThreeKost</span>
        </h2>
        <p class="mt-2 text-[15px] md:text-base leading-relaxed text-gray-800">
          ThreeKost is a modern room-finding platform that helps you discover
          the best boarding houses quickly, easily, and reliably. We’re here to
          make it simple for students, workers, and travelers to find a kost
          that fits their needs—from strategic locations and affordable prices
          to complete facilities. With smart search, location filters, price
          comparison, and an interactive map, ThreeKost ensures you can find
          your ideal place without the need for in-person visits.
        </p>
        <p class="mt-4 text-[15px] md:text-base leading-relaxed text-gray-800">
          Every kost listed on ThreeKost is carefully verified to ensure
          accuracy, safety, and comfort, allowing you to make the right choice
          with confidence. Whether you’re searching for your next home or
          showcasing your property, ThreeKost connects people, places, and
          possibilities.
        </p>
      </div>
    </section>

    <!-- Stats Section -->
    <section
      class="flex flex-wrap items-center justify-center gap-10 md:gap-16 py-16 px-4 bg-white"
    >
      <div class="text-center mx-4">
        <img src="img/10years.png" alt="" class="w-24 mx-auto mb-2" />
        <h3 class="mt-2 text-xl font-semibold">10+ Year</h3>
        <p class="text-sm text-gray-500">Experience</p>
      </div>
      <div class="text-center mx-4">
        <img src="img/follower.png" alt="" class="w-24 mx-auto mb-2" />
        <h3 class="mt-2 text-xl font-semibold">10.000+</h3>
        <p class="text-sm text-gray-500">Follower</p>
      </div>
      <div class="text-center mx-4">
        <img src="img/landlord.png" alt="" class="w-24 mx-auto mb-2" />
        <h3 class="mt-2 text-xl font-semibold">70.000+</h3>
        <p class="text-sm text-gray-500">Landlord</p>
      </div>
      <div class="text-center mx-4">
        <img src="img/tenant.png" alt="" class="w-24 mx-auto mb-2" />
        <h3 class="mt-2 text-xl font-semibold">80.000+</h3>
        <p class="text-sm text-gray-500">Tenant</p>
      </div>
    </section>

    <!-- Why Choose Section -->
    <section class="flex flex-col md:flex-row min-h-[520px]">
      <!-- KIRI -->
      <div
        class="flex-1 px-8 sm:px-12 lg:px-16 py-12 sm:py-16 lg:py-20 text-white"
        style="background: #001d5e url('img/bgwhy.png') center/cover no-repeat"
      >
        <h2
          class="text-3xl sm:text-[36px] lg:text-[40px] font-bold leading-tight"
        >
          Why Choose <span class="text-[#4aa8ff]">ThreeKost ?</span>
        </h2>

        <div
          class="mt-10 lg:mt-12 grid grid-cols-1 sm:grid-cols-2 gap-x-12 lg:gap-x-20 gap-y-10 sm:gap-y-14 max-w-[620px]"
        >
          <div class="flex items-start gap-3 sm:gap-4">
            <img
              src="img/affordable.png"
              alt="Affordable Prices"
              class="w-12 sm:w-14 lg:w-16 flex-shrink-0"
            />
            <div>
              <h4 class="font-semibold text-base sm:text-lg mb-1">
                Affordable Prices
              </h4>
              <p class="text-sm sm:text-[15px] leading-relaxed opacity-90">
                Enjoy budget-friendly kost options without compromising comfort
                and security.
              </p>
            </div>
          </div>

          <div class="flex items-start gap-3 sm:gap-4">
            <img
              src="img/credible.png"
              alt="Credible & Trustworthy"
              class="w-12 sm:w-14 lg:w-16 flex-shrink-0"
            />
            <div>
              <h4 class="font-semibold text-base sm:text-lg mb-1">
                Credible &amp; Trustworthy
              </h4>
              <p class="text-sm sm:text-[15px] leading-relaxed opacity-90">
                From daily to monthly kosts, we offer various room types
                tailored to your needs.
              </p>
            </div>
          </div>

          <div class="flex items-start gap-3 sm:gap-4">
            <img
              src="img/strategic.png"
              alt="Strategic & Trusted Locations"
              class="w-12 sm:w-14 lg:w-16 flex-shrink-0"
            />
            <div>
              <h4 class="font-semibold text-base sm:text-lg mb-1">
                Strategic &amp; Trusted Locations
              </h4>
              <p class="text-sm sm:text-[15px] leading-relaxed opacity-90">
                All kosts are verified to ensure you get the best place to stay
                in prime areas.
              </p>
            </div>
          </div>

          <div class="flex items-start gap-3 sm:gap-4">
            <img
              src="img/24.png"
              alt="24/7 Customer Support"
              class="w-12 sm:w-14 lg:w-16 flex-shrink-0"
            />
            <div>
              <h4 class="font-semibold text-base sm:text-lg mb-1">
                24/7 Customer Support
              </h4>
              <p class="text-sm sm:text-[15px] leading-relaxed opacity-90">
                Our team is ready to assist you anytime, making your kost search
                easier and more enjoyable.
              </p>
            </div>
          </div>
        </div>
      </div>
            
      <!-- KANAN -->
      <div
        class="relative flex-1 flex items-center justify-center min-h-[360px] md:min-h-[520px]"
        style="
          background-image: url('img/why.png');
          background-size: cover;
          background-position: center;
        "
      >
        <div class="absolute inset-0 bg-[rgba(0,20,80,0.45)]"></div>

        <div
          class="relative w-52 h-52 sm:w-60 sm:h-60 lg:w-[280px] lg:h-[280px] rounded-full bg-white flex items-center justify-center"
        >
          <img
            src="img/logo1.jpg"
            alt="ThreeKost Logo"
            class="max-w-[75%] h-auto"
          />
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="bg-white px-5 sm:px-10 lg:px-[60px] pt-[120px] pb-[140px]">
      <div
        class="mx-auto max-w-[1200px] flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-[40px]"
      >
        <div class="flex-1 lg:basis-[440px]">
          <h2
            class="text-[40px] sm:text-[50px] lg:text-[60px] font-bold mb-4 leading-tight text-[#111]"
          >
            Our services
          </h2>

          <div class="w-[170px] h-[2px] bg-[#111] mt-[10px] mb-[30px]"></div>

          <p
            class="max-w-[420px] text-[16px] sm:text-[18px] lg:text-[20px] leading-[2] text-[#333]"
          >
            Easy rooms, smooth marketing, trusted endorsements — all in
            Threekost.
          </p>
        </div>

        <div class="flex-1 lg:basis-[540px] flex justify-center">
          <div
            class="w-full max-w-[540px] bg-[#001974] text-white rounded-[70px] pt-[64px] pb-[150px] px-[80px] shadow-[0_22px_48px_rgba(0,0,0,0.2)]"
          >
            <img
              src="img/lamp.png"
              alt="Looking for a kost"
              class="w-[95px] mx-auto mb-[36px]"
            />

            <h3
              class="text-[32px] sm:text-[36px] lg:text-[40px] font-semibold mb-[26px]"
            >
              Looking for a kost
            </h3>

            <p
              class="text-[16px] sm:text-[17px] lg:text-[18px] leading-[2] mb-[42px]"
            >
              Finding a kost just got easier, faster, and more reliable!
              Discover your ideal room on Threekost now!
            </p>

            <a
              href="kost_list.php"
              class="w-full py-5 px-6 rounded-full border-0 bg-[#e6e6e6] text-[#111] font-bold text-[18px] hover:bg-[#d8d8d8] hover:-translate-y-[1px] hover:shadow-[0_8px_20px_rgba(0,0,0,0.15)] transition text-center block"
            >
              Find a kost now
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Hosting Section -->
    <section class="px-5 md:px-10 lg:px-16 py-20 bg-white">
      <div class="relative rounded-[40px] overflow-hidden shadow-2xl">
        <img
          src="img/hosting.png"
          alt="ThreeKost Building"
          class="w-full h-auto"
        />

        <div
          class="absolute inset-0 bg-gradient-to-r from-white/85 via-white/60 to-transparent"
        ></div>

        <div class="absolute top-1/2 left-[8%] -translate-y-1/2 max-w-md">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            Try Hosting<br />With Us
          </h2>
          <p class="text-base md:text-lg text-gray-700 mb-6">
            Earn extra just by renting your property...
          </p>

        </div>
      </div>
    </section>

    <!-- Smart Renting Tips Section -->
    <section
      class="px-5 md:px-10 lg:px-16 py-16 bg-white max-w-[1150px] mx-auto"
    >
      <div class="mb-8">
        <h2 class="text-3xl md:text-4xl font-bold leading-tight text-gray-800">
          Smart Renting<br />Tips
        </h2>
        <div class="w-36 h-[3px] bg-gray-700 mt-3"></div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-4">
        <article class="flex flex-col">
          <img
            src="img/budget.png"
            alt="Budget-Friendly Living"
            class="w-full h-auto"
          />
          <div class="pt-3">
            <h3 class="text-base md:text-lg font-semibold mb-1">
              Budget-Friendly Living
            </h3>
            <span class="text-xs md:text-sm text-gray-500">Economy</span>
          </div>
        </article>

        <article class="flex flex-col">
          <img
            src="img/smart.png"
            alt="Smart Renting Guide"
            class="w-full h-auto"
          />
          <div class="pt-3">
            <h3 class="text-base md:text-lg font-semibold mb-1">
              Smart Renting Guide
            </h3>
            <span class="text-xs md:text-sm text-gray-500"
              >Essential advice</span
            >
          </div>
        </article>

        <article class="flex flex-col">
          <img
            src="img/comfort.png"
            alt="Comfort & Lifestyle"
            class="w-full h-auto"
          />
          <div class="pt-3">
            <h3 class="text-base md:text-lg font-semibold mb-1">
              Comfort & Lifestyle
            </h3>
            <span class="text-xs md:text-sm text-gray-500">Ideas</span>
          </div>
        </article>
      </div>

      <div class="flex justify-center mt-10">
        <a
        href="kost_list.php"
        class="rounded-full bg-[#4f6bff] px-9 py-3 text-sm md:text-base font-semibold text-white shadow-lg hover:bg-[#3c57e3] transition text-center inline-block"
      >
        View All Blogs
      </a>
      </div>
    </section>

    <!-- Discover More Amazing Kost Section -->
    <section class="px-5 md:px-10 lg:px-16 py-16 bg-white">
      <div
        class="relative overflow-hidden rounded-[32px] md:rounded-[40px] shadow-2xl"
      >
        <img
          src="img/discover.png"
          alt="Discover More Amazing Kost"
          class="w-full h-[260px] sm:h-[320px] md:h-[380px] lg:h-[420px] object-cover"
        />

        <div
          class="absolute inset-0 bg-gradient-to-r from-black/55 via-black/25 to-transparent"
        ></div>

        <div
          class="absolute inset-y-0 left-0 flex items-center px-6 sm:px-10 lg:px-16"
        >
          <div class="max-w-xl text-white">
            <h2
              class="text-3xl sm:text-[34px] md:text-[40px] lg:text-[44px] font-bold leading-tight mb-4 text-[#484848]"
            >
              Discover More<br class="hidden sm:block" />
              Amazing Kost
            </h2>

            <p class="text-sm sm:text-base md:text-[17px] mb-6">
              Discover different types of kosts that suit your needs...
            </p>

            <a
            href="kost_list.php"
              class="inline-flex items-center justify-center rounded-full bg-[#1d4fff] px-8 sm:px-10 py-3 sm:py-3.5 text-sm sm:text-base font-semibold shadow-lg hover:bg-[#1438d9] transition"
            >
                Find A Property
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Top Rated Kosts Section -->
    <section class="bg-white px-5 md:px-10 lg:px-16 py-16">
      <div class="max-w-7xl mx-auto">
        <div class="mb-10">
          <h2
            class="text-3xl md:text-4xl font-bold leading-tight text-[#484848]"
          >
            Top Rated<br />Kosts
          </h2>
          <div class="w-32 h-[3px] bg-[#484848] mt-4"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Card 1 -->
          <article
            class="relative flex flex-col rounded-[26px] overflow-hidden shadow-[0_18px_40px_rgba(0,0,0,0.15)] bg-white"
          >
            <div class="relative">
              <img
                src="img/skyview.png"
                alt="SkyView Kost Exclusive"
                class="w-full h-56 md:h-60 object-cover"
              />

              <div
                class="absolute top-3 left-3 flex items-center gap-2 text-gray-800"
              >
                <div class="flex gap-1 text-gray-700">
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">⯪</span>
                  <span class="text-2xl not-italic leading-none">☆</span>
                </div>
                <span
                  class="text-xs bg-white/90 text-gray-700 px-2 py-0.5 rounded-md"
                  >3.5</span
                >
              </div>

              <button
                class="absolute top-3 right-3 w-10 h-10 rounded-[14px] bg-white/95 flex items-center justify-center shadow-md"
              >
                <i class="text-2xl not-italic leading-none text-gray-700">♡</i>
              </button>

              <div
                class="absolute bottom-4 left-5 w-16 h-16 rounded-full overflow-hidden shadow-md"
              >
                <img
                  src="img/host1.png"
                  alt="Host SkyView"
                  class="w-full h-full object-cover"
                />
              </div>
            </div>

            <div
              class="bg-[#6E8CFB] h-[110px] px-5 pt-6 pb-4 text-white flex flex-col justify-end"
            >
              <h3 class="font-semibold text-[18px] leading-snug mb-1">
                SkyView Kost Exclusive
              </h3>
              <p class="text-xs md:text-sm text-white/90">
                <span class="mr-1">📍</span>Jl. Pandanaran No. 18, Semarang.
              </p>
            </div>
          </article>

          <!-- Card 2 -->
          <article
            class="relative flex flex-col rounded-[26px] overflow-hidden shadow-[0_18px_40px_rgba(0,0,0,0.15)] bg-white"
          >
            <div class="relative">
              <img
                src="img/cozy.png"
                alt="CozyNest Kost"
                class="w-full h-56 md:h-60 object-cover"
              />

              <div
                class="absolute top-3 left-3 flex items-center gap-2 text-gray-800"
              >
                <div class="flex gap-1 text-gray-700">
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">⯪</span>
                  <span class="text-2xl not-italic leading-none">☆</span>
                  <span class="text-2xl not-italic leading-none">☆</span>
                </div>
                <span
                  class="text-xs bg-white/90 text-gray-700 px-2 py-0.5 rounded-md"
                  >2.5</span
                >
              </div>

              <button
                class="absolute top-3 right-3 w-10 h-10 rounded-[14px] bg-white/95 flex items-center justify-center shadow-md"
              >
                <i class="text-2xl not-italic leading-none text-gray-700">♡</i>
              </button>

              <div
                class="absolute bottom-4 left-5 w-16 h-16 rounded-full overflow-hidden shadow-md"
              >
                <img
                  src="img/host2.jpg"
                  alt="Host CozyNest"
                  class="w-full h-full object-cover"
                />
              </div>
            </div>

            <div
              class="bg-[#6E8CFB] h-[110px] px-5 pt-6 pb-4 text-white flex flex-col justify-end"
            >
              <h3 class="font-semibold text-[18px] leading-snug mb-1">
                CozyNest Kost
              </h3>
              <p class="text-xs md:text-sm text-white/90">
                <span class="mr-1">📍</span>Jl. Pahlawan No. 12, Salatiga.
              </p>
            </div>
          </article>

          <!-- Card 3 -->
          <article
            class="relative flex flex-col rounded-[26px] overflow-hidden shadow-[0_18px_40px_rgba(0,0,0,0.15)] bg-white"
          >
            <div class="relative">
              <img
                src="img/harmony.png"
                alt="Harmony Kost Family"
                class="w-full h-56 md:h-60 object-cover"
              />

              <div
                class="absolute top-3 left-3 flex items-center gap-2 text-gray-800"
              >
                <div class="flex gap-1 text-gray-700">
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">⯪</span>
                </div>
                <span
                  class="text-xs bg-white/90 text-gray-700 px-2 py-0.5 rounded-md"
                  >4.5</span
                >
              </div>

              <button
                class="absolute top-3 right-3 w-10 h-10 rounded-[14px] bg-white/95 flex items-center justify-center shadow-md"
              >
                <i class="text-2xl not-italic leading-none text-gray-700">♡</i>
              </button>

              <div
                class="absolute bottom-4 left-5 w-16 h-16 rounded-full overflow-hidden shadow-md"
              >
                <img
                  src="img/host3.png"
                  alt="Host Harmony"
                  class="w-full h-full object-cover"
                />
              </div>
            </div>

            <div
              class="bg-[#6E8CFB] h-[110px] px-5 pt-6 pb-4 text-white flex flex-col justify-end"
            >
              <h3 class="font-semibold text-[18px] leading-snug mb-1">
                Harmony Kost Family
              </h3>
              <p class="text-xs md:text-sm text-white/90">
                <span class="mr-1">📍</span>Jl. Anggrek Indah No. 7, Banjarsari,
                Surakarta.
              </p>
            </div>
          </article>

          <!-- Card 4 -->
          <article
            class="relative flex flex-col rounded-[26px] overflow-hidden shadow-[0_18px_40px_rgba(0,0,0,0.15)] bg-white"
          >
            <div class="relative">
              <img
                src="img/smartstay.png"
                alt="SmartStay Kost"
                class="w-full h-56 md:h-60 object-cover"
              />

              <div
                class="absolute top-3 left-3 flex items-center gap-2 text-gray-800"
              >
                <div class="flex gap-1 text-gray-700">
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                  <span class="text-2xl not-italic leading-none">★</span>
                </div>
                <span
                  class="text-xs bg-white/90 text-gray-700 px-2 py-0.5 rounded-md"
                  >5.0</span
                >
              </div>

              <button
                class="absolute top-3 right-3 w-10 h-10 rounded-[14px] bg-white/95 flex items-center justify-center shadow-md"
              >
                <i class="text-2xl not-italic leading-none text-gray-700">♡</i>
              </button>

              <div
                class="absolute bottom-4 left-5 w-16 h-16 rounded-full overflow-hidden shadow-md"
              >
                <img
                  src="img/host4.png"
                  alt="Host SmartStay"
                  class="w-full h-full object-cover"
                />
              </div>
            </div>

            <div
              class="bg-[#6E8CFB] h-[110px] px-5 pt-6 pb-4 text-white flex flex-col justify-end"
            >
              <h3 class="font-semibold text-[18px] leading-snug mb-1">
                SmartStay Kost
              </h3>
              <p class="text-xs md:text-sm text-white/90">
                <span class="mr-1">📍</span>Jl. Dr. Soetomo No. 21, Purwokerto,
                Banyumas.
              </p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Download Our Mobile App Section -->
    <section class="bg-white py-16">
      <div class="max-w-9xl mx-auto px-6 md:px-12 lg:px-20">
        <div
          class="bg-[#A8B8FF] rounded-[32px] md:rounded-[40px] px-8 md:px-12 lg:px-20 py-12 md:py-16 flex flex-col md:flex-row items-center justify-between gap-10"
        >
          <div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">
              Download Our<br />Mobile App
            </h2>

            <p class="text-white text-sm md:text-base mb-8">
              Available for free on these platforms
            </p>

            <div class="flex flex-wrap items-center gap-4">
              <button
                class="flex items-center gap-3 bg-white px-7 py-3 rounded-xl shadow-sm hover:shadow-md transition"
              >
                <img
                  src="img/playstore.png"
                  alt="PlayStore"
                  class="w-6"
                />
                <span class="font-medium text-gray-800">PlayStore</span>
              </button>

              <button
                class="flex items-center gap-3 bg-white px-7 py-3 rounded-xl shadow-sm hover:shadow-md transition"
              >
                <img
                  src="img/apple.png"
                  alt="AppleStore"
                  class="w-6"
                />
                <span class="font-medium text-gray-800">AppleStore</span>
              </button>
            </div>
          </div>

          <div class="flex justify-center w-full md:w-auto">
            <img
              src="img/phone.png"   
              alt="Mobile App Illustration"
              class="w-40 md:w-52 lg:w-60 object-contain"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Smart Guide Section -->
    <section class="bg-white py-20 md:py-28">
      <div
        class="max-w-7xl mx-auto px-4 md:px-8 lg:px-12 flex flex-col lg:flex-row items-center gap-14 lg:gap-20"
      >
        <div class="flex-1">
          <h2
            class="text-xl md:text-4xl lg:text-4xl font-bold leading-tight text-[#4A4A4A]"
          >
            Smart Guide to Comfortable<br />
            and Safe Kost Renting
          </h2>

          <div class="w-24 h-1.5 bg-[#4A4A4A] rounded-full mt-8 mb-10"></div>

          <p
            class="max-w-xl text-2xl md:text-base leading-relaxed text-gray-400"
          >
            Find practical tips and insights to help you choose the perfect kost.
            From understanding rental terms to ensuring safety and comfort, this
            guide helps make your kost-hunting journey smarter, easier, and
            worry-free.
          </p>

          <div class="mt-10 space-y-8">
            <div
              class="flex flex-col sm:flex-row gap-4 sm:gap-10 text-sm md:text-base font-semibold text-[#4A4A4A]"
            >
              <button class="hover:text-blue-500 transition">
                Ask A Question
              </button>
              <button class="hover:text-blue-500 transition">
                Find A Property
              </button>
            </div>

            <button
              class="inline-flex items-center justify-center px-10 md:px-14 py-4 rounded-full bg[#2F80FF] bg-[#2F80FF] text-white font-semibold text-sm md:text-base shadow-md hover:bg-[#2163d6] transition"
            >
              Discover More
            </button>
          </div>
        </div>

        <div class="flex-1 flex justify-center">
          <img
            src="img/smartguide.png"  
            alt="Smart Guide Kost"
            class="w-80 max-w-md lg:max-w-lg object-cover"
          />
        </div>
      </div>
    </section>

     <!-- Footer -->
      <?php 
        @include('footer.php')
      ?>

        <!-- SIGN UP MODAL -->
    <div
      id="signupModal"
      class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 px-4"
    >
      <!-- card -->
      <div
        class="relative w-full max-w-4xl bg-white rounded-[32px] md:rounded-[40px] shadow-2xl overflow-hidden"
      >
        <!-- tombol close -->
        <button
          id="closeSignup"
          type="button"
          class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 text-2xl"
        >
          &times;
        </button>

        <div class="flex flex-col items-center py-10 md:py-14 px-6 md:px-16">
          <!-- logo + Sign Up -->
          <div class="w-full flex items-center justify-between mb-10">
            <img
              src="img/logo1.jpg"
              alt="ThreeKost Logo"
              class="h-14 md:h-16 w-auto object-contain"
            />
            <span class="text-2xl md:text-3xl font-semibold text-[#5B5FC7]">
              Sign Up
            </span>
          </div>

          <!-- judul (berubah antara step 1 & 2) -->
          <h2
            id="signupTitle"
            class="text-2xl md:text-4xl font-bold text-gray-900 text-left w-full mb-8"
          >
            Enter your details
          </h2>

          <!-- FORM 2 STEP -->
          <form
            id="signupForm"
            action="signup_process.php"
            method="POST"
            class="w-full"
          >
            <!-- STEP 1: NAMA & EMAIL -->
            <div id="signupStep1" class="w-full space-y-5">
              <!-- full name -->
              <div>
                <input
                  type="text"
                  name="full_name"
                  required
                  placeholder="Full name as per ID card"
                  class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
                />
              </div>

              <!-- email -->
              <div>
                <input
                  type="email"
                  name="email"
                  required
                  placeholder="Email"
                  class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
                />
              </div>

              <!-- login link -->
              <div class="pt-3 text-center text-xs md:text-sm text-gray-600">
                <span>Already have an account? </span>
                <button
                  type="button"
                  id="openLoginFromSignup"
                  class="text-[#2F80FF] font-semibold underline"
                >
                  Log in
                </button>
              </div>

              <!-- tombol Next -->
              <div class="pt-2">
                <button
                  type="button"
                  id="btnNextStep1"
                  class="w-full md:w-52 mx-auto block rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
                >
                  Next
                </button>
              </div>
            </div>
            <!-- END STEP 1 -->

            <!-- STEP 2: PASSWORD -->
            <div id="signupStep2" class="w-full space-y-5 hidden">
              <!-- field password -->
              <div class="relative">
                <input
                  type="password"
                  name="password"
                  id="passwordField"
                  required
                  placeholder="New Password"
                  class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 pr-11 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
                />
                <button
                  type="button"
                  id="togglePassword"
                  class="absolute inset-y-0 right-3 flex items-center text-gray-400"
                  tabindex="-1"
                >
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>

              <!-- field confirm password -->
              <div class="relative">
                <input
                  type="password"
                  name="confirm_password"
                  id="confirmPasswordField"
                  required
                  placeholder="Confirm password"
                  class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 pr-11 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
                />
                <button
                  type="button"
                  id="toggleConfirmPassword"
                  class="absolute inset-y-0 right-3 flex items-center text-gray-400"
                  tabindex="-1"
                >
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>

              <!-- aturan password -->
              <ul class="mt-2 space-y-1 text-xs md:text-sm text-gray-600">
                <li class="flex items-center">
                  <span class="text-green-500 mr-2">✔</span>
                  Your password cannot contain your email address
                </li>
                <li class="flex items-center">
                  <span class="text-green-500 mr-2">✔</span>
                  Must contain at least one uppercase letter
                </li>
                <li class="flex items-center">
                  <span class="text-green-500 mr-2">✔</span>
                  Use a minimum of 8 characters
                </li>
                <li class="flex items-center">
                  <span class="text-green-500 mr-2">✔</span>
                  Password must include at least one symbol or number
                </li>
              </ul>

              <!-- tombol Back + Create Password -->
              <div
                class="pt-4 flex flex-col md:flex-row items-center justify-between gap-3"
              >
                <button
                  type="button"
                  id="btnBackStep2"
                  class="w-full md:w-auto text-sm md:text-base text-gray-500 hover:text-gray-700"
                >
                  Back
                </button>

                <button
                  type="submit"
                  class="w-full md:w-56 rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
                >
                  Create Password
                </button>
              </div>
            </div>
            <!-- END STEP 2 -->
          </form>
        </div>
      </div>
    </div>
    <!-- END SIGN UP MODAL -->

   <!-- WELCOME MODAL -->
    <div
      id="welcomeModal"
      class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/40 px-4"
    >
      <div
        class="relative w-full max-w-4xl bg-white rounded-[32px] md:rounded-[40px] shadow-2xl overflow-hidden"
      >
        <div class="flex flex-col items-center py-10 md:py-14 px-6 md:px-16">
          <!-- Logo -->
          <div class="w-full flex justify-center mb-10">
            <img
              src="img/logo1.jpg"
              alt="ThreeKost Logo"
              class="h-20 md:h-24 w-auto object-contain"
            />
          </div>

          <!-- Teks -->
          <div class="w-full max-w-xl text-left mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-[#5B5FC7] mb-3">
              Halo,<br />
              <span id="welcomeName">User</span>
            </h2>
            <p class="text-sm md:text-base text-gray-500">
              Welcome to Threekost
            </p>
          </div>

          <!-- Tombol Next -->
          <button
            id="welcomeNextBtn"
            type="button"
            class="mt-2 w-40 md:w-48 rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 shadow-md hover:bg-[#4a4fb7] transition"
          >
            Next
          </button>
        </div>
      </div>
    </div>
    <!-- END WELCOME MODAL -->

    <!-- LOGIN MODAL -->
    <div
      id="loginModal"
      class="fixed inset-0 z-[65] hidden items-center justify-center bg-black/40 px-4"
    >
      <div
        class="relative w-full max-w-4xl bg-white rounded-[32px] md:rounded-[40px] shadow-2xl overflow-hidden"
      >
        <!-- tombol close -->
        <button
          id="closeLogin"
          type="button"
          class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 text-2xl"
        >
          &times;
        </button>

        <div class="flex flex-col items-center py-10 md:py-14 px-6 md:px-16">
          <!-- logo + Log In -->
          <div class="w-full flex items-center justify-between mb-10">
            <img
              src="img/logo1.jpg"
              alt="ThreeKost Logo"
              class="h-14 md:h-16 w-auto object-contain"
            />
            <span class="text-2xl md:text-3xl font-semibold text-[#5B5FC7]">
              Log In
            </span>
          </div>

          <!-- Error box -->
          <div
            id="loginErrorBox"
            class="hidden w-full mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-xs md:text-sm"
          >
            <span id="loginErrorText"></span>
          </div>

          <!-- PHONE LOGIN SECTION -->
          <div id="phoneLoginSection" class="w-full max-w-2xl">
            <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-8">
              Enter your phone number
            </h2>

            <form
              action="login_process.php"
              method="POST"
              class="space-y-6"
            >
              <input type="hidden" name="login_type" value="phone" />

              <!-- phone input -->
              <div class="flex gap-3">
                <div
                  class="flex items-center border border-[#B8C4FF] rounded-xl px-3 md:px-4"
                >
                  <select
                    name="country_code"
                    class="bg-transparent text-sm md:text-base outline-none py-3.5 md:py-3 cursor-pointer"
                  >
                    <option value="+62">+62</option>
                    <option value="+60">+60</option>
                    <option value="+65">+65</option>
                  </select>
                </div>

                <input
                  type="text"
                  name="phone"
                  required
                  placeholder="e.g. 81234567890"
                  class="flex-1 border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
                />
              </div>

              <div class="text-center text-xs md:text-sm text-gray-600">
                <p>Already registered with your phone number?</p>
                <p class="mt-1">
                  or
                  <button
                    type="button"
                    id="linkToEmailLogin"
                    class="text-[#2F80FF] font-semibold underline"
                  >
                    Log in with email
                  </button>
                </p>
              </div>

              <!-- Divider -->
              <div class="flex items-center gap-3 text-xs md:text-sm text-gray-400">
                <span class="flex-1 h-px bg-[#B8C4FF]"></span>
                <span>Or Continue With</span>
                <span class="flex-1 h-px bg-[#B8C4FF]"></span>
              </div>

              <!-- Social icons (dummy) -->
              <div class="flex justify-center gap-6 text-2xl">
                <i class="fab fa-facebook text-[#1877F2]"></i>
                <i class="fab fa-google text-[#DB4437]"></i>
                <i class="fab fa-apple text-black"></i>
              </div>

              <!-- Next -->
              <div class="pt-2">
                <button
                  type="submit"
                  class="w-full md:w-52 mx-auto block rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
                >
                  Next
                </button>
              </div>
            </form>
          </div>
          <!-- END PHONE LOGIN -->

          <!-- EMAIL LOGIN SECTION -->
          <div
            id="emailLoginSection"
            class="w-full max-w-2xl hidden"
          >
            <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-8">
              Log in with email
            </h2>

            <form
              action="login_process.php"
              method="POST"
              class="space-y-6"
            >
              <input type="hidden" name="login_type" value="email" />

              <div>
                <input
                  type="email"
                  name="email_login"
                  required
                  placeholder="Email"
                  class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
                />
              </div>

              <div>
                <input
                  type="password"
                  name="password_login"
                  required
                  placeholder="Password"
                  class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
                />
              </div>

              <div class="text-center text-xs md:text-sm text-gray-600">
                <p>Prefer using phone number?</p>
                <p class="mt-1">
                  <button
                    type="button"
                    id="linkToPhoneLogin"
                    class="text-[#2F80FF] font-semibold underline"
                  >
                    Log in with phone number
                  </button>
                </p>
              </div>

              <!-- Divider -->
              <div class="flex items-center gap-3 text-xs md:text-sm text-gray-400">
                <span class="flex-1 h-px bg-[#B8C4FF]"></span>
                <span>Or Continue With</span>
                <span class="flex-1 h-px bg-[#B8C4FF]"></span>
              </div>

              <!-- Social icons (dummy) -->
              <div class="flex justify-center gap-6 text-2xl">
                <i class="fab fa-facebook text-[#1877F2]"></i>
                <i class="fab fa-google text-[#DB4437]"></i>
                <i class="fab fa-apple text-black"></i>
              </div>

              <div class="pt-2">
                <button
                  type="submit"
                  class="w-full md:w-52 mx-auto block rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
                >
                  Next
                </button>
              </div>
            </form>
          </div>
          <!-- END EMAIL LOGIN -->
        </div>
      </div>
    </div>
    <!-- END LOGIN MODAL -->

    <!-- JS kecil untuk filter popup  -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const filterOverlay = document.getElementById('filterOverlay');
        const openFilterBtn = document.getElementById('openFilter');
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

        openFilterBtn && openFilterBtn.addEventListener('click', openFilter);
        closeFilterBtn && closeFilterBtn.addEventListener('click', closeFilter);

        filterOverlay && filterOverlay.addEventListener('click', function (e) {
          if (e.target === filterOverlay) {
            closeFilter();
          }
        });
      });
    </script>

  </body>
</html>
