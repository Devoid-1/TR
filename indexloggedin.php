<?php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ThreeKost</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- JS utama (modal dll) -->
    <script src="script.js" defer></script>

    <!-- Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
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
    <!-- Navbar -->
    <header class="fixed top-0 left-0 z-50 w-full bg-white shadow-md">
      <div
        class="flex items-center justify-between h-[70px] px-4 md:px-8 lg:px-16"
      >
        <div class="flex items-center">
          <img
            src="img/logo1.jpg"
            alt="ThreeKost Logo"
            class="h-12 md:h-16 w-auto object-contain"
          />
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

          <!-- Icon user -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Sudah login: ke profile -->
            <a href="profile.php" class="hidden md:inline-flex items-center justify-center">
              <i class="fas fa-user-circle text-2xl cursor-pointer"></i>
            </a>
          <?php else: ?>
            <!-- Belum login: buka modal signup (desktop) -->
            <button
              id="openSignup"
              type="button"
              class="hidden md:inline-flex items-center justify-center"
            >
              <i class="fas fa-user-circle text-2xl cursor-pointer"></i>
            </button>

            <!-- Belum login: icon user di mobile (opsional) -->
            <button
              id="openSignupMobile"
              type="button"
              class="md:hidden flex items-center justify-center"
            >
              <i class="fas fa-user-circle text-2xl cursor-pointer"></i>
            </button>
          <?php endif; ?>
        </div>
      </div>
    </header>

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
          <button
            class="flex items-center justify-center rounded-full bg-blue-500 text-white p-3 hover:bg-blue-600 transition"
          >
            <i class="fas fa-sliders-h"></i>
          </button>
        </div>
      </div>
    </section>

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

            <button
              class="w-full py-5 px-6 rounded-full border-0 bg-[#e6e6e6] text-[#111] font-bold text-[18px] hover:bg-[#d8d8d8] hover:-translate-y-[1px] hover:shadow-[0_8px_20px_rgba(0,0,0,0.15)] transition"
            >
              Find a kost now
            </button>
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
          <button
            class="inline-block rounded-full bg-[#4f6bff] px-8 py-3 text-white font-semibold text-base md:text-lg shadow-lg hover:bg-[#3b57e5] transition"
          >
            Become A Host
          </button>
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
        <button
          class="rounded-full bg-[#4f6bff] px-9 py-3 text-sm md:text-base font-semibold text-white shadow-lg hover:bg-[#3c57e3] transition"
        >
          View All Blogs
        </button>
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

            <button
              class="inline-flex items-center justify-center rounded-full bg-[#1d4fff] px-8 sm:px-10 py-3 sm:py-3.5 text-sm sm:text-base font-semibold shadow-lg hover:bg-[#1438d9] transition"
            >
              Find A Property
            </button>
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
              class="inline-flex items-center justify-center px-10 md:px-14 py-4 rounded-full bg-[#2F80FF] text-white font-semibold text-sm md:text-base shadow-md hover:bg-[#2163d6] transition"
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
    <footer class="bg-white mt-16">
      <!-- NEWSLETTER BAR -->
      <div class="bg-[#A9D4FF]">
        <div
          class="max-w-6xl mx-auto px-6 md:px-10 lg:px-16 py-5 md:py-6 flex flex-col md:flex-row items-center gap-4 md:gap-8 justify-between"
        >
          <div>
            <h3 class="text-sm md:text-base font-bold tracking-wide text-gray-800">
              NEWSLETTER
            </h3>
            <p class="text-xs md:text-sm text-gray-700">Stay Upto Date</p>
          </div>

          <form class="w-full md:flex-1">
            <div
              class="flex items-center bg-white rounded-full pl-6 pr-2 py-2 md:py-3 shadow-sm"
            >
              <input
                type="email"
                placeholder="Your Email..."
                class="flex-1 bg-transparent outline-none text-sm md:text-base text-gray-700 placeholder:text-gray-400"
              />
              <button
                type="submit"
                class="flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-full bg-[#0065FF] text-white"
              >
                <i class="fas fa-paper-plane text-sm"></i>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- MAIN FOOTER CONTENT -->
      <div class="max-w-7xl mx-auto px-6 md:px-10 lg:px-16 py-14 md:py-20">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">
          <div class="lg:w-[40%] xl:w-[38%]">
            <img
              src="img/logo1.jpg"
              alt="ThreeKost Logo"
              class="h-20 md:h-24 w-auto mb-4"
            />

            <p
              class="text-base md:text-lg text-gray-500 leading-relaxed mb-8 max-w-md"
            >
              Three Kost is a modern room-rental web app that makes it easy
              to find comfortable, affordable housing that fits your
              needs—no in-person visits required.
            </p>

            <div class="flex flex-wrap items-center gap-4">
              <button
                class="flex items-center gap-3 bg-[#ECECEC] px-7 py-3 rounded-xl text-base md:text-lg text-gray-800"
              >
                <img
                  src="img/playstore.png"
                  alt="PlayStore"
                  class="w-5 md:w-6"
                />
                <span>PlayStore</span>
              </button>

              <button
                class="flex items-center gap-3 bg-[#ECECEC] px-7 py-3 rounded-xl text-base md:text-lg text-gray-800"
              >
                <img
                  src="img/apple.png"
                  alt="AppleStore"
                  class="w-5 md:w-6"
                />
                <span>AppleStore</span>
              </button>
            </div>
          </div>

          <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-8 lg:gap-6 xl:gap-8">
            <div>
              <h4
                class="text-base md:text-lg font-bold tracking-wide text-gray-800 mb-3"
              >
                COMPANY
              </h4>
              <ul class="space-y-1.5 text-sm md:text-base text-gray-700">
                <li><a href="#" class="hover:text-blue-500">About Us</a></li>
                <li>
                  <a href="#" class="hover:text-blue-500">Legal Information</a>
                </li>
                <li><a href="#" class="hover:text-blue-500">Contact Us</a></li>
                <li><a href="#" class="hover:text-blue-500">Blogs</a></li>
              </ul>
            </div>

            <div>
              <h4
                class="text-base md:text-lg font-bold tracking-wide text-gray-800 mb-3"
              >
                HELP CENTER
              </h4>
              <ul class="space-y-1.5 text-sm md:text-base text-gray-700">
                <li>
                  <a href="#" class="hover:text-blue-500">Find a Property</a>
                </li>
                <li><a href="#" class="hover:text-blue-500">How To Host?</a></li>
                <li><a href="#" class="hover:text-blue-500">Why Us?</a></li>
                <li><a href="#" class="hover:text-blue-500">FAQs</a></li>
                <li>
                  <a href="#" class="hover:text-blue-500">Rental Guides</a>
                </li>
              </ul>
            </div>

            <div>
              <h4
                class="text-base md:text-lg font-bold tracking-wide text-gray-800 mb-3"
              >
                CONTACT INFO
              </h4>
              <ul class="space-y-1.5 text-sm md:text-base text-gray-700 mb-5">
                <li>Phone: 892364729</li>
                <li>Email: threekost@gmail.com</li>
                <li>Location: Salatiga, Central Java</li>
              </ul>

              <div class="flex items-center gap-4 text-gray-700 text-2xl">
                <a href="#" class="hover:text-blue-600">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="hover:text-blue-600">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="hover:text-blue-600">
                  <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="hover:text-blue-600">
                  <i class="fab fa-linkedin-in"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- BOTTOM BAR -->
      <div class="border-t">
        <div
          class="max-w-6xl mx-auto px-6 md:px-10 lg:px-16 py-4 text-xs md:text-sm text-gray-500"
        >
          © 2022 thecreation.design | All rights reserved
        </div>
      </div>
    </footer>

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

          <!-- judul -->
          <h2
            class="text-2xl md:text-4xl font-bold text-gray-900 text-left w-full mb-8"
          >
            Enter your details
          </h2>

          <!-- form -->
          <form action="signup_process.php" method="POST" class="w-full space-y-5">
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
              <a href="login.php" class="text-[#2F80FF] font-semibold">
                Log in
              </a>
            </div>

            <!-- tombol Next -->
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
      </div>
    </div>
    <!-- END SIGN UP MODAL -->

  </body>
</html>
