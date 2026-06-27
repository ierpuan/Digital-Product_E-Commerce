<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireOrders extends Command
{
    protected $signature   = 'orders:expire';
    protected $description = 'Ubah status order PENDING yang sudah melewati batas waktu pembayaran menjadi FAILED';

    public function handle(): int
    {
        $expired = Order::where('status', Order::STATUS_PENDING)
            ->where('expired_at', '<=', now())
            ->get();

        if ($expired->isEmpty()) {
            $this->info('Tidak ada order yang perlu diexpire.');
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($expired as $order) {
            $order->update(['status' => Order::STATUS_FAILED]);

            Log::info('[ExpireOrders] Order diexpire', [
                'invoice_no' => $order->invoice_no,
                'user_id'    => $order->user_id,
                'total'      => $order->total,
                'expired_at' => $order->expired_at,
            ]);

            $count++;
        }

        $this->info("Berhasil mengexpire {$count} order.");
        return Command::SUCCESS;
    }
}
