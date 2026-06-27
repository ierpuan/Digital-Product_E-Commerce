@extends('layouts.app')
@section('title', 'Riwayat Pesanan')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Pesanan</h1>

@forelse($orders as $order)
    @php
        $statusColor = match($order->status) {
            'paid'     => 'bg-green-50 text-green-700',
            'pending'  => 'bg-yellow-50 text-yellow-700',
            'failed'   => 'bg-red-50 text-red-700',
            'refunded' => 'bg-gray-100 text-gray-600',
            default    => 'bg-gray-100 text-gray-600',
        };
    @endphp
    <a href="{{ route('orders.show', $order->id) }}"
       class="block bg-white border border-gray-100 rounded-xl p-4 mb-3 hover:shadow-sm hover:border-indigo-200 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-700">{{ $order->invoice_no }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y') }} ·
                    {{ $order->orderItems->count() }} item
                </p>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusColor }}">
                    {{ strtoupper($order->status) }}
                </span>
                <p class="text-sm font-bold text-indigo-600 mt-1">{{ $order->formatted_total }}</p>
            </div>
        </div>
    </a>
@empty
    <div class="text-center py-20 text-gray-400">
        <i class="ti ti-receipt-off text-5xl mb-3 block"></i>
        <p>Belum ada pesanan.</p>
        <a href="{{ route('products.index') }}" class="text-indigo-500 text-sm hover:underline mt-2 block">
            Mulai belanja
        </a>
    </div>
@endforelse

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
