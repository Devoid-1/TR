<!-- AUTH MODALS: SIGNUP, WELCOME, LOGIN -->

<!-- SIGN UP MODAL -->
<div
  id="signupModal"
  class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 px-4"
>
  <!-- card -->
  <div
    class="relative w-full max-w-4xl bg-white rounded-[32px] md:rounded-[40px] shadow-2xl overflow-hidden"
  >
    <!-- tombol close -->
    <button
      id="closeSignup"
      type="button"
      class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 text-2xl"
    >
      &times;
    </button>

    <div class="flex flex-col items-center py-10 md:py-14 px-6 md:px-16">
      <!-- logo + Sign Up -->
      <div class="w-full flex items-center justify-between mb-10">
        <img
          src="img/logo1.jpg"
          alt="ThreeKost Logo"
          class="h-14 md:h-16 w-auto object-contain"
        />
        <span class="text-2xl md:text-3xl font-semibold text-[#5B5FC7]">
          Sign Up
        </span>
      </div>

      <!-- judul (berubah antara step 1 & 2) -->
      <h2
        id="signupTitle"
        class="text-2xl md:text-4xl font-bold text-gray-900 text-left w-full mb-8"
      >
        Enter your details
      </h2>

      <!-- FORM 2 STEP -->
      <form
        id="signupForm"
        action="signup_process.php"
        method="POST"
        class="w-full"
      >
        <!-- STEP 1: NAMA & EMAIL -->
        <div id="signupStep1" class="w-full space-y-5">
          <!-- full name -->
          <div>
            <input
              type="text"
              name="full_name"
              required
              placeholder="Full name as per ID card"
              class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
            />
          </div>

          <!-- email -->
          <div>
            <input
              type="email"
              name="email"
              required
              placeholder="Email"
              class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
            />
          </div>

          <!-- login link -->
          <div class="pt-3 text-center text-xs md:text-sm text-gray-600">
            <span>Already have an account? </span>
            <button
              type="button"
              id="openLoginFromSignup"
              class="text-[#2F80FF] font-semibold underline"
            >
              Log in
            </button>
          </div>

          <!-- tombol Next -->
          <div class="pt-2">
            <button
              type="button"
              id="btnNextStep1"
              class="w-full md:w-52 mx-auto block rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
            >
              Next
            </button>
          </div>
        </div>
        <!-- END STEP 1 -->

        <!-- STEP 2: PASSWORD -->
        <div id="signupStep2" class="w-full space-y-5 hidden">
          <!-- field password -->
          <div class="relative">
            <input
              type="password"
              name="password"
              id="passwordField"
              required
              placeholder="New Password"
              class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 pr-11 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
            />
            <button
              type="button"
              id="togglePassword"
              class="absolute inset-y-0 right-3 flex items-center text-gray-400"
              tabindex="-1"
            >
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>

          <!-- field confirm password -->
          <div class="relative">
            <input
              type="password"
              name="confirm_password"
              id="confirmPasswordField"
              required
              placeholder="Confirm password"
              class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 pr-11 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
            />
            <button
              type="button"
              id="toggleConfirmPassword"
              class="absolute inset-y-0 right-3 flex items-center text-gray-400"
              tabindex="-1"
            >
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>

          <!-- aturan password -->
          <ul class="mt-2 space-y-1 text-xs md:text-sm text-gray-600">
            <li class="flex items-center">
              <span class="text-green-500 mr-2">✔</span>
              Your password cannot contain your email address
            </li>
            <li class="flex items-center">
              <span class="text-green-500 mr-2">✔</span>
              Must contain at least one uppercase letter
            </li>
            <li class="flex items-center">
              <span class="text-green-500 mr-2">✔</span>
              Use a minimum of 8 characters
            </li>
            <li class="flex items-center">
              <span class="text-green-500 mr-2">✔</span>
              Password must include at least one symbol or number
            </li>
          </ul>

          <!-- tombol Back + Create Password -->
          <div
            class="pt-4 flex flex-col md:flex-row items-center justify-between gap-3"
          >
            <button
              type="button"
              id="btnBackStep2"
              class="w-full md:w-auto text-sm md:text-base text-gray-500 hover:text-gray-700"
            >
              Back
            </button>

            <button
              type="submit"
              class="w-full md:w-56 rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
            >
              Create Password
            </button>
          </div>
        </div>
        <!-- END STEP 2 -->
      </form>
    </div>
  </div>
</div>
<!-- END SIGN UP MODAL -->

<!-- WELCOME MODAL -->
<div
  id="welcomeModal"
  class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/40 px-4"
>
  <div
    class="relative w-full max-w-4xl bg-white rounded-[32px] md:rounded-[40px] shadow-2xl overflow-hidden"
  >
    <div class="flex flex-col items-center py-10 md:py-14 px-6 md:px-16">
      <!-- Logo -->
      <div class="w-full flex justify-center mb-10">
        <img
          src="img/logo1.jpg"
          alt="ThreeKost Logo"
          class="h-20 md:h-24 w-auto object-contain"
        />
      </div>

      <!-- Teks -->
      <div class="w-full max-w-xl text-left mb-10">
        <h2 class="text-3xl md:text-4xl font-bold text-[#5B5FC7] mb-3">
          Halo,<br />
          <span id="welcomeName">User</span>
        </h2>
        <p class="text-sm md:text-base text-gray-500">
          Welcome to Threekost
        </p>
      </div>

      <!-- Tombol Next -->
      <button
        id="welcomeNextBtn"
        type="button"
        class="mt-2 w-40 md:w-48 rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 shadow-md hover:bg-[#4a4fb7] transition"
      >
        Next
      </button>
    </div>
  </div>
