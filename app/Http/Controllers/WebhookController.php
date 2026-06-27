<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * POST /webhook/payment
     * Endpoint ini dipanggil otomatis oleh payment gateway (Midtrans, Xendit, dll)
     * saat status pembayaran berubah.
     *
     * PENTING: Route ini dikecualikan dari CSRF middleware (lihat routes/web.php)
     */
    public function handlePayment(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('[Webhook] Payload diterima', ['payload' => $payload]);

        // --- Validasi Signature Key (contoh Midtrans) ---
        $isValid = $this->validateSignature($payload);

        // Cari order berdasarkan invoice_no yang dikirim gateway
        $invoiceNo = $payload['order_id'] ?? null;
        $order     = Order::where('invoice_no', $invoiceNo)->first();

        // Catat log webhook meski order tidak ditemukan
        PaymentLog::create([
            'order_id'        => $order?->id,
            'gateway'         => 'midtrans',
            'transaction_id'  => $payload['transaction_id'] ?? null,
            'status'          => $payload['transaction_status'] ?? 'unknown',
            'amount'          => $payload['gross_amount'] ?? 0,
            'payload'         => $payload,
            'signature_valid' => $isValid,
        ]);

        if (!$isValid) {
            Log::warning('[Webhook] Signature tidak valid', ['invoice' => $invoiceNo]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        if (!$order) {
            Log::error('[Webhook] Order tidak ditemukan', ['invoice' => $invoiceNo]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Proses perubahan status berdasarkan status dari gateway
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? 'accept';

        if (in_array($transactionStatus, ['capture', 'settlement']) && $fraudStatus === 'accept') {
            if (!$order->isPaid()) {
                $order->update([
                    'status'  => Order::STATUS_PAID,
                    'paid_at' => now(),
                ]);
                Log::info('[Webhook] Order berhasil dibayar', ['invoice' => $invoiceNo]);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
            $order->update(['status' => Order::STATUS_FAILED]);
            Log::info('[Webhook] Order gagal/expired', ['invoice' => $invoiceNo]);
        }

        return response()->json(['message' => 'OK']);
    }

    private function validateSignature(array $payload): bool
    {
        // Validasi signature Midtrans
        // Format: SHA512(order_id + status_code + gross_amount + server_key)
        $orderId     = $payload['order_id'] ?? '';
        $statusCode  = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey   = config('services.midtrans.server_key', '');

        if (empty($serverKey)) {
            Log::warning('[Webhook] Server key Midtrans belum dikonfigurasi');
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        $received = $payload['signature_key'] ?? '';

        return hash_equals($expected, $received);
    }
}
