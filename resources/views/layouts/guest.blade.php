<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Digital Store') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    @include('layouts.navigation')

    <main class="max-w-6xl mx-auto px-4 py-8 w-full flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center min-h-[calc(100vh-12rem)]">
            <section class="hidden lg:block">
                <a href="{{ route('products.index') }}" class="inline-flex items-center text-xl font-bold text-indigo-600">
                    <i class="ti ti-bolt mr-1"></i>DigitalStore
                </a>
                <h1 class="mt-6 text-3xl font-bold text-gray-800 leading-tight">
                    Produk digital siap pakai untuk belajar, bekerja, dan berkarya.
                </h1>
                <p class="mt-3 text-gray-500 leading-relaxed max-w-md">
                    Masuk untuk melanjutkan pembelian, melihat pesanan, dan mengunduh produk yang sudah kamu miliki.
                </p>

                <div class="mt-8 grid grid-cols-2 gap-3 max-w-lg">
                    <div class="bg-white border border-gray-100 rounded-xl p-4">
                        <i class="ti ti-download text-2xl text-indigo-500"></i>
                        <p class="mt-2 text-sm font-semibold text-gray-700">Download instan</p>
                        <p class="mt-1 text-xs text-gray-400">Akses file setelah pembayaran selesai.</p>
                    </div>
                    <div class="bg-white border border-gray-100 rounded-xl p-4">
                        <i class="ti ti-shield-check text-2xl text-indigo-500"></i>
                        <p class="mt-2 text-sm font-semibold text-gray-700">Akun aman</p>
                        <p class="mt-1 text-xs text-gray-400">Pesanan dan riwayat tersimpan rapi.</p>
                    </div>
                </div>
            </section>

            <section class="w-full max-w-md mx-auto">
                {{ $slot }}
            </section>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 mt-auto py-6 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} DigitalStore. All rights reserved.
    </footer>

</body>
</html>
