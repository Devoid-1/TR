<?php
require '../../config.php';

$adminName = $_SESSION['user_name'] ?? 'Admin';
?>
<main class="flex-1 bg-gray-50">
  <div class="max-w-6xl mx-auto px-8 py-10">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-semibold text-gray-900">
          Hello, <?php echo htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'); ?>!
        </h1>
        <p class="mt-1 text-sm text-gray-500">
          Here’s what’s happening in your kost this month
        </p>
      </div>

      <button
        type="button"
        class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:border-gray-300 hover:bg-gray-50"
      >
        This Month
        <svg
          class="h-4 w-4"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="1.8"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M6 9l6 6 6-6" />
        </svg>
      </button>
    </div>

    <!-- Main grid -->
    <div class="mt-10 grid grid-cols-1 xl:grid-cols-3 gap-6">
      <!-- Card: Total Revenue -->
      <div
        class="rounded-3xl bg-[#316BFF] text-white p-6 shadow-md relative overflow-hidden"
      >
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-white/80">Total Revenue</p>
            <p class="mt-6 text-3xl font-semibold">Rp. 50,200,000</p>

            <div class="mt-8 space-y-1">
              <div
                class="inline-flex items-center gap-2 rounded-full bg-black/30 px-3 py-1 text-xs font-medium"
              >
                <span
                  class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-black/40"
                >
                  <svg
                    class="h-3 w-3"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M12 5v14" />
                    <path d="M5 12l7-7 7 7" />
                  </svg>
                </span>
                <span>+ 5.6%</span>
              </div>
              <p class="text-[11px] text-white/80">
                Compared to last month
              </p>
            </div>
          </div>

          <button
            type="button"
            class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center shadow-inner"
          >
            <svg
              class="h-4 w-4"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M7 17L17 7" />
              <path d="M8 7H17V16" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Card: Active Tenants -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col justify-between"
      >
        <div class="flex items-start justify-between">
          <p class="text-sm font-medium text-gray-500">Active Tenants</p>
          <button
            type="button"
            class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center"
          >
            <svg
              class="h-4 w-4 text-gray-500"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M7 17L17 7" />
              <path d="M8 7H17V16" />
            </svg>
          </button>
        </div>

        <div class="mt-6">
          <p class="text-3xl font-semibold text-gray-900">35</p>
          <div
            class="mt-4 inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-500"
          >
            <span>+ 12.4%</span>
          </div>
          <p class="mt-1 text-[11px] text-gray-400">from last month</p>
        </div>
      </div>

      <!-- Card: Revenue Chart -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col xl:row-span-2"
      >
        <div class="flex items-start justify-between">
          <div>
            <h2 class="text-lg font-semibold text-gray-900">Revenue</h2>
            <p class="mt-1 text-[11px] text-gray-400">
              This month vs last
            </p>
          </div>
          <button
            type="button"
            class="h-10 w-10 rounded-full bg-gradient-to-br from-black to-gray-700 flex items-center justify-center text-white shadow"
          >
            <svg
              class="h-4 w-4"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M7 17L17 7" />
              <path d="M8 7H17V16" />
            </svg>
          </button>
        </div>

        <div class="mt-8 flex flex-1 gap-6">
          <!-- Y Axis -->
          <div
            class="flex flex-col justify-between text-[10px] text-gray-400 h-40"
          >
            <span>Rp. 38.000.000</span>
            <span>Rp. 20.000.000</span>
            <span>Rp. 18.000.000</span>
            <span>Rp. 15.000.000</span>
            <span>Rp. 9.500.000</span>
            <span>Rp. 7.000.000</span>
            <span>Rp. 5.000.000</span>
            <span>Rp. 2.500.000</span>
          </div>

          <!-- Bars -->
          <div class="flex-1 flex items-end gap-4 h-40">
            <div class="flex flex-col items-center flex-1 gap-2">
              <div class="w-full rounded-t-2xl bg-[#316BFF] h-20"></div>
              <span class="text-[10px] text-gray-400">21 Dec</span>
            </div>
            <div class="flex flex-col items-center flex-1 gap-2">
              <div class="w-full rounded-t-2xl bg-[#316BFF] h-24"></div>
              <span class="text-[10px] text-gray-400">22 Dec</span>
            </div>
            <div class="flex flex-col items-center flex-1 gap-2">
              <div class="w-full rounded-t-2xl bg-[#316BFF] h-28"></div>
              <span class="text-[10px] text-gray-400">23 Dec</span>
            </div>
            <div class="flex flex-col items-center flex-1 gap-2">
              <div class="w-full rounded-t-2xl bg-[#316BFF] h-32"></div>
              <span class="text-[10px] text-gray-400">24 Dec</span>
            </div>
            <div class="flex flex-col items-center flex-1 gap-2">
              <div class="w-full rounded-t-2xl bg-[#316BFF] h-36"></div>
              <span class="text-[10px] text-gray-400">25 Dec</span>
            </div>
            <div class="flex flex-col items-center flex-1 gap-2">
              <div class="w-full rounded-t-2xl bg-[#316BFF] h-28"></div>
              <span class="text-[10px] text-gray-400">26 Dec</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card: Total Rooms -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col justify-between"
      >
        <div class="flex items-start justify-between">
          <p class="text-sm font-medium text-gray-500">Total Rooms</p>
          <button
            type="button"
            class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center"
          >
            <svg
              class="h-4 w-4 text-gray-500"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M7 17L17 7" />
              <path d="M8 7H17V16" />
            </svg>
          </button>
        </div>

        <div class="mt-6">
          <p class="text-3xl font-semibold text-gray-900">120</p>
          <div
            class="mt-4 inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700"
          >
            25 rooms vacant
          </div>
        </div>
      </div>

      <!-- Card: Unpaid Bills -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col justify-between"
      >
        <div class="flex items-start justify-between">
          <p class="text-sm font-medium text-gray-500">Unpaid Bills</p>
          <button
            type="button"
            class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center"
          >
            <svg
              class="h-4 w-4 text-gray-500"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M7 17L17 7" />
              <path d="M8 7H17V16" />
            </svg>
          </button>
        </div>

        <div class="mt-6">
          <p class="text-3xl font-semibold text-gray-900">10</p>
          <div
            class="mt-4 inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700"
          >
            -2 from last month
          </div>
        </div>
      </div>

      <!-- Card: Booking -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col justify-between"
      >
        <div class="flex items-start justify-between">
          <p class="text-sm font-medium text-gray-500">Booking</p>
          <button
            type="button"
            class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center"
          >
            <svg
              class="h-4 w-4 text-gray-500"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M7 17L17 7" />
              <path d="M8 7H17V16" />
            </svg>
          </button>
        </div>

        <div class="mt-6">
          <p class="text-3xl font-semibold text-gray-900">30 Book</p>
          <p class="mt-3 text-xs text-gray-500">
            <span class="font-medium">5</span> tenant
            <span class="text-red-500 font-medium">bills pending</span>
            confirmation
          </p>
        </div>
      </div>

      <!-- Card: Tenants -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col justify-between"
      >
        <div class="flex items-start justify-between">
          <p class="text-sm font-medium text-gray-500">Tenants</p>
          <div
            class="h-9 w-9 rounded-full bg-gray-100 flex items-center justify-center"
          >
            <svg
              class="h-4 w-4 text-gray-500"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Z"
              />
              <path
                d="M4 20c0-2.21 2.686-4 6-4s6 1.79 6 4"
              />
            </svg>
          </div>
        </div>

        <div class="mt-6">
          <p class="text-3xl font-semibold text-gray-900">90</p>
          <p class="mt-3 text-xs text-gray-500">
            <span class="font-medium">10</span> tenant
            <span class="text-red-500 font-medium">messages need</span>
            attention
          </p>
        </div>
      </div>

      <!-- Card: Category -->
      <div
        class="rounded-3xl bg-white p-6 shadow-sm border border-gray-100 flex flex-col justify-between"
      >
        <div class="flex items-start justify-between">
          <h2 class="text-lg font-semibold text-gray-900">Category</h2>
          <button
            type="button"
            class="h-10 w-10 rounded-full bg-gradient-to-br from-black to-gray-700 flex items-center justify-center text-white shadow"
          >
            <svg
              class="h-4 w-4"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M7 17L17 7" />
              <path d="M8 7H17V16" />
            </svg>
          </button>
        </div>

        <div class="mt-6 space-y-6">
          <div class="flex justify-between text-center">
            <div class="flex flex-col items-center gap-2">
              <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-900"
              >
                100+
              </div>
              <p class="text-[11px] text-gray-400">Total Rooms</p>
            </div>
            <div class="flex flex-col items-center gap-2">
              <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-900"
              >
                20+
              </div>
              <p class="text-[11px] text-gray-400">Available Rooms</p>
            </div>
            <div class="flex flex-col items-center gap-2">
              <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-900"
              >
                50jt
              </div>
              <p class="text-[11px] text-gray-400">Room Revenue</p>
            </div>
          </div>

          <div class="flex justify-between text-center">
            <div class="flex flex-col items-center gap-2">
              <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-900"
              >
                10+
              </div>
              <p class="text-[11px] text-gray-400">Occupied Rooms</p>
            </div>
            <div class="flex flex-col items-center gap-2">
              <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-900"
              >
                3
              </div>
              <p class="text-[11px] text-gray-400">Under Maintenance</p>
            </div>
            <div class="flex flex-col items-center gap-2">
              <div
                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-900"
              >
                +3.8%
              </div>
              <p class="text-[11px] text-gray-400">Monthly Room Growth</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
