{{-- ============================================================ --}}
{{-- resources/views/admin/dashboard.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    @php
        $cards = [
            ['label' => 'Total Pengguna',  'value' => $stats['total_users'],    'icon' => 'ti-users',        'color' => 'text-blue-500',   'bg' => 'bg-blue-50'],
            ['label' => 'Total Produk',    'value' => $stats['total_products'], 'icon' => 'ti-package',      'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
            ['label' => 'Total Order',     'value' => $stats['total_orders'],   'icon' => 'ti-receipt',      'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],
            ['label' => 'Pendapatan',      'value' => 'Rp ' . number_format($stats['total_revenue'], 0, ',', '.'), 'icon' => 'ti-cash', 'color' => 'text-green-500', 'bg' => 'bg-green-50'],
            ['label' => 'Order Pending',   'value' => $stats['pending_orders'], 'icon' => 'ti-clock',        'color' => 'text-yellow-500', 'bg' => 'bg-yellow-50'],
            ['label' => 'Ulasan Pending',  'value' => $stats['pending_reviews'],'icon' => 'ti-star',         'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="bg-white rounded-xl border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 {{ $card['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                <i class="ti {{ $card['icon'] }} {{ $card['color'] }} text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">{{ $card['label'] }}</p>
                <p class="text-lg font-bold text-gray-800">{{ $card['value'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- Latest Orders --}}
<div class="bg-white rounded-xl border border-gray-100 p-5">
    <h2 class="font-semibold text-gray-700 mb-4">Order Terbaru</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-400 border-b border-gray-100">
                <th class="pb-2">Invoice</th>
                <th class="pb-2">Pembeli</th>
                <th class="pb-2">Total</th>
                <th class="pb-2">Status</th>
                <th class="pb-2">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($latestOrders as $order)
                @php
                    $sc = match($order->status) {
                        'paid' => 'bg-green-50 text-green-700', 'pending' => 'bg-yellow-50 text-yellow-700',
                        'failed' => 'bg-red-50 text-red-700', default => 'bg-gray-100 text-gray-500'
                    };
                @endphp
                <tr>
                    <td class="py-2 text-indigo-600 font-medium">
                        <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->invoice_no }}</a>
                    </td>
                    <td class="py-2 text-gray-600">{{ $order->user->name }}</td>
                    <td class="py-2 font-semibold">{{ $order->formatted_total }}</td>
                    <td class="py-2">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $sc }}">{{ strtoupper($order->status) }}</span>
                    </td>
                    <td class="py-2 text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
