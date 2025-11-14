document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("signupModal");
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
  const confirmPasswordInput = document.getElementById(
    "confirmPasswordField"
  );
  const togglePassword = document.getElementById("togglePassword");
  const toggleConfirmPassword = document.getElementById(
    "toggleConfirmPassword"
  );

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
});
