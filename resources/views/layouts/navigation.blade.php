<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('products.index') }}" class="text-xl font-bold text-indigo-600">
                        <i class="ti ti-bolt mr-1"></i>DigitalStore
                    </a>
                </div>
            </div>

            <!-- Nav Links (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-indigo-600">
                        <i class="ti ti-shopping-cart text-xl"></i>
                        @if(count(session('cart', [])) > 0)
                            <span class="absolute -top-1 -right-2 bg-indigo-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                {{ count(session('cart', [])) }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('orders.history') }}" class="text-sm text-gray-600 hover:text-indigo-600">Pesanan</a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600">Admin</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 hover:text-indigo-600">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-gray-500 hover:text-red-500">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700">Daftar</a>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" :class="{ 'hidden': open, 'block': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="h-6 w-6" :class="{ 'block': open, 'hidden': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-gray-100">
        <div class="px-4 py-3 space-y-2">
            @auth
                <a href="{{ route('cart.index') }}" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Keranjang</a>
                <a href="{{ route('orders.history') }}" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Pesanan</a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Admin</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block text-sm text-red-500 py-1">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-sm text-gray-600 py-1">Masuk</a>
                <a href="{{ route('register') }}" class="block text-sm text-indigo-600 py-1">Daftar</a>
            @endauth
        </div>
    </div>
</nav>
