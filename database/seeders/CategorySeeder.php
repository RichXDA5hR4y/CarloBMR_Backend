<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // 1. Kain
            [
                'name' => 'Kain',
                'description' => 'Kain khas Toraja'
            ],
            // 2. Tenun
            [
                'name' => 'Tenun',
                'description' => 'Tenun tradisional Toraja'
            ],
            // 3. Pakaian
            [
                'name' => 'Pakaian',
                'description' => 'Pakaian adat & modern'
            ],
            // 4. Aksesoris
            [
                'name' => 'Aksesoris',
                'description' => 'Aksesoris pendukung'
            ],
            // 5. Blanket
            [
                'name' => 'Blanket',
                'description' => 'Selimut & blanket'
            ],
            // 6. Sepu'
            [
                'name' => 'Sepu\'',
                'description' => 'Sepu\' khas Toraja'
            ],
            // 7. Pasta Sablon (NEW)
            [
                'name' => 'Pasta Sablon',
                'description' => 'Bahan sablon khas maupun non-khas Toraja'
            ],
            // 8. Lukisan (NEW)
            [
                'name' => 'Lukisan',
                'description' => 'Lukisan-Lukisan baik yang tradisional Toraja maupun lainnya'
            ]
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}