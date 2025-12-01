<x-guest-layout>

    <!-- Compact Title -->
    <div class="text-center mb-4">
        <h1 class="text-2xl font-semibold text-textDark">Daftar Akun</h1>
        <p class="text-sm text-textMuted mt-1">Buat akun baru untuk mulai menggunakan WarungKu</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4 text-sm">
        @csrf

        <!-- Name -->
        <div class="flex flex-col gap-2">
            <label for="name" class="text-sm font-medium text-textDark">Nama Lengkap</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">person</span>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required autofocus
                    class="w-full rounded-md border border-gray-300 bg-softBg py-2 pl-10 pr-3 shadow-sm focus:border-primary focus:ring-primary/30"
                    placeholder="Nama lengkap"
                />
            </div>
            @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-2">
            <label for="email" class="text-sm font-medium text-textDark">Email</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">mail</span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required autocomplete="username"
                    class="w-full rounded-md border border-gray-300 bg-softBg py-2 pl-10 pr-3 shadow-sm focus:border-primary focus:ring-primary/30"
                    placeholder="Email"
                />
            </div>
            @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-2">
            <label for="password" class="text-sm font-medium text-textDark">Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">lock</span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required autocomplete="new-password"
                    class="w-full rounded-md border border-gray-300 bg-softBg py-2 pl-10 pr-10 shadow-sm focus:border-primary focus:ring-primary/30"
                    placeholder="Password"
                />
                <button type="button" onclick="togglePassword('password','togglePassIcon')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">
                    <span class="material-symbols-outlined" id="togglePassIcon">visibility_off</span>
                </button>
            </div>
            @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="flex flex-col gap-2">
            <label for="password_confirmation" class="text-sm font-medium text-textDark">Konfirmasi Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">lock</span>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required autocomplete="new-password"
                    class="w-full rounded-md border border-gray-300 bg-softBg py-2 pl-10 pr-10 shadow-sm focus:border-primary focus:ring-primary/30"
                    placeholder="Ulangi password"
                />
                <button type="button" onclick="togglePassword('password_confirmation','toggleConfIcon')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">
                    <span class="material-symbols-outlined" id="toggleConfIcon">visibility_off</span>
                </button>
            </div>
            @error('password_confirmation')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-primary hover:bg-primary2 text-white py-2 rounded-md font-medium transition">
            Daftar
        </button>

        <!-- Already have account -->
        <div class="text-center mt-2">
            <span class="text-sm text-textMuted">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="text-primary font-medium hover:underline ml-1">Masuk</a>
        </div>
    </form>

    <!-- Toggle Script (same as login) -->
    <script>
        function togglePassword(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;
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
