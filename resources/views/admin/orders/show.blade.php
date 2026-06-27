{{-- ============================================================ --}}
{{-- resources/views/admin/orders/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Detail Order')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-500 hover:underline mb-4 block">
        <i class="ti ti-arrow-left"></i> Kembali
    </a>
    <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-5">
        {{-- Header --}}
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold text-gray-800">{{ $order->invoice_no }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</p>
                <p class="text-sm text-gray-600 mt-1">Pembeli: <strong>{{ $order->user->name }}</strong> ({{ $order->user->email }})</p>
            </div>
            @php
                $sc = match($order->status) {
                    'paid' => 'bg-green-50 text-green-700', 'pending' => 'bg-yellow-50 text-yellow-700',
                    'failed' => 'bg-red-50 text-red-700', default => 'bg-gray-100 text-gray-500'
                };
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $sc }}">{{ strtoupper($order->status) }}</span>
        </div>

        {{-- Items --}}
        <div class="border-t pt-4">
            <p class="text-xs font-semibold text-gray-400 uppercase mb-3">Item</p>
            @foreach($order->orderItems as $item)
                <div class="flex justify-between text-sm py-1">
                    <span class="text-gray-700">{{ $item->product_name }}</span>
                    <span class="font-semibold">{{ $item->formatted_price }}</span>
                </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="border-t pt-4 space-y-1 text-sm text-gray-600">
            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
            @if($order->discount > 0)
                <div class="flex justify-between text-green-600"><span>Diskon</span><span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
            @endif
            <div class="flex justify-between font-bold text-base text-gray-800 border-t pt-2">
                <span>Total</span><span class="text-indigo-600">{{ $order->formatted_total }}</span>
            </div>
        </div>

        {{-- Payment Logs --}}
        @if($order->paymentLogs->count())
            <div class="border-t pt-4">
                <p class="text-xs font-semibold text-gray-400 uppercase mb-3">Log Pembayaran</p>
                @foreach($order->paymentLogs as $log)
                    <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-600 mb-2">
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">{{ strtoupper($log->gateway) }} — {{ $log->status }}</span>
                            <span class="{{ $log->signature_valid ? 'text-green-600' : 'text-red-500' }}">
                                <i class="ti ti-{{ $log->signature_valid ? 'shield-check' : 'shield-x' }}"></i>
                                {{ $log->signature_valid ? 'Signature Valid' : 'Signature Invalid' }}
                            </span>
                        </div>
                        <p class="text-gray-400">TX: {{ $log->transaction_id ?? '-' }} · {{ $log->created_at->format('d M Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
