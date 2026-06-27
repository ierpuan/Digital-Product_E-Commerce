<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // GET /cart
    public function index()
    {
        $cart     = session()->get('cart', []);
        $subtotal = collect($cart)->sum('price');

        return view('cart.index', compact('cart', 'subtotal'));
    }

    // POST /cart/add
    public function add(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $product = Product::active()->findOrFail($request->product_id);
        $cart    = session()->get('cart', []);

        // Produk digital: 1 item per produk, cegah duplikasi
        if (isset($cart[$product->id])) {
            return back()->with('info', 'Produk sudah ada di keranjang.');
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name'       => $product->name,
            'price'      => $product->price,
            'thumbnail'  => $product->thumbnail,
        ];

        session()->put('cart', $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    // DELETE /cart/remove/{productId}
    public function remove(int $productId): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    // DELETE /cart/clear
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('products.index')->with('success', 'Keranjang dikosongkan.');
    }
}
