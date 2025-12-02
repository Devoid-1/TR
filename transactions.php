<?php
require 'config.php';

// mastiin user sudah login & adalah host
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'host') {
    header("Location: index.php");
    exit;
}

$hostId   = (int)$_SESSION['user_id'];
$hostName = $_SESSION['user_name'] ?? 'Host';

// Ambil semua kost milik host ini
$kosts = [];
$stmt = $conn->prepare("SELECT * FROM kosts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $hostId);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kosts[] = $row;
    }
}
$stmt->close();

// Hitung statistik
$totalKost         = count($kosts);
$totalRooms        = 0;
$totalAvailable    = 0;
$totalOccupied     = 0;
$activeKost        = 0;
$inactiveOrDeleted = 0;

foreach ($kosts as $k) {
    $totalRooms     += (int)$k['room_total'];
    $totalAvailable += (int)$k['room_available'];
    $occupied        = max(0, (int)$k['room_total'] - (int)$k['room_available']);
    $totalOccupied  += $occupied;

    if (!empty($k['delete_at'])) {
        $inactiveOrDeleted++;
    } else {
        $activeKost++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Host Dashboard - ThreeKost</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome (kalau butuh icon) -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQ..."
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body class="bg-gray-100">
    <div class="min-h-screen flex">
      <!-- SIDEBAR (yang sama seperti admin, dari sidebar.php) -->
      <?php @include 'sidebar.php'; ?>

      <!-- CONTENT -->
      <main class="flex-1 min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-10">
        <!-- Header -->
        <header class="mb-6">
          <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
            Hello, <?php echo htmlspecialchars($hostName); ?>!
          </h2>
          <p class="mt-1 text-sm sm:text-base text-gray-500">
            You are currently viewing the <span class="font-semibold">overview of your kosts</span>.
          </p>
        </header>

        <!-- Stat Cards (mirip desain gambar) -->
        <section class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
          <article class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm">
            <div class="text-sm font-semibold text-gray-900">Total Kost</div>
            <div class="mt-2 flex items-end justify-between">
              <div class="text-3xl font-bold text-gray-900"><?php echo $totalKost; ?></div>
              <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-medium text-indigo-600">
                All your kost
              </span>
            </div>
          </article>

          <article class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm">
            <div class="text-sm font-semibold text-gray-900">Active Kost</div>
            <div class="mt-2 flex items-end justify-between">
              <div class="text-3xl font-bold text-gray-900"><?php echo $activeKost; ?></div>
              <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                Not deleted
              </span>
            </div>
          </article>

          <article class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm">
            <div class="text-sm font-semibold text-gray-900">Available Rooms</div>
            <div class="mt-2 flex items-end justify-between">
              <div class="text-3xl font-bold text-gray-900"><?php echo $totalAvailable; ?></div>
              <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-medium text-sky-700">
                Ready to book
              </span>
            </div>
          </article>

          <article class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm">
            <div class="text-sm font-semibold text-gray-900">Occupied Rooms</div>
            <div class="mt-2 flex items-end justify-between">
              <div class="text-3xl font-bold text-gray-900"><?php echo $totalOccupied; ?></div>
              <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-700">
                Estimated filled
              </span>
            </div>
          </article>
        </section>

        <!-- Top bar: search + tombol -->
        <section class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <!-- Search -->
          <div class="w-full max-w-md">
            <div class="relative">
              <input
                id="kostSearch"
                type="text"
                class="block w-full rounded-full border border-gray-200 bg-white py-2.5 pl-4 pr-10 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                placeholder="Search kost by name or city..."
              />
              <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                🔍
              </span>
            </div>
          </div>

          <!-- Buttons di kanan -->
          <div class="flex items-center justify-end gap-2">
            <button
              type="button"
              class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-sm text-gray-600 shadow-sm hover:bg-gray-100"
              title="Filter (coming soon)"
            >
              ⛃
            </button>

            <!-- Kalau sudah punya halaman tambah kost sendiri, arahkan ke sana -->
            <a
              href="kost_list.php"
              class="inline-flex items-center rounded-full bg-gray-900 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-black"
            >
              Lihat Daftar Kost
            </a>
          </div>
        </section>

        <!-- Tabel kost milik host -->
        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-gray-700">
              <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wide text-gray-600">
                <tr>
                  <th class="px-4 py-3">Kost ID</th>
                  <th class="px-4 py-3">Nama Kost</th>
                  <th class="px-4 py-3">Kota</th>
                  <th class="px-4 py-3">Harga / Bulan</th>
                  <th class="px-4 py-3">Kamar (Avail/Total)</th>
                  <th class="px-4 py-3">Tipe</th>
                  <th class="px-4 py-3">Status</th>
                </tr>
              </thead>
              <tbody id="hostKostTable" class="divide-y divide-gray-100">
                <?php if (empty($kosts)): ?>
                  <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">
                      Belum ada kost yang Anda daftarkan.
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($kosts as $k): ?>
                    <?php
                      $occupied    = max(0, (int)$k['room_total'] - (int)$k['room_available']);
                      $statusText  = 'Active';
                      $statusClass = 'bg-emerald-50 text-emerald-700';

                      if (!empty($k['delete_at'])) {
                          $statusText  = 'Inactive';
                          $statusClass = 'bg-gray-100 text-gray-700';
                      } elseif ((int)$k['room_available'] <= 0) {
                          $statusText  = 'Full';
                          $statusClass = 'bg-rose-50 text-rose-700';
                      }
                    ?>
                    <tr
                      class="host-kost-row hover:bg-gray-50"
                      data-search-text="<?php
                        echo strtolower(
                          $k['name'] . ' ' .
                          $k['city'] . ' ' .
                          $k['bathroom_type'] . ' ' .
                          $k['gender_type']
                        );
                      ?>"
                    >
                      <td class="px-4 py-3 align-top text-xs text-gray-500">
                        #KST<?php echo (int)$k['id']; ?>
                      </td>
                      <td class="px-4 py-3 align-top">
                        <div class="font-semibold text-gray-900">
                          <?php echo htmlspecialchars($k['name']); ?>
                        </div>
                        <div class="mt-0.5 text-xs text-gray-500">
                          <?php echo htmlspecialchars($k['address']); ?>
                        </div>
                      </td>
                      <td class="px-4 py-3 align-top">
                        <?php echo htmlspecialchars($k['city']); ?>
                      </td>
                      <td class="px-4 py-3 align-top">
                        Rp <?php echo number_format((int)$k['price_month'], 0, ',', '.'); ?>
                      </td>
                      <td class="px-4 py-3 align-top text-xs">
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                          <?php echo (int)$k['room_available']; ?> / <?php echo (int)$k['room_total']; ?> rooms
                        </span>
                        <div class="mt-0.5 text-[11px] text-gray-500">
                          Est. occupied: <?php echo $occupied; ?> kamar
                        </div>
                      </td>
                      <td class="px-4 py-3 align-top text-xs text-gray-600">
                        <div>K. mandi: <?php echo htmlspecialchars($k['bathroom_type']); ?></div>
                        <div>Gender: <?php echo htmlspecialchars($k['gender_type']); ?></div>
                      </td>
                      <td class="px-4 py-3 align-top">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold <?php echo $statusClass; ?>">
                          <?php echo $statusText; ?>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>

    <script>
      // simple search
      document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('kostSearch');
        if (searchInput) {
          searchInput.addEventListener('input', function () {
            var term = searchInput.value.toLowerCase();
            document.querySelectorAll('.host-kost-row').forEach(function (row) {
              var text = row.getAttribute('data-search-text') || row.textContent.toLowerCase();
              row.style.display = text.indexOf(term) !== -1 ? '' : 'none';
            });
          });
        }
      });
    </script>
  </body>
</html>
