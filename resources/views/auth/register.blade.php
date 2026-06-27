<x-guest-layout>
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="inline-flex lg:hidden items-center text-xl font-bold text-indigo-600 mb-5">
                <i class="ti ti-bolt mr-1"></i>DigitalStore
            </a>
            <span class="inline-flex items-center gap-1 text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-1 rounded-full">
                <i class="ti ti-user-plus"></i> Akun baru
            </span>
            <h1 class="text-2xl font-bold text-gray-800 mt-3">Buat akun DigitalStore</h1>
            <p class="text-sm text-gray-500 mt-1">Simpan pesanan dan unduh produk digital dengan mudah.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" value="Nama" />
                <x-text-input id="name" class="block mt-2 w-full rounded-xl border-gray-200 px-4 py-2 text-base focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="block mt-2 w-full rounded-xl border-gray-200 px-4 py-2 text-base focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" value="Password" />

                <x-text-input id="password" class="block mt-2 w-full rounded-xl border-gray-200 px-4 py-2 text-base focus:border-indigo-500 focus:ring-indigo-500"
                                type="password"
                                name="password"
                                required autocomplete="new-password"
                                placeholder="Minimal 8 karakter" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" value="Konfirmasi Password" />

                <x-text-input id="password_confirmation" class="block mt-2 w-full rounded-xl border-gray-200 px-4 py-2 text-base focus:border-indigo-500 focus:ring-indigo-500"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Ulangi password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                <i class="ti ti-user-plus mr-1"></i>Daftar
            </button>
        </form>

        <div class="mt-5 text-center text-sm text-gray-500">
            Sudah punya akun?
            <a class="font-medium text-indigo-600 hover:text-indigo-700" href="{{ route('login') }}">
                Masuk
            </a>
        </div>
    </div>
</x-guest-layout>
