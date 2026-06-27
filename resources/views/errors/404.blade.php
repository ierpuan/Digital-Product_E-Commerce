<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ti ti-file-search text-indigo-400 text-5xl"></i>
        </div>
        <h1 class="text-8xl font-black text-indigo-600 mb-2">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">
            Halaman yang kamu cari tidak ada, sudah dipindahkan, atau URL-nya salah.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ url('/') }}"
               class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition">
                <i class="ti ti-home mr-1"></i>Kembali ke Beranda
            </a>
            <a href="{{ route('products.index') }}"
               class="bg-white text-indigo-600 border border-indigo-200 px-6 py-2.5 rounded-xl font-semibold hover:bg-indigo-50 transition">
                <i class="ti ti-shopping-bag mr-1"></i>Lihat Produk
            </a>
        </div>
    </div>
</body>
</html>
