<?php

namespace Database\Seeders;

use App\Models\DownloadToken;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products  = Product::all();

        // Setiap customer dapat 2 order: 1 paid, 1 pending
        foreach ($customers as $customer) {
            $this->createPaidOrder($customer, $products);
            $this->createPendingOrder($customer, $products);
        }
    }

    private function createPaidOrder(User $customer, $products): void
    {
        // Ambil 2 produk acak
        $selectedProducts = $products->random(2);
        $subtotal         = $selectedProducts->sum('price');
        $invoiceNo        = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $order = Order::create([
            'user_id'         => $customer->id,
            'voucher_id'      => null,
            'invoice_no'      => $invoiceNo,
            'subtotal'        => $subtotal,
            'discount'        => 0,
            'total'           => $subtotal,
            'status'          => Order::STATUS_PAID,
            'payment_method'  => 'bank_transfer',
            'payment_channel' => 'bca',
            'paid_at'         => now()->subDays(rand(1, 30)),
            'expired_at'      => now()->addHours(24),
        ]);

        foreach ($selectedProducts as $product) {
            $item = OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'price'        => $product->price,
            ]);

            // Buat download token untuk setiap item
            DownloadToken::create([
                'order_item_id'  => $item->id,
                'user_id'        => $customer->id,
                'token'          => Str::random(64),
                'download_count' => rand(0, 3),
                'max_downloads'  => 5,
                'expired_at'     => now()->addDays(30),
            ]);
        }

        // Log pembayaran sukses
        PaymentLog::create([
            'order_id'        => $order->id,
            'gateway'         => 'midtrans',
            'transaction_id'  => 'TXN-' . strtoupper(Str::random(12)),
            'status'          => 'settlement',
            'amount'          => $subtotal,
            'payload'         => [
                'order_id'           => $invoiceNo,
                'transaction_status' => 'settlement',
                'gross_amount'       => $subtotal,
                'fraud_status'       => 'accept',
            ],
            'signature_valid' => true,
        ]);
    }

    private function createPendingOrder(User $customer, $products): void
    {
        $selectedProduct = $products->random(1)->first();
        $invoiceNo       = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $order = Order::create([
            'user_id'     => $customer->id,
            'voucher_id'  => null,
            'invoice_no'  => $invoiceNo,
            'subtotal'    => $selectedProduct->price,
            'discount'    => 0,
            'total'       => $selectedProduct->price,
            'status'      => Order::STATUS_PENDING,
            'expired_at'  => now()->addHours(24),
        ]);

        OrderItem::create([
            'order_id'     => $order->id,
            'product_id'   => $selectedProduct->id,
            'product_name' => $selectedProduct->name,
            'price'        => $selectedProduct->price,
        ]);
    }
}
