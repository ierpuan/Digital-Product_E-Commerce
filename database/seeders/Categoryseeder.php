<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'E-Book',           'icon' => 'ti ti-book'],
            ['name' => 'Template Design',  'icon' => 'ti ti-layout'],
            ['name' => 'Source Code',      'icon' => 'ti ti-code'],
            ['name' => 'Video Course',     'icon' => 'ti ti-video'],
            ['name' => 'Audio & Music',    'icon' => 'ti ti-music'],
            ['name' => 'Preset & Filter',  'icon' => 'ti ti-adjustments'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
            ]);
        }
    }
}
