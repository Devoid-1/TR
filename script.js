document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("signupModal");
  const html = document.documentElement;
  const themeToggleBtn = document.getElementById("themeToggle");
  const themeToggleIcon = document.getElementById("themeToggleIcon");
  const themeToggleText = document.getElementById("themeToggleText");

  const openBtn = document.getElementById("openSignup");
  const openBtnMobile = document.getElementById("openSignupMobile");
  const closeBtn = document.getElementById("closeSignup");

  // Elemen untuk 2 step form
  const signupForm = document.getElementById("signupForm");
  const step1 = document.getElementById("signupStep1");
  const step2 = document.getElementById("signupStep2");
  const signupTitle = document.getElementById("signupTitle");
  const btnNextStep1 = document.getElementById("btnNextStep1");
  const btnBackStep2 = document.getElementById("btnBackStep2");

  const passwordInput = document.getElementById("passwordField");
  const confirmPasswordInput = document.getElementById("confirmPasswordField");
  const togglePassword = document.getElementById("togglePassword");
  const toggleConfirmPassword = document.getElementById(
    "toggleConfirmPassword"
  );

  // =============== THEME ===============
  function getInitialTheme() {
    // 1. Cek di localStorage dulu
    const stored = localStorage.getItem("theme");
    if (stored === "dark" || stored === "light") {
      return stored;
    }

    // 2. Kalau belum ada, ikuti preferensi sistem
    if (
      window.matchMedia &&
      window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
      return "dark";
    }

    // 3. Default: light
    return "light";
  }

  // >>> INI YANG KEMARIN HILANG <<<
  function applyTheme(theme) {
    if (!html) return;

    const isDark = theme === "dark";

    if (isDark) {
      html.classList.add("dark");
    } else {
      html.classList.remove("dark");
    }

    // Optional: update icon & text jika ada
    if (themeToggleIcon) {
      themeToggleIcon.classList.toggle("fa-sun", isDark);
      themeToggleIcon.classList.toggle("fa-moon", !isDark);
    }

    if (themeToggleText) {
      themeToggleText.textContent = isDark ? "Dark mode" : "Light mode";
    }
  }

  function initTheme() {
    const initialTheme = getInitialTheme();
    applyTheme(initialTheme);
  }

  function toggleTheme() {
    const isDark = html.classList.contains("dark");
    const newTheme = isDark ? "light" : "dark";
    applyTheme(newTheme);
    localStorage.setItem("theme", newTheme);
  }

  // Jalankan di awal (supaya saat halaman load langsung sesuai preferensi)
  initTheme();

  // Event klik toggle
  if (themeToggleBtn) {
    themeToggleBtn.addEventListener("click", toggleTheme);
  }

  // Helper: tampilkan step 1
  function showStep1() {
    if (step1) step1.classList.remove("hidden");
    if (step2) step2.classList.add("hidden");
    if (signupTitle) signupTitle.textContent = "Enter your details";
  }

  // Helper: tampilkan step 2
  function showStep2() {
    if (step1) step1.classList.add("hidden");
    if (step2) step2.classList.remove("hidden");
    if (signupTitle) signupTitle.textContent = "Create password";
    if (passwordInput) passwordInput.focus();
  }

  function openModal() {
    if (!modal) return;
    modal.classList.remove("hidden");
    modal.classList.add("flex"); // flex untuk center modal

    document.body.classList.add("overflow-hidden");

    // reset form ke step 1 setiap kali modal dibuka
    if (signupForm) signupForm.reset();
    showStep1();
  }

  function closeModal() {
    if (!modal) return;
    modal.classList.add("hidden");
    modal.classList.remove("flex");

    document.body.classList.remove("overflow-hidden");

    // reset juga ketika ditutup
    if (signupForm) signupForm.reset();
    showStep1();
  }

  // Buka modal
  if (openBtn) {
    openBtn.addEventListener("click", openModal);
  }

  if (openBtnMobile) {
    openBtnMobile.addEventListener("click", openModal);
  }

  // Tutup modal
  if (closeBtn) {
    closeBtn.addEventListener("click", closeModal);
  }

  // klik area gelap di luar card untuk menutup modal
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        closeModal();
      }
    });
  }

  // ====== LOGIC STEP 1 → STEP 2 ======
  if (btnNextStep1 && signupForm) {
    btnNextStep1.addEventListener("click", function () {
      const fullNameInput = signupForm.querySelector("input[name='full_name']");
      const emailInput = signupForm.querySelector("input[name='email']");

      const fullName = fullNameInput ? fullNameInput.value.trim() : "";
      const email = emailInput ? emailInput.value.trim() : "";

      if (!fullName || !email) {
        alert("Full name dan email wajib diisi.");
        return;
      }

      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert("Format email tidak valid.");
        return;
      }

      // Kalau lolos, pindah ke step 2
      showStep2();
    });
  }

  // Tombol Back di step 2
  if (btnBackStep2) {
    btnBackStep2.addEventListener("click", function () {
      showStep1();
    });
  }

  // ====== TOGGLE SHOW/HIDE PASSWORD ======
  function setupToggle(inputEl, btnEl) {
    if (!inputEl || !btnEl) return;

    btnEl.addEventListener("click", function () {
      const currentType = inputEl.getAttribute("type");
      inputEl.setAttribute(
        "type",
        currentType === "password" ? "text" : "password"
      );

      const icon = btnEl.querySelector("i");
      if (icon) {
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
      }
    });
  }

  setupToggle(passwordInput, togglePassword);
  setupToggle(confirmPasswordInput, toggleConfirmPassword);

  // ====== VALIDASI PASSWORD SAAT SUBMIT ======
  if (signupForm && passwordInput && confirmPasswordInput) {
    signupForm.addEventListener("submit", function (e) {
      const password = passwordInput.value;
      const confirm = confirmPasswordInput.value;

      if (password !== confirm) {
        e.preventDefault();
        alert("Password dan konfirmasi password tidak sama.");
        return;
      }

      if (password.length < 8) {
        e.preventDefault();
        alert("Password minimal 8 karakter.");
        return;
      }

      if (!/[A-Z]/.test(password)) {
        e.preventDefault();
        alert("Password harus mengandung minimal 1 huruf kapital.");
        return;
      }

      if (!/[\d\W]/.test(password)) {
        e.preventDefault();
        alert("Password harus mengandung minimal 1 angka atau simbol.");
        return;
      }

      const emailInput = signupForm.querySelector("input[name='email']");
      if (
        emailInput &&
        emailInput.value &&
        password.toLowerCase().includes(emailInput.value.toLowerCase())
      ) {
        e.preventDefault();
        alert("Password tidak boleh mengandung alamat email.");
        return;
      }
    });
  }

  // ===========================
  // WELCOME POPUP SETELAH SIGNUP/LOGIN
  // ===========================
  const welcomeModal = document.getElementById("welcomeModal");
  const welcomeNameEl = document.getElementById("welcomeName");
  const welcomeNextBtn = document.getElementById("welcomeNextBtn");

  function openWelcomeModal() {
    if (!welcomeModal) return;
    welcomeModal.classList.remove("hidden");
    welcomeModal.classList.add("flex");
    document.body.classList.add("overflow-hidden");
  }

  function closeWelcomeModal() {
    if (!welcomeModal) return;
    welcomeModal.classList.add("hidden");
    welcomeModal.classList.remove("flex");
    document.body.classList.remove("overflow-hidden");
  }

  // SHOW_WELCOME_MODAL & WELCOME_NAME dikirim dari PHP di <head>
  if (window.SHOW_WELCOME_MODAL && welcomeModal) {
    if (welcomeNameEl && window.WELCOME_NAME) {
      welcomeNameEl.textContent = window.WELCOME_NAME;
    }
    openWelcomeModal();
  }

  if (welcomeNextBtn) {
    welcomeNextBtn.addEventListener("click", function () {
      closeWelcomeModal();
    });
  }

  if (welcomeModal) {
    welcomeModal.addEventListener("click", function (e) {
      if (e.target === welcomeModal) {
        closeWelcomeModal();
      }
    });
  }

  // ===========================
  // LOGIN MODAL
  // ===========================
  const loginModal = document.getElementById("loginModal");
  const closeLogin = document.getElementById("closeLogin");
  const openLoginFromSignup = document.getElementById("openLoginFromSignup");

  const phoneLoginSection = document.getElementById("phoneLoginSection");
  const emailLoginSection = document.getElementById("emailLoginSection");
  const linkToEmailLogin = document.getElementById("linkToEmailLogin");
  const linkToPhoneLogin = document.getElementById("linkToPhoneLogin");

  const loginErrorBox = document.getElementById("loginErrorBox");
  const loginErrorText = document.getElementById("loginErrorText");

  function showPhoneLogin() {
    if (phoneLoginSection) phoneLoginSection.classList.remove("hidden");
    if (emailLoginSection) emailLoginSection.classList.add("hidden");
  }

  function showEmailLogin() {
    if (phoneLoginSection) phoneLoginSection.classList.add("hidden");
    if (emailLoginSection) emailLoginSection.classList.remove("hidden");
  }

  function openLoginModal() {
    if (!loginModal) return;
    loginModal.classList.remove("hidden");
    loginModal.classList.add("flex");
    document.body.classList.add("overflow-hidden");
    showPhoneLogin(); // default view
  }

  function closeLoginModal() {
    if (!loginModal) return;
    loginModal.classList.add("hidden");
    loginModal.classList.remove("flex");
    document.body.classList.remove("overflow-hidden");
  }

  // klik "Log in" di popup Sign Up
  if (openLoginFromSignup) {
    openLoginFromSignup.addEventListener("click", function () {
      closeModal();
      openLoginModal();
    });
  }

  // close button
  if (closeLogin) {
    closeLogin.addEventListener("click", closeLoginModal);
  }

  // klik overlay di luar card
  if (loginModal) {
    loginModal.addEventListener("click", function (e) {
      if (e.target === loginModal) {
        closeLoginModal();
      }
    });
  }

  // switch ke email login
  if (linkToEmailLogin) {
    linkToEmailLogin.addEventListener("click", function () {
      showEmailLogin();
    });
  }

  // switch balik ke phone login
  if (linkToPhoneLogin) {
    linkToPhoneLogin.addEventListener("click", function () {
      showPhoneLogin();
    });
  }

  // Auto-buka login modal jika ada error dari server
  if (window.SHOW_LOGIN_MODAL && loginModal) {
    if (window.LOGIN_ERROR && loginErrorBox && loginErrorText) {
      loginErrorText.textContent = window.LOGIN_ERROR;
      loginErrorBox.classList.remove("hidden");
    }
    openLoginModal();
  }

  // tutup dengan tombol ESC (tutup semua modal kalau ada)
  window.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      closeModal();
      closeWelcomeModal();
      closeLoginModal();
    }
  });

  // ===========================
  // PAYMENT PAGE - booking.php
  // ===========================
  const mbBox = document.getElementById("mbanking-box");
  const mbCollapsed = document.getElementById("mbanking-collapsed");
  const mbExpanded = document.getElementById("mbanking-expanded");

  const ewBox = document.getElementById("ewallet-box");
  const ewCollapsed = document.getElementById("ewallet-collapsed");
  const ewExpanded = document.getElementById("ewallet-expanded");

  const paymentModal = document.getElementById("payment-modal");
  const paymentModalClose = document.getElementById("payment-modal-close");
  const modalMethod = document.getElementById("modal-method");
  const paymentMethodInput = document.getElementById("payment-method-input");
  const paymentCatInput = document.getElementById("payment-category-input");

  // helper buka/tutup modal
  function openPaymentModal() {
    if (!paymentModal) return;
    paymentModal.classList.remove("hidden");
    paymentModal.classList.add("flex");
    document.body.classList.add("overflow-hidden");
  }

  function closePaymentModal() {
    if (!paymentModal) return;
    paymentModal.classList.add("hidden");
    paymentModal.classList.remove("flex");
    document.body.classList.remove("overflow-hidden");
  }

  // toggle expand / collapse M-Banking ketika box diklik (bukan logo)
  if (mbBox && mbCollapsed && mbExpanded) {
    mbBox.addEventListener("click", function (e) {
      if (e.target.closest("[data-payment-method]")) return; // jangan ikut toggle

      const isExpanded = !mbExpanded.classList.contains("hidden");
      if (isExpanded) {
        // kembali mengecil
        mbExpanded.classList.add("hidden");
        mbCollapsed.classList.remove("hidden");
      } else {
        // membesar
        mbCollapsed.classList.add("hidden");
        mbExpanded.classList.remove("hidden");
      }
    });
  }

  // toggle expand / collapse E-Wallet
  if (ewBox && ewCollapsed && ewExpanded) {
    ewBox.addEventListener("click", function (e) {
      if (e.target.closest("[data-payment-method]")) return;

      const isExpanded = !ewExpanded.classList.contains("hidden");
      if (isExpanded) {
        ewExpanded.classList.add("hidden");
        ewCollapsed.classList.remove("hidden");
      } else {
        ewCollapsed.classList.add("hidden");
        ewExpanded.classList.remove("hidden");
      }
    });
  }

  // buka modal ketika user pilih salah satu metode
  if (paymentModal && modalMethod) {
    const methodButtons = document.querySelectorAll("[data-payment-method]");

    methodButtons.forEach((btn) => {
      btn.addEventListener("click", function (event) {
        event.stopPropagation(); // biar tidak mentrigger klik box

        const method = btn.dataset.paymentMethod || "";
        const category = btn.dataset.paymentCategory || "";

        modalMethod.textContent =
          category && method ? category + " - " + method : method;

        if (paymentMethodInput) paymentMethodInput.value = method;
        if (paymentCatInput) paymentCatInput.value = category;

        openPaymentModal();
      });
    });

    // tombol Close
    if (paymentModalClose) {
      paymentModalClose.addEventListener("click", function () {
        closePaymentModal();
      });
    }

    // klik overlay di luar card
    paymentModal.addEventListener("click", function (e) {
      if (e.target === paymentModal) {
        closePaymentModal();
      }
    });
  }

  // ===========================
  // PROFILE PAGE – TABS + UNDERLINE ANIMASI
  // ===========================
  const tabLinks = document.querySelectorAll(".tab-link");
  const tabPanels = document.querySelectorAll(".tab-panel");
  const tabsNav = document.getElementById("tabs-nav");
  const underline = document.getElementById("tab-underline");

  // geser underline ke bawah tombol yang aktif
  function setUnderline(btn) {
    if (!tabsNav || !underline || !btn) return;
    underline.style.width = btn.offsetWidth + "px";
    underline.style.left = btn.offsetLeft + "px";
  }

  if (tabLinks.length && tabPanels.length) {
    // posisi awal di "Current Boarding"
    const initialBtn =
      document.querySelector(".tab-link[data-tab='current']") || tabLinks[0];
    if (initialBtn) {
      initialBtn.classList.add("text-[#343F7A]");
      initialBtn.classList.remove("text-gray-400");
      setUnderline(initialBtn);
    }

    tabLinks.forEach((btn) => {
      btn.addEventListener("click", function () {
        const target = btn.dataset.tab; // current | past | review
        const targetId = "tab-" + target;

        // update style tombol
        tabLinks.forEach((link) => {
          const isActive = link === btn;
          if (isActive) {
            link.classList.add("text-[#343F7A]");
            link.classList.remove("text-gray-400");
          } else {
            link.classList.remove("text-[#343F7A]");
            link.classList.add("text-gray-400");
          }
        });

        // tampilkan panel yang dipilih (dengan animasi dari Tailwind)
        tabPanels.forEach((panel) => {
          const isTarget = panel.id === targetId;
          if (isTarget) {
            panel.classList.remove(
              "opacity-0",
              "translate-y-3",
              "max-h-0",
              "pointer-events-none"
            );
            panel.classList.add(
              "opacity-100",
              "translate-y-0",
              "max-h-[2000px]",
              "pointer-events-auto"
            );
          } else {
            panel.classList.remove(
              "opacity-100",
              "translate-y-0",
              "max-h-[2000px]",
              "pointer-events-auto"
            );
            panel.classList.add(
              "opacity-0",
              "translate-y-3",
              "max-h-0",
              "pointer-events-none"
            );
          }
        });

        // pindahkan underline ke tab yg aktif
        setUnderline(btn);
      });
    });
  }
});
