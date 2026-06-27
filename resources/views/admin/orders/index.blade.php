{{-- ============================================================ --}}
{{-- resources/views/admin/orders/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Kelola Order')

@section('content')
{{-- Filter Status --}}
<div class="flex gap-2 mb-5">
    @foreach(['', 'pending', 'paid', 'failed', 'refunded'] as $s)
        <a href="{{ route('admin.orders.index', $s ? ['status' => $s] : []) }}"
           class="text-xs px-3 py-1.5 rounded-full border
               {{ request('status') === $s || (!request('status') && $s === '') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-500 border-gray-200 hover:border-indigo-300' }}">
            {{ $s ? strtoupper($s) : 'SEMUA' }}
        </a>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-400 border-b">
            <tr>
                <th class="text-left px-4 py-3">Invoice</th>
                <th class="text-left px-4 py-3">Pembeli</th>
                <th class="text-left px-4 py-3">Total</th>
                <th class="text-left px-4 py-3">Status</th>
                <th class="text-left px-4 py-3">Tanggal</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($orders as $order)
                @php
                    $sc = match($order->status) {
                        'paid' => 'bg-green-50 text-green-700', 'pending' => 'bg-yellow-50 text-yellow-700',
                        'failed' => 'bg-red-50 text-red-700', default => 'bg-gray-100 text-gray-500'
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-indigo-600">{{ $order->invoice_no }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $order->user->name }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $order->formatted_total }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $sc }}">{{ strtoupper($order->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-500 hover:underline text-xs">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-50">{{ $orders->links() }}</div>
</div>
@endsection
