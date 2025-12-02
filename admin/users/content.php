<?php

$flashErrors  = $_SESSION['flash_errors'] ?? [];
$flashSuccess = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_errors'], $_SESSION['flash_success']);

$hosts = [];
$loggedRole   = $_SESSION['role'] ?? '';
$loggedUserId = $_SESSION['user_id'] ?? 0;

if ($loggedRole === 'admin') {
  $sql = "SELECT id, full_name, email, phone, created_at
          FROM users
          WHERE role = ?
          ORDER BY created_at DESC";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $roleValue);
    if ($stmt->execute()) {
      $res = $stmt->get_result();
      while ($row = $res->fetch_assoc()) {
        $hosts[] = $row;
      }
    }
    $stmt->close();
  }
} elseif ($loggedRole === 'host') {
  $sql = "SELECT id, full_name, email, phone,created_by, created_at
          FROM users
          WHERE role = ? AND created_by = ?
          ORDER BY created_at DESC";
  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("si", $roleValue, $loggedUserId);
    if ($stmt->execute()) {
      $res = $stmt->get_result();
      while ($row = $res->fetch_assoc()) {
        $hosts[] = $row;
      }
    }
    $stmt->close();
  }

  $kostNames = [];
  $sqlKost = "SELECT id, name FROM kosts WHERE user_id = ? ORDER BY created_at DESC";
  if ($stmtKost = $conn->prepare($sqlKost)) {
    $stmtKost->bind_param("i", $loggedUserId);
    if ($stmtKost->execute()) {
      $resKost = $stmtKost->get_result();
      while ($row = $resKost->fetch_assoc()) {
        $kostNames[] = $row;
      }
    }
    $stmtKost->close();
  }

}
?>

<main class="flex-1 min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-10">
  <header class="mb-6">
  <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Hello, <?php echo htmlspecialchars($welcomeName); ?>!(<?php echo $_SESSION['role'] ?>)</h2>
  <p class="text-sm sm:text-base text-gray-500 mt-1">
    You are currently viewing the <span class="font-semibold">Hosts & Kosts</span> section.
  </p>
  </header>

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

  <section class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
  <div class="w-full max-w-md">
    <div class="relative">
    <input
      id="kostSearch"
      type="text"
      class="block w-full rounded-full border border-gray-200 bg-white py-2.5 pl-4 pr-10 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
      placeholder="Search host or kost..."
    />
    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
      🔍
    </span>
    </div>
  </div>

  <div class="flex items-center justify-end gap-2">
    <button
    type="button"
    class="inline-flex items-center rounded-full bg-indigo-600 px-4 py-2 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
    data-open-modal="modalAddHost"
    >
    + Add User
    </button>
  </div>
  </section>

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

<div
  id="modalAddHost"
  class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-900/40 p-4"
>
  <div class="w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl">
  <div class="mb-3 flex items-center justify-between">
    <h3 class="text-base font-semibold text-gray-900">Tambah <?php echo htmlspecialchars($roleValue) ?> Baru</h3>
    <button
    type="button"
    class="text-lg text-gray-400 hover:text-gray-700"
    data-close-modal
    >
    &times;
    </button>
  </div>

  <form method="POST" action="index.php" class="space-y-3">
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
    <label class="mb-1 block text-xs font-semibold text-gray-700">Password (untuk login <?php echo htmlspecialchars($roleValue) ?>)</label>
    <input
      type="password"
      name="password"
      required
      class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
    />
    </div>

    <?php if (!empty($kostNames)): ?>
      <div>
        <label class="mb-1 block text-xs font-semibold text-gray-700">Pilih Kost</label>
        <select
          name="kost_id"
          class="block w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
          required
        >
          <option value="" disabled selected>-- Pilih Kost --</option>
          <?php foreach ($kostNames as $k): ?>
            <option value="<?php echo (int)$k['id']; ?>">
              <?php echo htmlspecialchars($k['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php else: ?>
      <div class="text-xs text-gray-500">
      </div>
    <?php endif; ?>

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

<script>
  document.addEventListener('DOMContentLoaded', function () {
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

  document.querySelectorAll('[data-close-modal]').forEach(function (btn) {
    btn.addEventListener('click', function () {
    var modal = btn.closest('.fixed');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
    });
  });

  document.querySelectorAll('#modalAddHost').forEach(function (backdrop) {
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
