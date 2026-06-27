<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ti ti-shield-lock text-red-400 text-5xl"></i>
        </div>
        <h1 class="text-8xl font-black text-red-500 mb-2">403</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Akses Ditolak</h2>
        <p class="text-gray-500 mb-2 max-w-md mx-auto">
            Kamu tidak memiliki izin untuk mengakses halaman ini.
        </p>
        @if($exception->getMessage())
            <p class="text-sm text-red-400 mb-8 bg-red-50 border border-red-100 px-4 py-2 rounded-lg inline-block">
                {{ $exception->getMessage() }}
            </p>
        @else
            <div class="mb-8"></div>
        @endif
        <div class="flex items-center justify-center gap-3">
            <a href="{{ url('/') }}"
               class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition">
                <i class="ti ti-home mr-1"></i>Kembali ke Beranda
            </a>
            @auth
                <a href="{{ route('products.index') }}"
                   class="bg-white text-gray-600 border border-gray-200 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-50 transition">
                    <i class="ti ti-arrow-left mr-1"></i>Kembali
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="bg-white text-indigo-600 border border-indigo-200 px-6 py-2.5 rounded-xl font-semibold hover:bg-indigo-50 transition">
                    <i class="ti ti-login mr-1"></i>Masuk
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
