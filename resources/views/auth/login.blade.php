<x-guest-layout>

    <!-- Title -->
<div class="text-center mb-6">
    <h1 class="text-3xl font-bold text-textDark">Masuk</h1>
    <p class="text-textMuted mt-1">Akses dashboard & kelola warung Anda</p>
</div>


    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-green-600 text-sm font-semibold">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
        @csrf

        <!-- Email -->
        <div class="flex flex-col gap-2">
            <label for="email" class="text-sm font-semibold text-textDark">Email</label>

            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    mail
                </span>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required autofocus autocomplete="username"
                    class="w-full rounded-xl border border-gray-300 bg-softBg py-3 pl-12 pr-4 shadow-sm focus:border-primary focus:ring-primary"
                    placeholder="Masukkan email anda"
                />
            </div>

            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-2">
            <label for="password" class="text-sm font-semibold text-textDark">Password</label>

            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    lock
                </span>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required autocomplete="current-password"
                    class="w-full rounded-xl border border-gray-300 bg-softBg py-3 pl-12 pr-12 shadow-sm focus:border-primary focus:ring-primary"
                    placeholder="Masukkan password"
                />

                <button
                    type="button"
                    onclick="togglePassword()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"
                >
                    <span class="material-symbols-outlined" id="toggleIcon">
                        visibility_off
                    </span>
                </button>
            </div>

            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-1">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                />
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-primary hover:underline"
                >
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full bg-primary hover:bg-primary2 text-white py-3 rounded-xl font-semibold transition"
        >
            Masuk
        </button>

        <!-- Register Link -->
         <!-- Link Register -->
<div class="text-center mt-4">
    <span class="text-sm text-gray-600">Belum punya akun?</span>
    <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">
        Daftar sekarang
    </a>
</div>

    </form>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");

            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "visibility";
            } else {
                input.type = "password";
                icon.textContent = "visibility_off";
            }
        }
    </script>
</x-guest-layout>
