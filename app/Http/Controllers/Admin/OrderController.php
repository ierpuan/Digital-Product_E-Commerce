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
// Admin\OrderController
// ============================================================
class OrderController extends Controller
{
    // GET /admin/orders
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    // GET /admin/orders/{order}
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.product', 'paymentLogs', 'voucher');

        return view('admin.orders.show', compact('order'));
    }
}
