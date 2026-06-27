<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ============================================================
// Jalankan setiap 5 menit — cek order pending yang sudah expired
// ============================================================
Schedule::command('orders:expire')->everyFiveMinutes();
