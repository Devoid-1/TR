<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId    = $_SESSION['user_id'];
$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
if ($bookingId <= 0) {
    header('Location: index.php');
    exit;
}

/**
 * JIKA USER KLIK "CONFIRM PAYMENT"
 * -> update status booking jadi 'paid'
 * -> lalu redirect ke profile
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
  // ambil method & category dari form
  $allowedCategories = ['M-Banking', 'E-Wallet'];
  $allowedMethods    = ['BCA','BRI','BNI','Mandiri','Gopay','ShopeePay','Dana','OVO','LinkAja'];

  $paymentMethod   = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

  // jika JS belum set value, buang nilai placeholder
  if ($paymentMethod === 'data-payment-method') {
    $paymentMethod = '';
  }

  // validasi whitelist
  if (!in_array($paymentMethod, $allowedMethods, true)) {
    $paymentMethod = null;
  }

  // update sekaligus simpan method/category 
  $stmt = $conn->prepare("
    UPDATE bookings
    SET payment_status = 'paid',
      payment_method = ?,
      created_at = NOW()
    WHERE id = ? AND user_id = ?
  ");
  $stmt->bind_param("sii", $paymentMethod, $bookingId, $userId);
  $stmt->execute();
  $stmt->close();

  header("Location: profile.php?payment=success");
  exit;
}

// ambil data booking + kost + user (untuk nama di modal)
$stmt = $conn->prepare("
    SELECT 
        b.*,
        k.name       AS kost_name,
        k.main_image,
        k.address,
        k.city,
        u.full_name  AS user_name
    FROM bookings b
    JOIN kosts k ON b.kost_id = k.id
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ? AND b.user_id = ?
");
$stmt->bind_param("ii", $bookingId, $userId);
$stmt->execute();
$result  = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    header('Location: index.php');
    exit;
}

$mainImage  = htmlspecialchars($booking['main_image']);
$kostName   = htmlspecialchars($booking['kost_name']);
$address    = htmlspecialchars($booking['address']);
$city       = htmlspecialchars($booking['city']);
$priceMonth = (int)$booking['monthly_rent'];
$deposit    = (int)$booking['deposit_amount'];
$statusText = ($booking['payment_status'] === 'paid') ? 'Paid' : 'Pending Payment';
$userName   = htmlspecialchars($booking['user_name']);
$totalPayment = $priceMonth + $deposit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Payment - ThreeKost</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
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
  body{
    font-family:"Poppins",sans-serif;
  }
  </style>
</head>
<body class="bg-gray-50 text-slate-900">
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <main class="pt-24 pb-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-0">

      <!-- TITLE -->
      <h1 class="text-3xl md:text-4xl font-semibold text-[#343F7A] mb-8">
        Payment
      </h1>

      <!-- KARTU INFO KOST -->
      <section class="bg-[#343F7A] text-white rounded-[40px] shadow-lg px-6 py-8 md:px-12 md:py-10 lg:px-16 lg:py-12">
        <div class="grid gap-10 md:grid-cols-[minmax(0,1.05fr)_minmax(0,1.4fr)] items-center">
          <div class="flex justify-center">
            <img
              src="<?php echo $mainImage; ?>"
              alt="<?php echo $kostName; ?>"
              class="w-full max-w-sm h-56 md:h-64 lg:h-72 object-cover rounded-[26px] shadow-md"
            />
          </div>

          <div class="flex flex-col gap-6">
            <div class="text-center">
              <h2 class="text-2xl md:text-3xl lg:text-4xl font-semibold">
                <?php echo $kostName; ?>
              </h2>
              <div class="mt-2 border-t border-white/60 w-full mx-auto"></div>

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

            <div class="mt-4 grid gap-10 md:grid-cols-[minmax(0,1.25fr)_minmax(0,0.9fr)] items-center">
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

      <!-- SELECT PAYMENT MODE -->
      <section class="bg-white rounded-3xl shadow-md px-6 py-6 md:px-10 md:py-8 mt-8">
        <h2 class="text-xl md:text-2xl font-semibold text-[#343F7A] text-center mb-6">
          Select Payment Mode
        </h2>

        <!-- M-Banking -->
        <div
          id="mbanking-box"
          class="border-2 border-[#5f7cff] rounded-2xl px-6 py-4 mb-6 cursor-pointer shadow-[0_0_25px_rgba(95,124,255,0.25)] bg-white"
        >
          <p class="text-[#5f7cff] font-semibold mb-3 text-sm md:text-base">
            M-Banking
          </p>

          <!-- tampilan kecil -->
          <div id="mbanking-collapsed" class="flex flex-wrap items-center gap-6">
            <img src="paymentimg/bca.png"     alt="BCA"     class="h-8 md:h-10 object-contain" />
            <img src="paymentimg/bri.png"     alt="BRI"     class="h-8 md:h-10 object-contain" />
            <img src="paymentimg/bni.png"     alt="BNI"     class="h-8 md:h-10 object-contain" />
            <img src="paymentimg/mandiri.png" alt="Mandiri" class="h-8 md:h-10 object-contain" />
          </div>

          <!-- tampilan setelah expand -->
          <div id="mbanking-expanded" class="hidden mt-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-24 md:h-28 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="BCA"
                data-payment-category="M-Banking"
              >
                <img src="paymentimg/bca.png" alt="BCA" class="h-10 md:h-12 object-contain" />
              </button>

              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-24 md:h-28 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="BRI"
                data-payment-category="M-Banking"
              >
                <img src="paymentimg/bri.png" alt="BRI" class="h-10 md:h-12 object-contain" />
              </button>

              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-24 md:h-28 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="BNI"
                data-payment-category="M-Banking"
              >
                <img src="paymentimg/bni.png" alt="BNI" class="h-10 md:h-12 object-contain" />
              </button>

              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-24 md:h-28 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="Mandiri"
                data-payment-category="M-Banking"
              >
                <img src="paymentimg/mandiri.png" alt="Mandiri" class="h-10 md:h-12 object-contain" />
              </button>
            </div>
          </div>
        </div>

        <!-- E-Wallet -->
        <div
          id="ewallet-box"
          class="border-2 border-[#5f7cff] rounded-2xl px-6 py-4 cursor-pointer shadow-[0_0_25px_rgba(95,124,255,0.25)] bg-white"
        >
          <p class="text-[#5f7cff] font-semibold mb-3 text-sm md:text-base">
            E-Wallet
          </p>

          <div id="ewallet-collapsed" class="flex flex-wrap items-center gap-6">
            <img src="paymentimg/gopay.png"   alt="Gopay"     class="h-8 md:h-10 object-contain" />
            <img src="paymentimg/shopee.png"  alt="ShopeePay" class="h-8 md:h-10 object-contain" />
            <img src="paymentimg/dana.png"    alt="Dana"      class="h-8 md:h-10 object-contain" />
            <img src="paymentimg/ovo.png"     alt="OVO"       class="h-8 md:h-10 object-contain" />
            <img src="paymentimg/linkaja.png" alt="LinkAja"   class="h-8 md:h-10 object-contain" />
          </div>

          <div id="ewallet-expanded" class="hidden mt-4">
            <div class="grid grid-cols-3 md:grid-cols-5 gap-4 md:gap-6">
              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-20 md:h-24 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="Gopay"
                data-payment-category="E-Wallet"
              >
                <img src="paymentimg/gopay.png" alt="Gopay" class="h-10 object-contain" />
              </button>

              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-20 md:h-24 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="ShopeePay"
                data-payment-category="E-Wallet"
              >
                <img src="paymentimg/shopee.png" alt="ShopeePay" class="h-10 object-contain" />
              </button>

              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-20 md:h-24 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="Dana"
                data-payment-category="E-Wallet"
              >
                <img src="paymentimg/dana.png" alt="Dana" class="h-10 object-contain" />
              </button>

              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-20 md:h-24 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="OVO"
                data-payment-category="E-Wallet"
              >
                <img src="paymentimg/ovo.png" alt="OVO" class="h-10 object-contain" />
              </button>

              <button
                type="button"
                class="bg-white border border-[#BFD0FF] rounded-2xl h-20 md:h-24 flex items-center justify-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                data-payment-method="LinkAja"
                data-payment-category="E-Wallet"
              >
                <img src="paymentimg/linkaja.png" alt="LinkAja" class="h-10 object-contain" />
              </button>
            </div>
          </div>
        </div>

        <div class="flex justify-center mt-10">
          <a href="profile.php"
            class="inline-flex items-center justify-center rounded-full bg-gray-300 text-gray-800 px-10 py-2.5 text-sm md:text-base font-medium hover:bg-gray-400">
            Cancel
          </a>
        </div>
      </section>
    </div>
  </main>

  <!-- MODAL KONFIRMASI PAYMENT -->
  <div
    id="payment-modal"
    class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden"
  >
    <div class="bg-white rounded-3xl max-w-md w-full mx-4 p-6 md:p-8 shadow-2xl">
      <h3 class="text-lg md:text-xl font-semibold text-[#343F7A] mb-4">
        Payment Confirmation
      </h3>

      <div class="space-y-2 text-sm md:text-base text-slate-700 mb-4">
        <p><span class="font-medium">Name:</span> <?php echo $userName; ?></p>
        <p><span class="font-medium">Kost:</span> <?php echo $kostName; ?></p>
        <p>
          <span class="font-medium">Payment Method:</span>
          <span id="modal-method" class="text-[#5f7cff] font-semibold"></span>
        </p>
        <p>
          <span class="font-medium">Kost Price (Monthly):</span>
          <span>Rp <?php echo number_format($priceMonth, 0, ',', '.'); ?></span>
        </p>
        <p>
          <span class="font-medium">Deposit:</span>
          <span>Rp <?php echo number_format($deposit, 0, ',', '.'); ?></span>
        </p>
        <p class="pt-2 border-t border-slate-200 mt-2">
          <span class="font-semibold">Total Payment:</span>
          <span class="font-semibold text-emerald-600">
            Rp <?php echo number_format($totalPayment, 0, ',', '.'); ?>
          </span>
        </p>
      </div>

      <!-- FORM POST UNTUK CONFIRM PAYMENT -->
      <form method="POST" class="flex justify-end gap-3 mt-4">
        <input type="hidden" name="payment_method"  value="data-payment-method"  id="payment-method-input">

        <button
          id="payment-modal-close"
          type="button"
          class="px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-sm md:text-base hover:bg-slate-100"
        >
          Close
        </button>

        <?php if ($booking['payment_status'] === 'paid'): ?>
  <!-- Kalau udah paid tombol tidak submit, cuma popup -->
  <button
    type="button"
    class="px-5 py-2 rounded-full bg-gray-400 text-white text-sm md:text-base font-semibold cursor-not-allowed"
  >
    Paid
  </button>
<?php else: ?>
  <!-- Kalau belum paid tombol normal, bisa bayar -->
  <button
    type="submit"
    name="confirm_payment"
    class="px-5 py-2 rounded-full bg-[#5f7cff] text-white text-sm md:text-base font-semibold hover:bg-[#4767e5]"
  >
    Confirm Payment
  </button>
<?php endif; ?>

      </form>
    </div>
  </div>

  <!-- Footer -->
  <?php 
        @include('footer.php')
      ?>

  <!-- JS global -->
  <script src="script.js" defer></script>
</body>
</html>