</div>
<!-- END WELCOME MODAL -->

<!-- LOGIN MODAL -->
<div
  id="loginModal"
  class="fixed inset-0 z-[65] hidden items-center justify-center bg-black/40 px-4"
>
  <div
    class="relative w-full max-w-4xl bg-white rounded-[32px] md:rounded-[40px] shadow-2xl overflow-hidden"
  >
    <!-- tombol close -->
    <button
      id="closeLogin"
      type="button"
      class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 text-2xl"
    >
      &times;
    </button>

    <div class="flex flex-col items-center py-10 md:py-14 px-6 md:px-16">
      <!-- logo + Log In -->
      <div class="w-full flex items-center justify-between mb-10">
        <img
          src="img/logo1.jpg"
          alt="ThreeKost Logo"
          class="h-14 md:h-16 w-auto object-contain"
        />
        <span class="text-2xl md:text-3xl font-semibold text-[#5B5FC7]">
          Log In
        </span>
      </div>

      <!-- Error box -->
      <div
        id="loginErrorBox"
        class="hidden w-full mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-xs md:text-sm"
      >
        <span id="loginErrorText"></span>
      </div>

      <!-- PHONE LOGIN SECTION -->
      <div id="phoneLoginSection" class="w-full max-w-2xl">
        <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-8">
          Enter your phone number
        </h2>

        <form
          action="login_process.php"
          method="POST"
          class="space-y-6"
        >
          <input type="hidden" name="login_type" value="phone" />

          <!-- phone input -->
          <div class="flex gap-3">
            <div
              class="flex items-center border border-[#B8C4FF] rounded-xl px-3 md:px-4"
            >
              <select
                name="country_code"
                class="bg-transparent text-sm md:text-base outline-none py-3.5 md:py-3 cursor-pointer"
              >
                <option value="+62">+62</option>
                <option value="+60">+60</option>
                <option value="+65">+65</option>
              </select>
            </div>

            <input
              type="text"
              name="phone"
              required
              placeholder="e.g. 81234567890"
              class="flex-1 border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
            />
          </div>

          <div class="text-center text-xs md:text-sm text-gray-600">
            <p>Already registered with your phone number?</p>
            <p class="mt-1">
              or
              <button
                type="button"
                id="linkToEmailLogin"
                class="text-[#2F80FF] font-semibold underline"
              >
                Log in with email
              </button>
            </p>
          </div>

          <!-- Divider -->
          <div class="flex items-center gap-3 text-xs md:text-sm text-gray-400">
            <span class="flex-1 h-px bg-[#B8C4FF]"></span>
            <span>Or Continue With</span>
            <span class="flex-1 h-px bg-[#B8C4FF]"></span>
          </div>

          <!-- Social icons (dummy) -->
          <div class="flex justify-center gap-6 text-2xl">
            <i class="fab fa-facebook text-[#1877F2]"></i>
            <i class="fab fa-google text-[#DB4437]"></i>
            <i class="fab fa-apple text-black"></i>
          </div>

          <!-- Next -->
          <div class="pt-2">
            <button
              type="submit"
              class="w-full md:w-52 mx-auto block rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
            >
              Next
            </button>
          </div>
        </form>
      </div>
      <!-- END PHONE LOGIN -->

      <!-- EMAIL LOGIN SECTION -->
      <div
        id="emailLoginSection"
        class="w-full max-w-2xl hidden"
      >
        <h2 class="text-2xl md:text-4xl font-bold text-gray-900 mb-8">
          Log in with email
        </h2>

        <form
          action="login_process.php"
          method="POST"
          class="space-y-6"
        >
          <input type="hidden" name="login_type" value="email" />

          <div>
            <input
              type="email"
              name="email_login"
              required
              placeholder="Email"
              class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
            />
          </div>

          <div>
            <input
              type="password"
              name="password_login"
              required
              placeholder="Password"
              class="w-full border border-[#B8C4FF] rounded-xl py-3.5 md:py-4 px-4 md:px-5 text-sm md:text-base outline-none focus:ring-2 focus:ring-[#5B5FC7] focus:border-transparent"
            />
          </div>

          <div class="text-center text-xs md:text-sm text-gray-600">
            <p>Prefer using phone number?</p>
            <p class="mt-1">
              <button
                type="button"
                id="linkToPhoneLogin"
                class="text-[#2F80FF] font-semibold underline"
              >
                Log in with phone number
              </button>
            </p>
          </div>

          <!-- Divider -->
          <div class="flex items-center gap-3 text-xs md:text-sm text-gray-400">
            <span class="flex-1 h-px bg-[#B8C4FF]"></span>
            <span>Or Continue With</span>
            <span class="flex-1 h-px bg-[#B8C4FF]"></span>
          </div>

          <!-- Social icons (dummy) -->
          <div class="flex justify-center gap-6 text-2xl">
            <i class="fab fa-facebook text-[#1877F2]"></i>
            <i class="fab fa-google text-[#DB4437]"></i>
            <i class="fab fa-apple text-black"></i>
          </div>

          <div class="pt-2">
            <button
              type="submit"
              class="w-full md:w-52 mx-auto block rounded-full bg-[#5B5FC7] text-white font-semibold text-base md:text-lg py-3 md:py-3.5 shadow-md hover:bg-[#4a4fb7] transition"
            >
              Next
            </button>
          </div>
        </form>
      </div>
      <!-- END EMAIL LOGIN -->
    </div>
  </div>
</div>
<!-- END LOGIN MODAL -->
