<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/', fn() => redirect()->route('products.index'))->name('home');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/',        [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}',  [ProductController::class, 'show'])->name('show');
});

// ============================================================
// WEBHOOK — Dikecualikan dari CSRF di bootstrap/app.php
// ============================================================

Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])
    ->name('webhook.payment');

// ============================================================
// AUTH ROUTES (Breeze)
// ============================================================

require __DIR__.'/auth.php';

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================

Route::middleware(['auth', 'verified'])->group(function () {

    // Profile (bawaan Breeze)
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Keranjang
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/',                      [CartController::class, 'index'])->name('index');
        Route::post('/add',                  [CartController::class, 'add'])->name('add');
        Route::delete('/remove/{productId}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear',              [CartController::class, 'clear'])->name('clear');
    });

    // Checkout & Order
    Route::get('/checkout',                [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/apply-voucher', [OrderController::class, 'applyVoucher'])->name('checkout.voucher');
    Route::post('/checkout/process',       [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/orders',                  [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}',          [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/payment',  [OrderController::class, 'payment'])->name('orders.payment');

    // Download
    Route::get('/download/{token}', DownloadController::class)->name('download');

    // Ulasan
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// ============================================================
// ADMIN ROUTES — auth + middleware admin
// ============================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Produk (CRUD)
    Route::resource('products', Admin\ProductController::class)->except(['show']);

    // Order (read-only)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/',        [Admin\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [Admin\OrderController::class, 'show'])->name('show');
    });

    // Moderasi ulasan
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/',                   [Admin\ReviewController::class, 'index'])->name('index');
        Route::patch('/{review}/approve', [Admin\ReviewController::class, 'approve'])->name('approve');
        Route::delete('/{review}',        [Admin\ReviewController::class, 'destroy'])->name('destroy');
    });
});
