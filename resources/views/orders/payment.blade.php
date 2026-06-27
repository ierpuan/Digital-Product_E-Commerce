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

        @if($snapToken && $midtransClientKey)
            <button id="pay-button" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition">
                <i class="ti ti-credit-card mr-1"></i>Bayar Sekarang
            </button>
        @else
            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4 text-sm text-yellow-700">
                <i class="ti ti-alert-circle text-2xl mb-2 block"></i>
                Midtrans belum aktif. Isi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di file .env.
            </div>
        @endif

        <a href="{{ route('orders.history') }}" class="block mt-4 text-sm text-indigo-500 hover:underline">
            Lihat semua pesanan
        </a>
    </div>
</div>

@if($snapToken && $midtransClientKey)
    <script src="{{ $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function () {
                    window.location.href = '{{ route('orders.show', $order->id) }}';
                },
                onPending: function () {
                    window.location.href = '{{ route('orders.history') }}';
                },
                onError: function () {
                    window.location.href = '{{ route('orders.payment', $order->id) }}';
                },
                onClose: function () {}
            });
        });
    </script>
@endif
@endsection
