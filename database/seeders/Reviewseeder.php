<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $comments = [
            5 => [
                'Produk sangat berkualitas, sesuai deskripsi. Sangat puas!',
                'Mantap banget, langsung bisa dipakai. Recommended!',
                'Worth it banget harganya, kontennya lengkap dan mudah dipahami.',
                'Keren! File langsung bisa didownload setelah bayar. Cepat banget.',
            ],
            4 => [
                'Bagus, cukup membantu untuk belajar. Semoga ada update berikutnya.',
                'Secara keseluruhan memuaskan, hanya saja bisa lebih detail di beberapa bagian.',
                'Produknya oke, pengiriman (download) cepat. Puas dengan pembelian ini.',
            ],
            3 => [
                'Lumayan, tapi ada beberapa bagian yang masih kurang jelas.',
                'Cukup sesuai ekspektasi, bisa dipakai meski tidak sempurna.',
            ],
        ];

        // Ambil semua order_items dari order yang sudah paid
        $paidOrders = Order::where('status', 'paid')->with('orderItems')->get();

        foreach ($paidOrders as $order) {
            foreach ($order->orderItems as $item) {
                // 80% chance item di-review
                if (rand(1, 10) > 2) {
                    $rating  = $this->randomRating();
                    $pool    = $comments[$rating] ?? $comments[4];
                    $comment = $pool[array_rand($pool)];

                    Review::create([
                        'user_id'       => $order->user_id,
                        'product_id'    => $item->product_id,
                        'order_item_id' => $item->id,
                        'rating'        => $rating,
                        'comment'       => $comment,
                        'is_approved'   => (bool) rand(0, 1), // 50% langsung approved
                    ]);
                }
            }
        }
    }

    // Distribusi rating realistis: lebih banyak rating 4-5
    private function randomRating(): int
    {
        $distribution = [5, 5, 5, 5, 4, 4, 4, 3, 3, 3];
        return $distribution[array_rand($distribution)];
    }
}
