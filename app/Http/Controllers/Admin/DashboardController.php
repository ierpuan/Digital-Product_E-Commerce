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
// Admin\DashboardController
// ============================================================
class DashboardController extends Controller
{
    // GET /admin/dashboard
    public function index()
    {
        $stats = [
            'total_users'    => User::count(),
            'total_products' => Product::count(),
            'total_orders'   => Order::count(),
            'total_revenue'  => Order::where('status', 'paid')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'pending_reviews'=> Review::where('is_approved', false)->count(),
        ];

        $latestOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestOrders'));
    }
}
