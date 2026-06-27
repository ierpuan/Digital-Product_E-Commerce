@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Daftar Item --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h2 class="font-semibold text-gray-700 mb-4">Item Pesanan</h2>
            @foreach($cart as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <span class="text-sm text-gray-700">{{ $item['name'] }}</span>
                    <span class="text-sm font-semibold text-indigo-600">
                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Form Voucher --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <h2 class="font-semibold text-gray-700 mb-3">Kode Voucher</h2>
            @if(session('voucher'))
                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg px-4 py-2 text-sm">
                    <span class="text-green-700">
                        <i class="ti ti-tag mr-1"></i>
                        <strong>{{ session('voucher.code') }}</strong> —
                        Hemat Rp {{ number_format(session('voucher.discount'), 0, ',', '.') }}
                    </span>
                    <form method="POST" action="{{ route('checkout.voucher') }}">
                        @csrf
                        <input type="hidden" name="code" value="">
                        <button class="text-red-400 hover:text-red-600 text-xs">Hapus</button>
                    </form>
                </div>
            @else
                <form method="POST" action="{{ route('checkout.voucher') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="code" placeholder="Masukkan kode voucher"
                           class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 uppercase">
                    <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900">
                        Pakai
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Ringkasan Pembayaran --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 h-fit">
        <h2 class="font-semibold text-gray-700 mb-4">Ringkasan Pembayaran</h2>

        @php
            $discount = session('voucher.discount', 0);
            $total    = max(0, $subtotal - $discount);
        @endphp

        <div class="space-y-2 text-sm text-gray-600 mb-4">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            @if($discount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Diskon Voucher</span>
                    <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-gray-800 text-base border-t pt-2 mt-2">
                <span>Total</span>
                <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <button class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                Buat Pesanan
            </button>
        </form>

        <p class="text-xs text-gray-400 text-center mt-3">
            Kamu akan diarahkan ke halaman pembayaran
        </p>
    </div>
</div>
@endsection
