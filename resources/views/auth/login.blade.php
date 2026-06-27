<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="inline-flex lg:hidden items-center text-xl font-bold text-indigo-600 mb-5">
                <i class="ti ti-bolt mr-1"></i>DigitalStore
            </a>
            <span class="inline-flex items-center gap-1 text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-1 rounded-full">
                <i class="ti ti-login-2"></i> Masuk akun
            </span>
            <h1 class="text-2xl font-bold text-gray-800 mt-3">Selamat datang kembali</h1>
            <p class="text-sm text-gray-500 mt-1">Lanjutkan belanja dan akses produk digital kamu.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="block mt-2 w-full rounded-xl border-gray-200 px-4 py-2 text-base focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Password" />

                <x-text-input id="password" class="block mt-2 w-full rounded-xl border-gray-200 px-4 py-2 text-base focus:border-indigo-500 focus:ring-indigo-500"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="Masukkan password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between gap-3">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-500">Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-700" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                <i class="ti ti-login-2 mr-1"></i>Masuk
            </button>
        </form>

        <div class="mt-5 text-center text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Daftar sekarang</a>
        </div>
    </div>
</x-guest-layout>
