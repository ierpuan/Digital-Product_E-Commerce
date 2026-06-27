{{-- ============================================================ --}}
{{-- resources/views/admin/reviews/index.blade.php --}}
{{-- ============================================================ --}}
@extends('layouts.admin')
@section('title', 'Moderasi Ulasan')

@section('content')
<p class="text-sm text-gray-500 mb-5">Ulasan menunggu persetujuan: <strong>{{ $reviews->total() }}</strong></p>

@forelse($reviews as $review)
    <div class="bg-white rounded-xl border border-gray-100 p-5 mb-3 flex items-start justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-sm font-semibold text-gray-700">{{ $review->user->name }}</span>
                <span class="text-xs text-gray-400">→</span>
                <span class="text-sm text-indigo-600">{{ $review->product->name }}</span>
            </div>
            <div class="text-yellow-400 text-sm mb-1">
                @for($i = 1; $i <= 5; $i++)
                    <i class="ti ti-star{{ $i <= $review->rating ? '-filled' : '' }}"></i>
                @endfor
                <span class="text-gray-400 text-xs ml-1">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            @if($review->comment)
                <p class="text-sm text-gray-600">{{ $review->comment }}</p>
            @else
                <p class="text-xs text-gray-400 italic">Tidak ada komentar.</p>
            @endif
        </div>
        <div class="flex gap-2 shrink-0">
            <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}">
                @csrf @method('PATCH')
                <button class="bg-green-500 text-white text-xs px-3 py-2 rounded-lg hover:bg-green-600 flex items-center gap-1">
                    <i class="ti ti-check"></i> Setujui
                </button>
            </form>
            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}"
                  onsubmit="return confirm('Hapus ulasan ini?')">
                @csrf @method('DELETE')
                <button class="bg-red-50 text-red-500 text-xs px-3 py-2 rounded-lg hover:bg-red-100 flex items-center gap-1">
                    <i class="ti ti-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
@empty
    <div class="text-center py-20 text-gray-400">
        <i class="ti ti-star-off text-5xl mb-3 block"></i>
        <p>Tidak ada ulasan yang menunggu persetujuan.</p>
    </div>
@endforelse

<div class="mt-4">{{ $reviews->links() }}</div>
@endsection
