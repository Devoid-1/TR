<?php
session_start();
require 'config.php';

// wajib login dulu
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// ambil kost_id dari URL
$kostId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($kostId <= 0) {
    header('Location: search_kost.php');
    exit;
}

// ambil data kost
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

$mainImage  = htmlspecialchars($kost['main_image']);
$kostName   = htmlspecialchars($kost['name']);
$address    = htmlspecialchars($kost['address']);
$city       = htmlspecialchars($kost['city']);
$priceMonth = (int)$kost['price_month'];        // as number
$deposit    = 50000;                              // contoh deposit tetap

// ================== PROSES FORM ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name'] ?? '');
    $nik            = trim($_POST['nik'] ?? '');
    $phone_number   = trim($_POST['phone_number'] ?? '');


    if ($full_name === '' || $nik === '' || $phone_number === '') {
        $error = "Semua field wajib diisi.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO bookings
            (user_id, kost_id, full_name, nik, phone_number, monthly_rent, deposit_amount, payment_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
        ");

        $stmt->bind_param(
    "iisssii",
    $userId,
    $kostId,
    $full_name,
    $nik,
    $phone_number,
    $priceMonth,
    $deposit
);


        if ($stmt->execute()) {
            $bookingId = $conn->insert_id;
            $stmt->close();
            // redirect ke halaman payment
            header("Location: payment.php?booking_id=" . $bookingId);
            exit;
        } else {
            $error = "Gagal menyimpan booking: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking Form - ThreeKost</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Font Awesome (untuk ikon bedroom/bathroom/parking & star) -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
  />

  <style>
    html {
        scroll-behavior: smooth;
      }
      
    body { font-family:"Poppins",sans-serif; }
  </style>
</head>
<body class="bg-[#f7f7fb] text-slate-900">
  <?php 
        @include('navbar.php')
      ?>

  <main class="pt-24 pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-0">

      <!-- TITLE -->
      <h1 class="text-3xl md:text-4xl font-semibold text-[#343F7A] mb-8">
        Booking Form
      </h1>

      <!-- KARTU INFO KOST (BIRU) -->
      <section class="bg-[#343F7A] text-white rounded-[40px] shadow-lg px-6 py-8 md:px-12 md:py-10 lg:px-16 lg:py-12">
        <div class="grid gap-10 md:grid-cols-[minmax(0,1.05fr)_minmax(0,1.4fr)] items-center">
          <!-- FOTO KOST -->
          <div class="flex justify-center">
            <img
              src="<?php echo $mainImage; ?>"
              alt="<?php echo $kostName; ?>"
              class="w-full max-w-sm h-56 md:h-64 lg:h-72 object-cover rounded-[26px] shadow-md"
            />
          </div>

          <!-- INFO KOST -->
          <div class="flex flex-col gap-6">
            <!-- NAMA + ICON BED/BATH/PARKING (JANGAN DIUBAH STYLE-NYA) -->
            <div class="text-center">
              <h2 class="text-2xl md:text-3xl lg:text-4xl font-semibold">
                <?php echo $kostName; ?>
              </h2>

              <!-- garis tipis -->
              <div class="mt-2 border-t border-white/60 w-full mx-auto"></div>

              <!-- icon row -->
              <div class="mt-3 flex flex-wrap justify-center gap-x-10 gap-y-2 text-sm md:text-base">
                <span class="inline-flex items-center gap-2">
                  <i class="fa-solid fa-bed text-lg"></i>
                  <span>1 Bedroom</span>
                </span>
                <span class="inline-flex items-center gap-2">
                  <i class="fa-solid fa-bath text-lg"></i>
                  <span>1 Bathroom</span>
                </span>
                <span class="inline-flex items-center gap-2">
                  <i class="fa-solid fa-car-side text-lg"></i>
                  <span>1 Parking</span>
                </span>
              </div>
            </div>

            <!-- ALAMAT + RATE -->
            <div class="text-sm md:text-base">
              <p class="flex items-center justify-center md:justify-start gap-2">
                <span class="text-lg">📍</span>
                <span><?php echo $address; ?>, <?php echo $city; ?></span>
              </p>
              <p class="mt-2 text-center md:text-left">
                Rate :
                <span class="text-[#FFD54F] text-lg ml-1">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                </span>
              </p>
            </div>

            <!-- PRICE DETAILS + DEPOSIT -->
            <div class="mt-4 grid gap-10 md:grid-cols-[minmax(0,1.25fr)_minmax(0,0.9fr)] items-center">
              <!-- PRICE DETAILS BOX -->
              <div class="border border-[#b7c0f5] rounded-[20px] px-6 py-5 md:px-8 md:py-6 text-sm md:text-base">
                <p class="text-xl md:text-2xl font-semibold mb-4">
                  Price Details
                </p>

                <div class="grid grid-cols-[auto_auto] gap-y-2 gap-x-4">
                  <span>Monthly :</span>
                  <span>Rp. <?php echo number_format($priceMonth, 0, ',', '.'); ?></span>

                  <span>Weekly :</span>
                  <span>Rp. <?php echo number_format($priceMonth / 4, 0, ',', '.'); ?></span>

                  <span>Daily :</span>
                  <span>Rp. <?php echo number_format($priceMonth / 30, 0, ',', '.'); ?></span>
                </div>
              </div>

              <!-- DEPOSIT TEXT (TANPA FRAME) -->
              <div class="text-center text-[#f8b2a0] font-semibold">
                <p class="text-lg md:text-xl">
                  Deposit Amount
                </p>
                <p class="mt-3 text-2xl md:text-3xl lg:text-4xl">
                  Rp <?php echo number_format($deposit, 0, ',', '.'); ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FORM BOOKING (CARD PUTIH) -->
      <section class="mt-10 bg-white rounded-[40px] shadow-md px-6 py-8 md:px-10 lg:px-14">
        <h2 class="text-xl md:text-2xl font-semibold text-[#343F7A] mb-6 text-center">
          Booking Details
        </h2>

        <?php if (!empty($error)): ?>
          <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5 max-w-xl mx-auto">
          <!-- Name -->
          <div class="flex items-center gap-4">
            <label class="w-32 text-sm md:text-base">Name</label>
            <div class="flex-1">
              <input
                type="text"
                name="full_name"
                class="w-full rounded-lg border border-[#b7c0f5] px-3 py-2 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-[#5f7cff]"
                required
              />
            </div>
          </div>

          <!-- NIK -->
          <div class="flex items-center gap-4">
            <label class="w-32 text-sm md:text-base">NIK</label>
            <div class="flex-1">
              <input
                type="text"
                name="nik"
                class="w-full rounded-lg border border-[#b7c0f5] px-3 py-2 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-[#5f7cff]"
                required
              />
            </div>
          </div>

          <!-- Phone -->
          <div class="flex items-center gap-4">
            <label class="w-32 text-sm md:text-base">Phone number</label>
            <div class="flex-1">
              <input
                type="text"
                name="phone_number"
                class="w-full rounded-lg border border-[#b7c0f5] px-3 py-2 text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-[#5f7cff]"
                required
              />
            </div>
          </div>

          <!-- BUTTONS -->
          <div class="flex flex-col md:flex-row justify-center gap-4 mt-8">
            <a
              href="detail_kost.php?id=<?php echo $kostId; ?>"
              class="inline-flex items-center justify-center rounded-full bg-gray-300 text-gray-800 px-10 py-2.5 text-sm md:text-base font-medium hover:bg-gray-400"
            >
              Cancel
            </a>
            <button
              type="submit"
              class="inline-flex items-center justify-center rounded-full bg-[#5f7cff] text-white px-10 py-2.5 text-sm md:text-base font-semibold hover:bg-[#4767e5]"
            >
              Make Payment
            </button>
          </div>
        </form>
      </section>
    </div>
  </main>

  <!-- FOOTER ambil dari index.php -->
      <?php 
        @include('footer.php')
      ?> 
     <script src="script.js" defer></script>
</body>
</html>
