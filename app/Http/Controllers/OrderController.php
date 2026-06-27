<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // GET /checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $subtotal = collect($cart)->sum('price');

        return view('orders.checkout', compact('cart', 'subtotal'));
    }

    // POST /checkout/apply-voucher
    public function applyVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $cart     = session()->get('cart', []);
        $subtotal = collect($cart)->sum('price');

        $voucher = Voucher::where('code', strtoupper($request->code))->valid()->first();

        if (!$voucher) {
            return back()->with('error', 'Kode voucher tidak valid atau sudah kedaluwarsa.');
        }

        if ($subtotal < $voucher->min_purchase) {
            return back()->with('error', 'Minimum pembelian Rp ' . number_format($voucher->min_purchase, 0, ',', '.'));
        }

        session()->put('voucher', [
            'id'       => $voucher->id,
            'code'     => $voucher->code,
            'discount' => $voucher->calculateDiscount((float) $subtotal),
        ]);

        return back()->with('success', 'Voucher berhasil diterapkan!');
    }

    // POST /checkout/process
    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        /** @var \App\Models\User $user */
        $user           = Auth::user();
        $subtotal       = collect($cart)->sum('price');
        $voucherSession = session()->get('voucher');
        $discount       = $voucherSession['discount'] ?? 0;
        $total          = max(0, $subtotal - $discount);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'    => $user->id,
                'voucher_id' => $voucherSession['id'] ?? null,
                'subtotal'   => $subtotal,
                'discount'   => $discount,
                'total'      => $total,
            ]);

            foreach ($cart as $item) {
                $order->orderItems()->create([
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                ]);
            }

            DB::commit();

            session()->forget(['cart', 'voucher']);

            return redirect()->route('orders.payment', $order->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat order. Silakan coba lagi.');
        }
    }

    // GET /orders/{order}/payment
    public function payment(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_if($order->user_id !== $user->id, 403);

        if ($order->isPaid()) {
            return redirect()->route('orders.show', $order->id);
        }

        $snapToken = $this->createMidtransSnapToken($order);

        return view('orders.payment', [
            'order' => $order,
            'snapToken' => $snapToken,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransIsProduction' => (bool) config('services.midtrans.is_production'),
        ]);
    }

    // GET /orders/{order}
    public function show(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_if($order->user_id !== $user->id, 403);

        $order->load('orderItems.product', 'orderItems.downloadTokens', 'voucher');

        return view('orders.show', compact('order'));
    }

    // GET /orders
    public function history()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with('orderItems')
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    private function createMidtransSnapToken(Order $order): ?string
    {
        $serverKey = config('services.midtrans.server_key');

        if (!$serverKey) {
            return null;
        }

        $order->loadMissing('user', 'orderItems');

        $payload = [
            'transaction_details' => [
                'order_id' => $order->invoice_no,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
            'item_details' => $order->orderItems->map(fn ($item) => [
                'id' => (string) $item->product_id,
                'price' => (int) $item->price,
                'quantity' => 1,
                'name' => $item->product_name,
            ])->values()->all(),
            'callbacks' => [
                'finish' => route('orders.show', $order->id),
            ],
        ];

        if ((int) $order->discount > 0) {
            $payload['item_details'][] = [
                'id' => 'DISCOUNT',
                'price' => -1 * (int) $order->discount,
                'quantity' => 1,
                'name' => 'Diskon',
            ];
        }

        $baseUrl = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->acceptJson()
                ->post($baseUrl . '/snap/v1/transactions', $payload);

            if ($response->failed()) {
                Log::warning('[Midtrans] Gagal membuat Snap token', [
                    'invoice' => $order->invoice_no,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return null;
            }

            return $response->json('token');
        } catch (\Throwable $e) {
            Log::error('[Midtrans] Error membuat Snap token', [
                'invoice' => $order->invoice_no,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
