<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex">

{{-- SIDEBAR --}}
<aside class="w-56 bg-indigo-900 text-indigo-100 min-h-screen flex flex-col py-6 px-3 shrink-0">
    <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-white px-3 mb-8 block">
        <i class="ti ti-bolt mr-1"></i>DigitalStore
    </a>
    @php
        $menu = [
            ['route' => 'admin.dashboard',      'icon' => 'ti-layout-dashboard', 'label' => 'Dashboard'],
            ['route' => 'admin.products.index', 'icon' => 'ti-package',          'label' => 'Produk'],
            ['route' => 'admin.orders.index',   'icon' => 'ti-receipt',          'label' => 'Order'],
            ['route' => 'admin.reviews.index',  'icon' => 'ti-star',             'label' => 'Ulasan'],
        ];
    @endphp
    @foreach($menu as $item)
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm mb-1
               {{ request()->routeIs($item['route']) ? 'bg-indigo-700 text-white' : 'hover:bg-indigo-800' }}">
            <i class="ti {{ $item['icon'] }}"></i>{{ $item['label'] }}
        </a>
    @endforeach
    <div class="mt-auto">
        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm text-indigo-300 hover:text-white">
            <i class="ti ti-arrow-left"></i>Ke Toko
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex items-center gap-3 px-3 py-2 text-sm text-indigo-300 hover:text-red-400 w-full">
                <i class="ti ti-logout"></i>Keluar
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="flex-1 flex flex-col min-h-screen">
    <header class="bg-white border-b border-gray-200 px-6 h-14 flex items-center justify-between">
        <h1 class="font-semibold text-gray-700">@yield('title', 'Dashboard')</h1>
        <span class="text-sm text-gray-500">{{ Auth::user()->name }}</span>
    </header>

    <div class="px-6 py-4 w-full">
        @foreach(['success','error'] as $type)
            @if(session($type))
                <div class="mb-4 px-4 py-3 rounded-lg text-sm
                    {{ $type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach
    </div>

    <main class="px-6 pb-8 flex-1">
        @yield('content')
    </main>
</div>

</body>
</html>
