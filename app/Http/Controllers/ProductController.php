<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products   = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // GET /products/{slug}
    public function show(string $slug)
    {
        $product = Product::with(['category', 'reviews' => fn($q) => $q->approved()->with('user')])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $avgRating = $product->reviews->avg('rating');

        return view('products.show', compact('product', 'avgRating'));
    }
}
