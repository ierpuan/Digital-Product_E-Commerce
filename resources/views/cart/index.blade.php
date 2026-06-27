@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Keranjang Belanja</h1>

@if(empty($cart))
    <div class="text-center py-20 text-gray-400">
        <i class="ti ti-shopping-cart-off text-5xl mb-3 block"></i>
        <p class="mb-4">Keranjang kamu masih kosong.</p>
        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:underline text-sm">Mulai belanja</a>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Daftar Item --}}
        <div class="lg:col-span-2 space-y-3">
            @foreach($cart as $item)
                <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center shrink-0">
                            <i class="ti ti-file text-indigo-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $item['name'] }}</p>
                            <p class="text-indigo-600 font-bold text-sm mt-0.5">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600 text-sm">
                            <i class="ti ti-trash"></i>
                        </button>
                    </form>
                </div>
            @endforeach

            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf @method('DELETE')
                <button class="text-xs text-gray-400 hover:text-red-500 mt-1">
                    <i class="ti ti-x mr-1"></i>Kosongkan keranjang
                </button>
            </form>
        </div>

        {{-- Ringkasan --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 h-fit">
            <h2 class="font-semibold text-gray-700 mb-4">Ringkasan</h2>
            <div class="flex justify-between text-sm text-gray-600 mb-4">
                <span>Subtotal ({{ count($cart) }} item)</span>
                <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout') }}"
               class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                Lanjut Checkout
            </a>
        </div>
    </div>
@endif
@endsection
