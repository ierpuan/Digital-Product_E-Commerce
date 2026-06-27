@extends('layouts.app')
@section('title', 'Katalog Produk')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Produk Digital</h1>
        <p class="text-sm text-gray-500 mt-1">Temukan ebook, template, source code, dan lainnya</p>
    </div>
    {{-- Search --}}
    <form method="GET" action="{{ route('products.index') }}" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari produk..."
               class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-indigo-300">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            <i class="ti ti-search"></i>
        </button>
    </form>
</div>

{{-- Filter Kategori --}}
<div class="flex gap-2 flex-wrap mb-6">
    <a href="{{ route('products.index') }}"
       class="px-3 py-1.5 rounded-full text-sm border
           {{ !request('category') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300' }}">
        Semua
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
           class="px-3 py-1.5 rounded-full text-sm border
               {{ request('category') === $cat->slug ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300' }}">
            <i class="ti {{ $cat->icon }} mr-1"></i>{{ $cat->name }}
        </a>
    @endforeach
</div>

{{-- Grid Produk --}}
@if($products->isEmpty())
    <div class="text-center py-20 text-gray-400">
        <i class="ti ti-package-off text-5xl mb-3 block"></i>
        <p>Produk tidak ditemukan.</p>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($products as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="bg-white rounded-xl border border-gray-100 hover:shadow-md hover:border-indigo-200 transition overflow-hidden group">
                {{-- Thumbnail --}}
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 h-36 flex items-center justify-center">
                    @if($product->thumbnail)
                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <i class="ti ti-file-type-{{ $product->file_type === 'pdf' ? 'pdf' : 'zip' }} text-4xl text-indigo-300"></i>
                    @endif
                </div>
                <div class="p-3">
                    <span class="text-xs text-indigo-500 font-medium">{{ $product->category->name }}</span>
                    <h3 class="text-sm font-semibold text-gray-700 mt-1 line-clamp-2 group-hover:text-indigo-600">
                        {{ $product->name }}
                    </h3>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-indigo-600 font-bold text-sm">{{ $product->formatted_price }}</span>
                        <span class="text-xs text-gray-400">{{ $product->total_sales }}x terjual</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endif
@endsection
