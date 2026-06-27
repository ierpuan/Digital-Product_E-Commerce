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
// Admin\ProductController
// ============================================================
class ProductController extends Controller
{
    // GET /admin/products
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    // GET /admin/products/create
    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    // POST /admin/products
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'thumbnail'   => 'nullable|image|max:2048',
            'file'        => 'required|file|max:51200|mimes:pdf,zip,mp4,epub,docx,pptx',
        ]);

        // Upload thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Upload file produk ke disk private (tidak bisa diakses langsung)
        $filePath = $request->file('file')->store('products', 'private');
        $fileSize = (int) round($request->file('file')->getSize() / 1024); // KB
        $fileType = $request->file('file')->getClientOriginalExtension();

        Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'price'       => $request->price,
            'thumbnail'   => $thumbnailPath,
            'file_path'   => $filePath,
            'file_size'   => $fileSize,
            'file_type'   => $fileType,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // GET /admin/products/{product}/edit
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // PUT /admin/products/{product}
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'thumbnail'   => 'nullable|image|max:2048',
            'file'        => 'nullable|file|max:51200|mimes:pdf,zip,mp4,epub,docx,pptx',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'price']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            Storage::disk('public')->delete($product->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('file')) {
            Storage::disk('private')->delete($product->file_path);
            $data['file_path'] = $request->file('file')->store('products', 'private');
            $data['file_size'] = (int) round($request->file('file')->getSize() / 1024);
            $data['file_type'] = $request->file('file')->getClientOriginalExtension();
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // DELETE /admin/products/{product}
    public function destroy(Product $product): RedirectResponse
    {
        Storage::disk('private')->delete($product->file_path);
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
