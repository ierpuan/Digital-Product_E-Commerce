@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Info Produk --}}
    <div class="lg:col-span-2">
        <a href="{{ route('products.index') }}" class="text-sm text-indigo-500 hover:underline">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>

        <div class="mt-4 bg-white rounded-xl border border-gray-100 p-6">
            <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-1 rounded-full">
                {{ $product->category->name }}
            </span>
            <h1 class="text-2xl font-bold text-gray-800 mt-3">{{ $product->name }}</h1>

            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                <span><i class="ti ti-star-filled text-yellow-400"></i>
                    {{ $avgRating ? number_format($avgRating, 1) : 'Belum ada' }} ulasan
                </span>
                <span><i class="ti ti-shopping-bag"></i> {{ $product->total_sales }}x terjual</span>
                <span><i class="ti ti-file"></i> {{ strtoupper($product->file_type) }} · {{ $product->file_size_label }}</span>
            </div>

            <p class="text-gray-600 mt-5 leading-relaxed">{{ $product->description }}</p>
        </div>

        {{-- Ulasan --}}
        <div class="mt-6 bg-white rounded-xl border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-700 mb-4">
                Ulasan Pembeli ({{ $product->reviews->count() }})
            </h2>

            @forelse($product->reviews as $review)
                <div class="border-b border-gray-50 py-3 last:border-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ $review->user->name }}</span>
                        <span class="text-yellow-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ti ti-star{{ $i <= $review->rating ? '-filled' : '' }}"></i>
                            @endfor
                        </span>
                    </div>
                    @if($review->comment)
                        <p class="text-sm text-gray-500 mt-1">{{ $review->comment }}</p>
                    @endif
                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>
    </div>

    {{-- Panel Beli --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-100 p-5 sticky top-20">
            <div class="text-3xl font-bold text-indigo-600 mb-4">{{ $product->formatted_price }}</div>

            @auth
                @php
                    $inCart = isset(session('cart', [])[$product->id]);
                @endphp
                @if($inCart)
                    <a href="{{ route('cart.index') }}"
                       class="block w-full text-center bg-green-500 text-white py-3 rounded-xl font-semibold hover:bg-green-600 transition">
                        <i class="ti ti-shopping-cart-check mr-1"></i>Lihat Keranjang
                    </a>
                @else
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                            <i class="ti ti-shopping-cart-plus mr-1"></i>Tambah ke Keranjang
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                    Masuk untuk Membeli
                </a>
            @endauth

            <ul class="mt-5 space-y-2 text-sm text-gray-500">
                <li><i class="ti ti-download text-indigo-400 mr-2"></i>Download langsung setelah bayar</li>
                <li><i class="ti ti-refresh text-indigo-400 mr-2"></i>Maksimal 5x download</li>
                <li><i class="ti ti-clock text-indigo-400 mr-2"></i>Link aktif 30 hari</li>
                <li><i class="ti ti-shield-check text-indigo-400 mr-2"></i>Pembayaran aman</li>
            </ul>
        </div>
    </div>

</div>
@endsection
