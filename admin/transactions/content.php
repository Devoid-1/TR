
      <main class="flex-1 min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-10">
        <!-- Header -->
        <header class="mb-6">
          <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
            Hello, <?php echo htmlspecialchars($userName); ?>
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

<!-- Tabel Transaksi -->
<section class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden mt-8">
    <div class="px-4 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Transaksi Sewa</h3>
        <p class="mt-1 text-xs text-gray-500">Daftar pembayaran terkait kost Anda.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm text-gray-700">
            <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wide text-gray-600">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Nama User</th>
                    <th class="px-4 py-3">Nama Kost</th>
                    <th class="px-4 py-3">Penyewa (Fullname)</th>
                    <th class="px-4 py-3">Monthly Rent</th>
                    <th class="px-4 py-3">Created At</th>
                    <th class="px-4 py-3">Payment Method</th>
                    <th class="px-4 py-3">Payment Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                            Belum ada transaksi.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $b): ?>
                        <?php
                            $statusClass = 'bg-gray-100 text-gray-700';
                            if ($b['payment_status'] === 'paid') {
                                    $statusClass = 'bg-emerald-50 text-emerald-700';
                            } elseif ($b['payment_status'] === 'pending') {
                                    $statusClass = 'bg-amber-50 text-amber-700';
                            } elseif ($b['payment_status'] === 'failed') {
                                    $statusClass = 'bg-rose-50 text-rose-700';
                            }
                            $searchText = strtolower(
                                ($b['user_name'] ?? '') . ' ' .
                                ($b['kost_name'] ?? '') . ' ' .
                                ($b['full_name'] ?? '') . ' ' .
                                ($b['payment_status'] ?? '')
                            );
                        ?>
                        <tr class="hover:bg-gray-50 host-kost-row" data-search-text="<?php echo htmlspecialchars($searchText); ?>">
                            <td class="px-4 py-3 text-xs text-gray-500">#TRX<?php echo (int)$b['id']; ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($b['user_name']); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($b['kost_name']); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($b['full_name']); ?></td>
                            <td class="px-4 py-3">
                                Rp <?php echo number_format((int)$b['monthly_rent'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <?php echo htmlspecialchars($b['created_at']); ?>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <?php echo htmlspecialchars($b['payment_method']); ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($b['payment_status']); ?>
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