<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code'         => 'WELCOME10',
                'type'         => 'percent',
                'value'        => 10,
                'min_purchase' => 0,
                'max_uses'     => null,   // tidak terbatas
                'expired_at'   => now()->addYear(),
                'is_active'    => true,
            ],
            [
                'code'         => 'DISKON50K',
                'type'         => 'fixed',
                'value'        => 50000,
                'min_purchase' => 150000,
                'max_uses'     => 100,
                'expired_at'   => now()->addMonths(3),
                'is_active'    => true,
            ],
            [
                'code'         => 'HEMAT25',
                'type'         => 'percent',
                'value'        => 25,
                'min_purchase' => 100000,
                'max_uses'     => 50,
                'expired_at'   => now()->addMonths(1),
                'is_active'    => true,
            ],
            [
                'code'         => 'FLASH20',
                'type'         => 'percent',
                'value'        => 20,
                'min_purchase' => 50000,
                'max_uses'     => 30,
                'expired_at'   => now()->addDays(7),
                'is_active'    => true,
            ],
            [
                'code'         => 'EXPIRED99',
                'type'         => 'percent',
                'value'        => 99,
                'min_purchase' => 0,
                'max_uses'     => 999,
                'expired_at'   => now()->subDay(), // sudah expired
                'is_active'    => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
