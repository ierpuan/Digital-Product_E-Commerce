<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // POST /reviews
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Pastikan order_item milik user yang login dan ordernya sudah paid
        $orderItem = OrderItem::with('order')
            ->where('id', $request->order_item_id)
            ->whereHas('order', fn($q) => $q->where('user_id', $user->id)->where('status', 'paid'))
            ->firstOrFail();

        // Cegah review duplikat
        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('order_item_id', $orderItem->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        Review::create([
            'user_id'       => $user->id,
            'product_id'    => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
            'is_approved'   => false,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim dan menunggu persetujuan.');
    }
}
