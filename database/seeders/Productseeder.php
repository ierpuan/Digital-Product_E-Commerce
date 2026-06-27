<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Buat file dummy di storage private agar download tidak error
        $this->createDummyFiles();

        $products = [
            // E-Book
            [
                'category' => 'E-Book',
                'name'     => 'Panduan Lengkap Laravel 12 untuk Pemula',
                'desc'     => 'Belajar Laravel dari nol hingga deploy. Mencakup Eloquent, Blade, API, dan autentikasi.',
                'price'    => 79000,
                'file'     => 'products/ebook-laravel.pdf',
                'size'     => 4200,
                'type'     => 'pdf',
                'sales'    => 128,
            ],
            [
                'category' => 'E-Book',
                'name'     => 'Buku Panduan UI/UX Design Thinking',
                'desc'     => 'Pelajari prinsip desain produk digital yang berpusat pada pengguna.',
                'price'    => 65000,
                'file'     => 'products/ebook-uiux.pdf',
                'size'     => 3100,
                'type'     => 'pdf',
                'sales'    => 87,
            ],
            [
                'category' => 'E-Book',
                'name'     => 'Strategi Pemasaran Digital UMKM 2024',
                'desc'     => 'Panduan praktis marketing digital untuk bisnis kecil dan menengah.',
                'price'    => 55000,
                'file'     => 'products/ebook-marketing.pdf',
                'size'     => 2800,
                'type'     => 'pdf',
                'sales'    => 54,
            ],

            // Template Design
            [
                'category' => 'Template Design',
                'name'     => 'Paket Template Presentasi Bisnis Premium',
                'desc'     => '30 slide Powerpoint & Keynote siap pakai untuk pitch deck profesional.',
                'price'    => 120000,
                'file'     => 'products/template-ppt.zip',
                'size'     => 18500,
                'type'     => 'zip',
                'sales'    => 203,
            ],
            [
                'category' => 'Template Design',
                'name'     => 'Template CV & Resume Minimalis',
                'desc'     => '10 desain CV modern format Word & Canva, siap edit langsung.',
                'price'    => 45000,
                'file'     => 'products/template-cv.zip',
                'size'     => 9200,
                'type'     => 'zip',
                'sales'    => 315,
            ],

            // Source Code
            [
                'category' => 'Source Code',
                'name'     => 'Sistem POS (Point of Sale) Laravel + Vue.js',
                'desc'     => 'Source code kasir lengkap: produk, transaksi, laporan, multi-user.',
                'price'    => 350000,
                'file'     => 'products/source-pos.zip',
                'size'     => 42000,
                'type'     => 'zip',
                'sales'    => 67,
            ],
            [
                'category' => 'Source Code',
                'name'     => 'REST API Starter Kit Laravel 12',
                'desc'     => 'Boilerplate API dengan autentikasi Sanctum, role & permission, dokumentasi Swagger.',
                'price'    => 180000,
                'file'     => 'products/source-api.zip',
                'size'     => 8700,
                'type'     => 'zip',
                'sales'    => 94,
            ],

            // Video Course
            [
                'category' => 'Video Course',
                'name'     => 'Full Stack Web Development: React + Node.js',
                'desc'     => '12 jam video pembelajaran membangun aplikasi full stack dari nol.',
                'price'    => 299000,
                'file'     => 'products/course-react-node.zip',
                'size'     => 3200000,
                'type'     => 'zip',
                'sales'    => 156,
            ],
            [
                'category' => 'Video Course',
                'name'     => 'Belajar Ilustrasi Digital dengan Procreate',
                'desc'     => '8 jam tutorial menggambar digital untuk pemula hingga mahir.',
                'price'    => 199000,
                'file'     => 'products/course-procreate.zip',
                'size'     => 2100000,
                'type'     => 'zip',
                'sales'    => 211,
            ],

            // Audio & Music
            [
                'category' => 'Audio & Music',
                'name'     => 'Paket Sound Effect Cinematic 200+',
                'desc'     => '200+ sound effect berkualitas tinggi untuk video, game, dan podcast.',
                'price'    => 95000,
                'file'     => 'products/sfx-cinematic.zip',
                'size'     => 520000,
                'type'     => 'zip',
                'sales'    => 178,
            ],

            // Preset & Filter
            [
                'category' => 'Preset & Filter',
                'name'     => 'Lightroom Preset Pack — Moody Vintage',
                'desc'     => '50 preset Lightroom untuk foto dengan nuansa vintage moody aesthetic.',
                'price'    => 85000,
                'file'     => 'products/preset-moody.zip',
                'size'     => 3400,
                'type'     => 'zip',
                'sales'    => 429,
            ],
            [
                'category' => 'Preset & Filter',
                'name'     => 'Filter CapCut Viral Pack 2024',
                'desc'     => '25 template CapCut trending siap pakai untuk konten kreator.',
                'price'    => 35000,
                'file'     => 'products/filter-capcut.zip',
                'size'     => 7800,
                'type'     => 'zip',
                'sales'    => 892,
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();

            Product::create([
                'category_id' => $category->id,
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => $data['desc'],
                'price'       => $data['price'],
                'thumbnail'   => null,
                'file_path'   => $data['file'],
                'file_size'   => $data['size'],
                'file_type'   => $data['type'],
                'is_active'   => true,
                'total_sales' => $data['sales'],
            ]);
        }
    }

    private function createDummyFiles(): void
    {
        $files = [
            'products/ebook-laravel.pdf',
            'products/ebook-uiux.pdf',
            'products/ebook-marketing.pdf',
            'products/template-ppt.zip',
            'products/template-cv.zip',
            'products/source-pos.zip',
            'products/source-api.zip',
            'products/course-react-node.zip',
            'products/course-procreate.zip',
            'products/sfx-cinematic.zip',
            'products/preset-moody.zip',
            'products/filter-capcut.zip',
        ];

        foreach ($files as $file) {
            if (!Storage::disk('private')->exists($file)) {
                Storage::disk('private')->put($file, 'DUMMY FILE — ' . $file);
            }
        }
    }
}
