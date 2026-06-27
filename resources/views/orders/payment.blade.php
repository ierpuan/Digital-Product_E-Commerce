{{-- ============================================================ --}}
{{-- resources/views/orders/payment.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Pembayaran')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
        <div class="w-16 h-16 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ti ti-clock text-yellow-500 text-3xl"></i>
        </div>
        <h1 class="text-xl font-bold text-gray-800">Menunggu Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-2">Invoice: <strong>{{ $order->invoice_no }}</strong></p>

        <div class="bg-indigo-50 rounded-xl p-5 my-6 text-left space-y-2">
            @foreach($order->orderItems as $item)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $item->product_name }}</span>
                    <span class="font-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </div>
            @endforeach
            @if($order->discount > 0)
                <div class="flex justify-between text-sm text-green-600 border-t pt-2">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-indigo-700 border-t pt-2 text-base">
                <span>Total Bayar</span>
                <span>{{ $order->formatted_total }}</span>
            </div>
        </div>

        <p class="text-xs text-gray-400 mb-4">
            Selesaikan pembayaran sebelum
            <strong>{{ $order->expired_at?->format('d M Y H:i') }}</strong>
        </p>

        {{-- Di sini biasanya ditampilkan Snap Midtrans / redirect Xendit --}}
        <div class="bg-gray-50 border border-dashed border-gray-200 rounded-lg p-4 text-sm text-gray-400">
            <i class="ti ti-credit-card text-2xl mb-2 block"></i>
            Integrasi payment gateway (Midtrans Snap / Xendit) dipasang di sini.
        </div>

        <a href="{{ route('orders.history') }}" class="block mt-4 text-sm text-indigo-500 hover:underline">
            Lihat semua pesanan
        </a>
    </div>
</div>
@endsection
