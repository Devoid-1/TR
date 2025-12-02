<?php
session_start();
require 'config.php';

// Harus login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Ambil id kost dari URL
$kostId = isset($_GET['kost_id']) ? (int) $_GET['kost_id'] : 0;
if ($kostId <= 0) {
    // Kalau tidak ada kost, balik ke profile
    header('Location: profile.php');
    exit;
}

// Ambil data kost dari database
$stmt = $conn->prepare("SELECT name, main_image FROM kosts WHERE id = ?");
$stmt->bind_param("i", $kostId);
$stmt->execute();
$result = $stmt->get_result();
$kost   = $result->fetch_assoc();
$stmt->close();

if (!$kost) {
    header('Location: profile.php');
    exit;
}

// Data untuk tampilan
$kostName  = htmlspecialchars($kost['name']);
$kostImage = !empty($kost['main_image']) ? htmlspecialchars($kost['main_image']) : 'img/default-kost.jpg';
$today     = date('d M Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Messages - <?php echo $kostName; ?> | ThreeKost</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
<body class="bg-slate-50 text-slate-900">
  <?php @include('navbar.php'); ?>

  <main class="pt-24 md:pt-28 pb-16 px-4 md:px-8 lg:px-16 max-w-6xl mx-auto">
    <!-- Back -->
    <div class="flex items-center gap-3 mb-6">
      <a href="javascript:history.back()" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-100">
        <i class="fa-solid fa-chevron-left text-slate-700 text-sm"></i>
      </a>
      <h1 class="text-xl md:text-2xl font-semibold text-slate-900">All Messages</h1>
    </div>

    <!-- Chat layout -->
    <div class="bg-[#404884] rounded-3xl p-4 md:p-6 lg:p-8 text-white flex flex-col md:flex-row gap-6 shadow-lg">
      <!-- LEFT: list kost / chat -->
      <aside class="w-full md:w-1/3 border-b md:border-b-0 md:border-r border-white/15 pb-4 md:pb-0 md:pr-5">
        <div class="space-y-4">
          <!-- Active kost (yang baru di-click) -->
          <button class="w-full flex items-center gap-3 bg-white/10 hover:bg-white/15 rounded-2xl p-3 text-left">
            <div class="w-12 h-12 rounded-full overflow-hidden bg-white/20 flex-shrink-0">
              <img
                src="<?php echo $kostImage; ?>"
                alt="<?php echo $kostName; ?>"
                class="w-full h-full object-cover"
                onerror="this.style.display='none';"
              />
            </div>
            <div class="flex-1">
              <p class="text-sm md:text-base font-semibold leading-snug">
                <?php echo $kostName; ?>
              </p>
              <p class="text-[11px] md:text-xs text-white/70 mt-0.5">
                On: <?php echo $today; ?>
              </p>
            </div>
          </button>

          <!-- Contoh chat lain (dummy) -->
          <div class="flex items-center gap-3 opacity-60">
            <div class="w-11 h-11 rounded-full overflow-hidden bg-white/10 flex-shrink-0">
              <img
                src="img/default-avatar.png"
                alt="Another Kost"
                class="w-full h-full object-cover"
                onerror="this.style.display='none';"
              />
            </div>
            <div>
              <p class="text-sm font-medium">Other Kost</p>
              <p class="text-[11px] text-white/60">On: 12 Aug 2023</p>
            </div>
          </div>
        </div>
      </aside>

      <!-- RIGHT: conversation -->
      <section class="flex-1 flex flex-col justify-between">
        <!-- Bubbles -->
        <div class="space-y-4 md:space-y-5">
          <!-- Chat 1 -->
          <div class="flex justify-end">
            <div class="inline-block bg-white text-slate-900 rounded-3xl px-4 py-3 max-w-[80%] shadow">
              <p class="text-xs md:text-sm leading-relaxed">
                Hello, good afternoon. Sorry to bother you. Is this the number for <?php echo $kostName; ?> boarding house?
              </p>
              <p class="mt-1 text-[10px] md:text-[11px] text-slate-500 text-right">10:05 AM</p>
            </div>
          </div>

          <!-- Chat 2 -->
          <div class="flex justify-start">
            <div class="inline-block bg-white text-slate-900 rounded-3xl px-4 py-3 max-w-[80%] shadow">
              <p class="text-xs md:text-sm leading-relaxed">
                Good afternoon. Yes, that's correct. I'm the owner. How can I help you?
              </p>
              <p class="mt-1 text-[10px] md:text-[11px] text-slate-500 text-right">10:06 AM</p>
            </div>
          </div>

          <!-- Chat 3 -->
          <div class="flex justify-end">
            <div class="inline-block bg-white text-slate-900 rounded-3xl px-4 py-3 max-w-[80%] shadow">
              <p class="text-xs md:text-sm leading-relaxed">
                I'd like to ask if there are still rooms available for this month?
              </p>
              <p class="mt-1 text-[10px] md:text-[11px] text-slate-500 text-right">10:07 AM</p>
            </div>
          </div>

          <!-- Chat 4 -->
          <div class="flex justify-start">
            <div class="inline-block bg-white text-slate-900 rounded-3xl px-4 py-3 max-w-[80%] shadow">
              <p class="text-xs md:text-sm leading-relaxed">
                Yes, we still have a few rooms available.
              </p>
              <p class="mt-1 text-[10px] md:text-[11px] text-slate-500 text-right">10:08 AM</p>
            </div>
          </div>
        </div>

        <!-- Input -->
        <!-- BUKAN form lagi, cuma tampilan -->
<div class="mt-6">
  <div class="flex items-center gap-3 bg-white rounded-full px-4 py-2 md:py-3 shadow-inner">
    <input
      type="text"
      placeholder="Type your message..."
      class="flex-1 bg-transparent border-0 focus:outline-none focus:ring-0 text-sm md:text-base text-slate-800 placeholder-slate-400"
      autocomplete="off"
    />
    <button
      type="button"
      class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-[#FFC857] flex items-center justify-center shadow hover:bg-[#ffb835] transition cursor-default"
      onclick="return false;"
    >
      <i class="fa-solid fa-paper-plane text-[#343F7A] text-sm md:text-base"></i>
    </button>
  </div>
</div>


      </section>
    </div>
  </main>

  <?php @include('footer.php'); ?>
</body>
</html>
