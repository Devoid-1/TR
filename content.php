<?php
// ===============================
//  Halaman Admin - Hosts & Kosts
// ===============================

// Pesan flash dari admin.php
$flashErrors  = $_SESSION['flash_errors'] ?? [];
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_errors'], $_SESSION['flash_success']);

// Ambil semua host (role = host)
$hosts = [];
$hostResult = $conn->query("
    SELECT id, full_name, email, phone, created_at
    FROM users
    WHERE role = 'host'
    ORDER BY created_at DESC
");
if ($hostResult && $hostResult->num_rows > 0) {
    while ($row = $hostResult->fetch_assoc()) {
        $hosts[] = $row;
    }
}

// Ambil semua kost + nama host
$kosts = [];
$kostResult = $conn->query("
    SELECT k.*, u.full_name AS host_name
    FROM kosts k
    LEFT JOIN users u ON k.user_id = u.id
    ORDER BY k.created_at DESC
");
if ($kostResult && $kostResult->num_rows > 0) {
    while ($row = $kostResult->fetch_assoc()) {
        $kosts[] = $row;
    }
}

// Statistik kartu
$totalHosts       = count($hosts);
$totalKost        = count($kosts);
$kostAvailable    = 0;
$kostNotAvailable = 0;

foreach ($kosts as $k) {
    if ((int)$k['room_available'] > 0) {
        $kostAvailable++;
    } else {
        $kostNotAvailable++;
    }
}
?>

<main class="flex-1 min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-10">
  <!-- Header -->
  <header class="mb-6">
    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Hello, Admin!</h2>
    <p class="text-sm sm:text-base text-gray-500 mt-1">
      You are currently viewing the <span class="font-semibold">Hosts & Kosts</span> section.
    </p>
  </header>

  <!-- Flash messages -->
  <?php if (!empty($flashSuccess)): ?>
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
      <?php echo htmlspecialchars($flashSuccess); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($flashErrors)): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
      <ul class="list-disc space-y-1 pl-5">
        <?php foreach ($flashErrors as $err): ?>
          <li><?php echo htmlspecialchars($err); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Stats cards -->
  <section class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
    <article class="rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm flex flex-col justify-between">
      <div class="text-sm font-semibold text-gray-900">Total Hosts</div>
      <div class="mt-2 flex items-end justify-between">
        <div class="text-3xl font-bold text-gray-900"><?php echo $totalHosts; ?></div>
        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-medium text-indigo-600">
          All registered hosts
        </span>
      </div>
    </article>

    <article class="rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm flex flex-col justify-between">
      <div class="text-sm font-semibold text-gray-900">Total Kost</div>
      <div class="mt-2 flex items-end justify-between">
        <div class="text-3xl font-bold text-gray-900"><?php echo $totalKost; ?></div>
        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-medium text-indigo-600">
          All kost in system
        </span>
      </div>
    </article>

    <article class="rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm flex flex-col justify-between">
      <div class="text-sm font-semibold text-gray-900">Kost with Available Rooms</div>
      <div class="mt-2 flex items-end justify-between">
        <div class="text-3xl font-bold text-gray-900"><?php echo $kostAvailable; ?></div>
        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
          room_available &gt; 0
        </span>
      </div>
    </article>

    <article class="rounded-2xl border border-gray-200 bg-white px-4 py-4 shadow-sm flex flex-col justify-between">
      <div class="text-sm font-semibold text-gray-900">Kost Full</div>
      <div class="mt-2 flex items-end justify-between">
        <div class="text-3xl font-bold text-gray-900"><?php echo $kostNotAvailable; ?></div>
        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-medium text-rose-700">
          No available room
        </span>
      </div>
    </article>
  </section>

  <!-- Top bar: search + buttons -->
  <section class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <!-- Search -->
    <div class="w-full max-w-md">
      <div class="relative">
        <input
          id="kostSearch"
          type="text"
          class="block w-full rounded-full border border-gray-200 bg-white py-2.5 pl-4 pr-10 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          placeholder="Search host or kost..."
        />
        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
          <!-- simple icon -->
          🔍
        </span>
      </div>
    </div>

    <!-- Buttons -->
    <div class="flex items-center justify-end gap-2">
      <button
        type="button"
        class="inline-flex items-center rounded-full bg-indigo-600 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
        data-open-modal="modalAddHost"
      >
        + Add Host
      </button>

      <button
        type="button"
        class="inline-flex items-center rounded-full bg-gray-900 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-black"
        data-open-modal="modalAddKost"
      >
        + Add Kost
      </button>
    </div>
  </section>

  <!-- Tabel Kost utama -->
  <section class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wide text-gray-600">
          <tr>
            <th class="px-4 py-3">Nama Kost</th>
            <th class="px-4 py-3">Host</th>
            <th class="px-4 py-3">Kota</th>
            <th class="px-4 py-3">Harga / Bulan</th>
            <th class="px-4 py-3">Kamar</th>
            <th class="px-4 py-3">Fasilitas</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-right">Tindakan</th>
          </tr>
        </thead>
        <tbody id="kostTableBody" class="divide-y divide-gray-100">
          <?php if (empty($kosts)): ?>
            <tr>
              <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500">
                Belum ada kost yang terdaftar.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($kosts as $k): ?>
              <?php
                $statusClass = 'bg-emerald-50 text-emerald-700';
                $statusText  = 'Active';

                if (!empty($k['delete_at'])) {
                    $statusClass = 'bg-gray-100 text-gray-700';
                    $statusText  = 'Inactive';
                } elseif ((int)$k['room_available'] <= 0) {
                    $statusClass = 'bg-rose-50 text-rose-700';
                    $statusText  = 'Full';
                }

                $fasilitas = [];
                if ($k['parking']) { $fasilitas[] = 'Parkir'; }
                if ($k['wifi'])    { $fasilitas[] = 'WiFi'; }
                if ($k['ac'])      { $fasilitas[] = 'AC'; }
                if ($k['kitchen']) { $fasilitas[] = 'Dapur'; }
              ?>
              <tr class="kost-row hover:bg-gray-50" data-search-text="<?php
                    echo strtolower(
                        $k['name'] . ' ' .
                        ($k['host_name'] ?? '') . ' ' .
                        $k['city'] . ' ' .
                        implode(' ', $fasilitas)
                    );
              ?>">
                <td class="px-4 py-3 align-top">
                  <div class="font-semibold text-gray-900">
                    <?php echo htmlspecialchars($k['name']); ?>
                  </div>
                  <div class="text-xs text-gray-400">
                    #KST<?php echo (int)$k['id']; ?>
                  </div>
                </td>
                <td class="px-4 py-3 align-top">
                  <div class="text-sm text-gray-900">
                    <?php echo htmlspecialchars($k['host_name'] ?? '-'); ?>
                  </div>
                  <div class="text-xs text-gray-500">
                    <?php echo htmlspecialchars($k['gender_type']); ?>
                  </div>
                </td>
                <td class="px-4 py-3 align-top">
                  <?php echo htmlspecialchars($k['city']); ?>
                </td>
                <td class="px-4 py-3 align-top">
                  Rp <?php echo number_format((int)$k['price_month'], 0, ',', '.'); ?>
                </td>
                <td class="px-4 py-3 align-top">
                  <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                    <?php echo (int)$k['room_available']; ?> /
                    <?php echo (int)$k['room_total']; ?> rooms
                  </span>
                </td>
                <td class="px-4 py-3 align-top text-xs text-gray-600">
                  <?php echo htmlspecialchars(implode(', ', $fasilitas)); ?>
                </td>
                <td class="px-4 py-3 align-top">
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold <?php echo $statusClass; ?>">
                    <?php echo $statusText; ?>
                  </span>
                </td>
                <td class="px-4 py-3 align-top text-right">
                  <form
                    method="POST"
                    action="admin.php"
                    onsubmit="return confirm('Yakin ingin menghapus kost ini?');"
                    class="inline-block"
                  >
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int)$k['id']; ?>">
                    <button
                      type="submit"
                      class="inline-flex items-center rounded-full bg-rose-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-rose-600"
                    >
                      Delete
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Tabel host ringkas di bawah -->
  <?php if (!empty($hosts)): ?>
    <section class="mt-6">
      <h3 class="mb-2 text-sm font-semibold text-gray-900">
        Latest Hosts
      </h3>
      <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full text-left text-sm text-gray-700">
          <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wide text-gray-600">
            <tr>
              <th class="px-4 py-3">Nama</th>
              <th class="px-4 py-3">Email</th>
              <th class="px-4 py-3">Telepon</th>
              <th class="px-4 py-3">Dibuat</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach (array_slice($hosts, 0, 5) as $h): ?>
              <tr>
                <td class="px-4 py-3"><?php echo htmlspecialchars($h['full_name']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($h['email']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($h['phone'] ?? '-'); ?></td>
                <td class="px-4 py-3 text-xs text-gray-500">
                  <?php echo htmlspecialchars($h['created_at']); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  <?php endif; ?>
</main>

<!-- MODAL: ADD HOST -->
<div
  id="modalAddHost"
  class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-900/40 p-4"
>
  <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl">
    <div class="mb-3 flex items-center justify-between">
      <h3 class="text-base font-semibold text-gray-900">Tambah Host Baru</h3>
      <button
        type="button"
        class="text-lg text-gray-400 hover:text-gray-700"
        data-close-modal
      >
        &times;
      </button>
    </div>

    <form method="POST" action="admin.php" class="space-y-3">
      <input type="hidden" name="action" value="add_host" />

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Nama Lengkap</label>
        <input
          type="text"
          name="full_name"
          required
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
        />
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Email</label>
        <input
          type="email"
          name="email"
          required
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
        />
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">No. Telepon</label>
        <input
          type="text"
          name="phone"
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
        />
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Password (untuk login host)</label>
        <input
          type="password"
          name="password"
          required
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
        />
      </div>

      <div class="mt-4 flex justify-end gap-2">
        <button
          type="button"
          class="rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100"
          data-close-modal
        >
          Batal
        </button>
        <button
          type="submit"
          class="rounded-full bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700"
        >
          Simpan Host
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: ADD KOST -->
<div
  id="modalAddKost"
  class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-900/40 p-4"
>
  <div class="w-full max-w-2xl rounded-2xl bg-white p-5 shadow-xl">
    <div class="mb-3 flex items-center justify-between">
      <h3 class="text-base font-semibold text-gray-900">Tambah Kost Baru</h3>
      <button
        type="button"
        class="text-lg text-gray-400 hover:text-gray-700"
        data-close-modal
      >
        &times;
      </button>
    </div>

    <form method="POST" action="admin.php" enctype="multipart/form-data" class="space-y-3">
      <input type="hidden" name="action" value="add_kost" />

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Host</label>
        <select
          name="host_id"
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          <?php echo empty($hosts) ? 'disabled' : ''; ?>
        >
          <option value="">-- Pilih Host --</option>
          <?php foreach ($hosts as $h): ?>
            <option value="<?php echo (int)$h['id']; ?>">
              <?php echo htmlspecialchars($h['full_name'] . ' - ' . $h['email']); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (empty($hosts)): ?>
          <p class="mt-1 text-[11px] text-rose-500">
            Tambah host terlebih dahulu sebelum membuat kost.
          </p>
        <?php endif; ?>
      </div>

      <div class="grid gap-3 sm:grid-cols-2">
        <div>
          <label class="mb-1 block text-xs font-semibold text-gray-700">Nama Kost</label>
          <input
            type="text"
            name="name"
            required
            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          />
        </div>

        <div>
          <label class="mb-1 block text-xs font-semibold text-gray-700">Kota</label>
          <input
            type="text"
            name="city"
            required
            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          />
        </div>
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Alamat Lengkap</label>
        <textarea
          name="address"
          rows="2"
          required
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
        ></textarea>
      </div>

      <div class="grid gap-3 sm:grid-cols-3">
        <div>
          <label class="mb-1 block text-xs font-semibold text-gray-700">Harga / Bulan</label>
          <input
            type="number"
            name="price_month"
            min="0"
            required
            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          />
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-gray-700">Total Kamar</label>
          <input
            type="number"
            name="room_total"
            min="0"
            required
            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          />
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-gray-700">Kamar Tersedia</label>
          <input
            type="number"
            name="room_available"
            min="0"
            required
            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          />
        </div>
      </div>

      <div class="grid gap-3 sm:grid-cols-2">
        <div>
          <label class="mb-1 block text-xs font-semibold text-gray-700">Tipe Kamar Mandi</label>
          <select
            name="bathroom_type"
            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          >
            <option value="private">Private</option>
            <option value="shared">Shared</option>
          </select>
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-gray-700">Tipe Gender</label>
          <select
            name="gender_type"
            class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          >
            <option value="mixed">Campur</option>
            <option value="male">Laki-laki</option>
            <option value="female">Perempuan</option>
          </select>
        </div>
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Fasilitas</label>
        <div class="grid grid-cols-2 gap-2 text-xs text-gray-700">
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="facilities[]" value="parking" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <span>Parkir</span>
          </label>
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="facilities[]" value="wifi" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <span>WiFi</span>
          </label>
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="facilities[]" value="ac" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <span>AC</span>
          </label>
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="facilities[]" value="kitchen" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
            <span>Dapur</span>
          </label>
        </div>
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Deskripsi</label>
        <textarea
          name="description"
          rows="3"
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
        ></textarea>
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Foto Utama</label>
        <input
          type="file"
          name="main_image"
          accept="image/*"
          class="block w-full text-xs text-gray-600 file:mr-3 file:rounded-full file:border-0 file:bg-gray-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-black"
        />
      </div>

      <div class="mt-4 flex justify-end gap-2">
        <button
          type="button"
          class="rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100"
          data-close-modal
        >
          Batal
        </button>
        <button
          type="submit"
          class="rounded-full bg-gray-900 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-black <?php echo empty($hosts) ? 'opacity-50 cursor-not-allowed' : ''; ?>"
          <?php echo empty($hosts) ? 'disabled' : ''; ?>
        >
          Simpan Kost
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // open modal
    document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = btn.getAttribute('data-open-modal');
        var modal = document.getElementById(id);
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }
      });
    });

    // close modal
    document.querySelectorAll('[data-close-modal]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var modal = btn.closest('.fixed');
        if (modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        }
      });
    });

    // klik backdrop untuk close
    document.querySelectorAll('#modalAddHost, #modalAddKost').forEach(function (backdrop) {
      backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) {
          backdrop.classList.add('hidden');
          backdrop.classList.remove('flex');
        }
      });
    });

    // search filter kost
    var searchInput = document.getElementById('kostSearch');
    if (searchInput) {
      searchInput.addEventListener('input', function () {
        var term = searchInput.value.toLowerCase();
        document.querySelectorAll('.kost-row').forEach(function (row) {
          var text = row.getAttribute('data-search-text') || row.textContent.toLowerCase();
          row.style.display = text.indexOf(term) !== -1 ? '' : 'none';
        });
      });
    }
  });
</script>
