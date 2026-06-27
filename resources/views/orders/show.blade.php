{{-- ============================================================ --}}
{{-- resources/views/orders/show.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Detail Pesanan')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('orders.history') }}" class="text-sm text-indigo-500 hover:underline">
        <i class="ti ti-arrow-left"></i> Kembali
    </a>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mt-4">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="font-bold text-gray-800">{{ $order->invoice_no }}</h1>
                <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            @php
                $statusColor = match($order->status) {
                    'paid'     => 'bg-green-50 text-green-700',
                    'pending'  => 'bg-yellow-50 text-yellow-700',
                    'failed'   => 'bg-red-50 text-red-700',
                    'refunded' => 'bg-gray-100 text-gray-600',
                    default    => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusColor }}">
                {{ strtoupper($order->status) }}
            </span>
        </div>

        {{-- Items + Download --}}
        <div class="space-y-4">
            @foreach($order->orderItems as $item)
                <div class="border border-gray-100 rounded-xl p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $item->product_name }}</p>
                            <p class="text-indigo-600 font-bold text-sm mt-0.5">{{ $item->formatted_price }}</p>
                        </div>
                        @if($order->isPaid())
                            @php $token = $item->downloadTokens->first(); @endphp
                            @if($token && $token->isUsable())
                                <a href="{{ route('download', $token->token) }}"
                                   class="bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg hover:bg-indigo-700 flex items-center gap-1">
                                    <i class="ti ti-download"></i>
                                    Download ({{ $token->remainingDownloads() }}x sisa)
                                </a>
                            @else
                                <span class="text-xs text-gray-400">Download tidak tersedia</span>
                            @endif
                        @endif
                    </div>

                    {{-- Form Ulasan --}}
                    @if($order->isPaid() && !$item->review)
                        <form method="POST" action="{{ route('reviews.store') }}" class="mt-3 border-t pt-3">
                            @csrf
                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                            <p class="text-xs text-gray-500 mb-2">Beri ulasan:</p>
                            <div class="flex gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                        <i class="ti ti-star text-xl text-gray-300 hover:text-yellow-400"></i>
                                    </label>
                                @endfor
                            </div>
                            <textarea name="comment" rows="2" placeholder="Tulis ulasan (opsional)..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
                            <button class="mt-2 bg-gray-800 text-white text-xs px-4 py-2 rounded-lg hover:bg-gray-900">
                                Kirim Ulasan
                            </button>
                        </form>
                    @elseif($item->review)
                        <div class="mt-3 border-t pt-3 text-xs text-gray-400">
                            <i class="ti ti-check text-green-500 mr-1"></i>
                            Ulasan sudah dikirim ({{ $item->review->stars }})
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="mt-5 border-t pt-4 space-y-1 text-sm text-gray-600">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-gray-800 text-base pt-1">
                <span>Total</span>
                <span class="text-indigo-600">{{ $order->formatted_total }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
