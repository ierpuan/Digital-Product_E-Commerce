# 🛒 Digital Product E-Commerce

Aplikasi e-commerce untuk penjualan produk digital (ebook, template, source code, video course, dll) berbasis **Laravel 12** dengan fitur lengkap mulai dari katalog produk, keranjang belanja, checkout, integrasi webhook pembayaran, hingga sistem download token yang aman.

---

## ✨ Fitur Utama

### 👤 Customer
- Registrasi & login (Laravel Breeze)
- Katalog produk dengan filter kategori & pencarian
- Keranjang belanja berbasis session
- Checkout dengan kode voucher diskon
- Pembayaran via Payment Gateway (Midtrans/Xendit) menggunakan Webhook
- Download produk digital dengan token unik (max 5x, expired 30 hari)
- Riwayat pesanan
- Ulasan & rating produk (hanya untuk pembeli)

### 🛡️ Admin
- Dashboard statistik (pendapatan, order, user, produk)
- Manajemen produk (CRUD + upload file ke disk private)
- Monitoring order & log webhook pembayaran
- Moderasi ulasan (approve / hapus)

---

## 🗂️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Frontend | Blade + Tailwind CSS (CDN) + Alpine.js |
| Database | MySQL |
| Auth | Laravel Breeze |
| Icons | Tabler Icons |
| Storage | Laravel Filesystem (disk `private`) |
| Payment | Midtrans Webhook (siap integrasi) |

---

## 📁 Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── OrderController.php
│   │   │   └── ReviewController.php
│   │   ├── Auth/                        # Breeze (dimodifikasi)
│   │   ├── CartController.php
│   │   ├── DownloadController.php
│   │   ├── OrderController.php
│   │   ├── ProductController.php
│   │   ├── ReviewController.php
│   │   └── WebhookController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Product.php
│   ├── Voucher.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── PaymentLog.php
│   ├── DownloadToken.php
│   └── Review.php
database/
├── migrations/
└── seeders/
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── admin.blade.php
│   └── navigation.blade.php
├── products/
├── cart/
├── orders/
└── admin/
```

---

## 🗃️ Struktur Database (ERD)

```
users ──────────────────┐
  │                     │
  ├──< orders >──────── vouchers
  │       │
  │       ├──< order_items >── products ──< categories
  │       │         │               │
  │       │         ├──< download_tokens
  │       │         └──< reviews
  │       │
  │       └──< payment_logs
  │
  └──< download_tokens
  └──< reviews
```

| Tabel | Keterangan |
|---|---|
| `users` | Pelanggan & admin (kolom `role`) |
| `categories` | Kategori produk digital |
| `products` | Produk dengan path file di disk private |
| `vouchers` | Kode diskon (percent / fixed nominal) |
| `orders` | Transaksi dengan state machine |
| `order_items` | Detail item + snapshot harga saat beli |
| `payment_logs` | Log setiap webhook callback + validasi signature |
| `download_tokens` | Token unik per item, dibatasi jumlah & waktu |
| `reviews` | Ulasan produk, hanya dari pembeli |

### State Machine Order
```
PENDING → PAID → (selesai otomatis, download token dibuat)
        → FAILED
        → REFUNDED
```

---

## ⚙️ Instalasi

### 1. Clone & Install Dependency

```bash
git clone <repo-url>
cd Digital_Product_E-Commerce

composer install
npm install && npm run dev
```

### 2. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME="Digital Store"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digital_product
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file

# Midtrans (opsional, untuk integrasi pembayaran)
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
```

### 3. Konfigurasi Disk Storage

Pastikan `config/filesystems.php` memiliki disk `private`:

```php
'private' => [
    'driver'     => 'local',
    'root'       => storage_path('app/private/products'),
    'visibility' => 'private',
    'throw'      => false,
],
```

### 4. Jalankan Migration & Seeder

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```

Buka [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔐 Akun Default (Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@digitalstore.com | password |
| Customer | budi@gmail.com | password |
| Customer | siti@gmail.com | password |

---

## 🔗 Daftar Route Utama

| Method | URL | Keterangan |
|---|---|---|
| GET | `/products` | Katalog produk |
| GET | `/products/{slug}` | Detail produk |
| GET | `/cart` | Keranjang belanja |
| POST | `/cart/add` | Tambah ke keranjang |
| GET | `/checkout` | Halaman checkout |
| POST | `/checkout/process` | Buat order |
| GET | `/orders` | Riwayat pesanan |
| GET | `/orders/{order}` | Detail pesanan + download |
| GET | `/download/{token}` | Download file produk |
| POST | `/webhook/payment` | Callback payment gateway |
| GET | `/admin/dashboard` | Dashboard admin |
| GET | `/admin/products` | Kelola produk |
| GET | `/admin/orders` | Kelola order |
| GET | `/admin/reviews` | Moderasi ulasan |

---

## 🔒 Keamanan

- File produk disimpan di disk **`private`** — tidak bisa diakses langsung via URL
- Download hanya bisa melalui `DownloadToken` yang unik, dibatasi 5x unduhan dan expired 30 hari
- Webhook endpoint divalidasi menggunakan **Signature Key** dari payment gateway (SHA512)
- Ulasan hanya bisa dikirim oleh user yang memiliki `order_item_id` valid (sudah membeli)
- Middleware `admin` memproteksi semua route `/admin/*`

---

## 🪝 Alur Webhook Pembayaran

```
User bayar via M-Banking / QRIS
        ↓
Payment Gateway (Midtrans) menerima konfirmasi
        ↓
Gateway POST ke /webhook/payment
        ↓
WebhookController validasi Signature Key
        ↓
PaymentLog disimpan (payload + signature_valid)
        ↓
Order status → PAID
        ↓
DownloadToken dibuat otomatis untuk setiap item
```

---

## 👥 Tim Pengembang

| Nama | NIM | Peran |
|---|---|---|
| | | Backend Developer |
| | | Frontend Developer |
| | | Database & API |
| | | Testing & Deployment |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan tugas akhir (UAS) mata kuliah **Informasi Bisnis**.

© 2026 Digital Store — All rights reserved.
