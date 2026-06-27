<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// ============================================================
// Admin\ReviewController
// ============================================================
class ReviewController extends Controller
{
    // GET /admin/reviews
    public function index()
    {
        $reviews = Review::with('user', 'product')
            ->where('is_approved', false)
            ->latest('created_at')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    // PATCH /admin/reviews/{review}/approve
    public function approve(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Ulasan disetujui.');
    }

    // DELETE /admin/reviews/{review}
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Ulasan dihapus.');
    }
}
