<?php
// Ambil foto user untuk navbar
$navbarPhotoPath = null;

if (isset($_SESSION['user_id']) && isset($conn)) {
    $uid = $_SESSION['user_id'];

    $stmtNav = $conn->prepare("SELECT photo FROM users WHERE id = ?");
    $stmtNav->bind_param("i", $uid);
    $stmtNav->execute();
    $resultNav = $stmtNav->get_result();
    if ($rowNav = $resultNav->fetch_assoc()) {
        if (!empty($rowNav['photo'])) {
            // Sama seperti di profile.php: simpan path relatif (uploads/...)
            $navbarPhotoPath = htmlspecialchars($rowNav['photo']);
        }
    }
    $stmtNav->close();
}
?>
<!-- Navbar -->
    <header class="fixed top-0 left-0 z-50 w-full bg-white shadow-md">
      <div
        class="flex items-center justify-between h-[70px] px-4 md:px-8 lg:px-16"
      >
        <div class="flex items-center">
        <a href="index.php">  
            <img 
            src="img/logo1.jpg"
            alt="ThreeKost Logo"
            class="h-12 md:h-16 w-auto object-contain"
          />
          </a>
        </div>

        <!-- Menu desktop -->
        <nav class="hidden md:flex space-x-6 text-gray-900 font-medium">
          <a href="#about-us" class="hover:text-blue-600 transition">About Us</a>
          <a href="kost_list.php" class="hover:text-blue-600 transition">Kosts</a>
          <a href="#" class="hover:text-blue-600 transition"
            >Download Mobile App</a
          >
        </nav>

        <!-- Kanan -->
        <div class="flex items-center gap-3">
          <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- muncul kalo blm login -->
            <button
              type="button"
              class="hidden sm:inline-flex items-center rounded-full bg-[#3351d3] px-4 py-2 text-sm font-medium text-white hover:bg-[#4767e5] transition"
            >
              Become A Host
            </button>
          <?php endif; ?>

          <!-- Burger hanya di mobile/tablet -->
          <i class="fas fa-bars text-2xl cursor-pointer md:hidden"></i>

          <!-- Icon user -->
<?php if (isset($_SESSION['user_id'])): ?>
  <!-- Sudah login: ke profile -->
  <a href="profile.php" class="hidden md:inline-flex items-center justify-center">
    <?php if (!empty($navbarPhotoPath)): ?>
      <img
        src="<?php echo $navbarPhotoPath; ?>"
        alt="Profile photo"
        class="w-8 h-8 rounded-full object-cover border border-gray-300"
      />
    <?php else: ?>
      <!-- Kalau belum punya foto, tampilkan icon default seperti biasa -->
      <i class="fas fa-user-circle text-2xl cursor-pointer"></i>
    <?php endif; ?>
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

      <!-- MOBILE MENU -->
<div id="mobileMenu"
     class="fixed inset-0 bg-black/40 z-40 hidden md:hidden">

    <div class="absolute right-0 top-0 h-full w-64 bg-white shadow-xl p-6 flex flex-col gap-6">

        <!-- Tombol close -->
        <button id="closeMobileMenu" class="text-2xl text-gray-700 self-end">
            &times;
        </button>

        <!-- Menu -->
        <a href="index.php" class="text-gray-800 font-medium">Home</a>
        <a href="#about-us" class="text-gray-800 font-medium">About Us</a>
        <a href="kost_list.php" class="text-gray-800 font-medium">Kosts</a>
        <a href="#" class="text-gray-800 font-medium">Download App</a>

        <div class="h-px bg-gray-300"></div>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Belum login -->
            <button 
                id="openSignupMobile2"
                class="w-full bg-blue-600 text-white py-2 rounded-lg mt-2">
                Login / Signup
            </button>
        <?php else: ?>
            <!-- Sudah login -->
            <a href="profile.php" class="flex items-center gap-3 mt-2">
                <?php if (!empty($navbarPhotoPath)): ?>
                    <img src="<?= $navbarPhotoPath ?>" class="w-10 h-10 rounded-full border" />
                <?php else: ?>
                    <i class="fas fa-user-circle text-3xl"></i>
                <?php endif; ?>
                <span>My Profile</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const burger = document.querySelector(".fa-bars");
    const mobileMenu = document.getElementById("mobileMenu");
    const closeMenu = document.getElementById("closeMobileMenu");

    if (burger) {
        burger.addEventListener("click", () => {
            mobileMenu.classList.remove("hidden");
        });
    }

    if (closeMenu) {
        closeMenu.addEventListener("click", () => {
            mobileMenu.classList.add("hidden");
        });
    }

    // Klik luar area -> tutup
    mobileMenu.addEventListener("click", (e) => {
        if (e.target === mobileMenu) {
            mobileMenu.classList.add("hidden");
        }
    });

    // Mobile signup button -> buka modal signup
    const signupBtn = document.getElementById("openSignupMobile2");
    if (signupBtn) {
        signupBtn.addEventListener("click", () => {
            mobileMenu.classList.add("hidden");
            document.getElementById("signupModal").classList.remove("hidden");
            document.getElementById("signupModal").classList.add("flex");
        });
    }
});
</script>


    </header>