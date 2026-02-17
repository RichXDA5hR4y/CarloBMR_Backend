<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // 1. Kain Toraja
            [
                'category_id' => 1,
                'name' => 'Kain Toraja Motif Tongkonan',
                'description' => 'Kain khas Toraja dengan motif Tongkonan yang ikonik',
                'price' => 250000,
                'stock' => 20,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Kain+Toraja',
                'status' => 'active'
            ],
            // 2. Tenun Toraja
            [
                'category_id' => 2,
                'name' => 'Tenun Toraja Premium',
                'description' => 'Tenun tradisional dengan warna khas Toraja',
                'price' => 500000,
                'stock' => 15,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Tenun+Toraja',
                'status' => 'active'
            ],
            // 3. Baju Adat
            [
                'category_id' => 3,
                'name' => 'Baju Adat Toraja',
                'description' => 'Pakaian adat untuk acara formal',
                'price' => 750000,
                'stock' => 10,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Baju+Adat',
                'status' => 'active'
            ],
            // 4. Kalung Manik-Manik
            [
                'category_id' => 4,
                'name' => 'Kalung Manik-Manik Toraja',
                'description' => 'Aksesoris kalung tradisional',
                'price' => 150000,
                'stock' => 30,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Kalung',
                'status' => 'active'
            ],
            // 5. Blanket Toraja
            [
                'category_id' => 5,
                'name' => 'Blanket Toraja',
                'description' => 'Selimut dengan motif Toraja',
                'price' => 350000,
                'stock' => 25,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Blanket',
                'status' => 'active'
            ],
            // 6. Sepu' Toraja
            [
                'category_id' => 6,
                'name' => 'Sepu\' Toraja',
                'description' => 'Sepu\' khas Toraja untuk acara adat',
                'price' => 450000,
                'stock' => 12,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Sepu',
                'status' => 'active'
            ],
            // 7. Pasta Sablon (NEW)
            [
                'category_id' => 7,
                'name' => 'Pasta Sablon',
                'description' => 'Pasta sablon sesuai kebutuhan',
                'price' => 125000,
                'stock' => 35,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Pasta+Sablon',
                'status' => 'active'
            ],
            // 8. Lukisan Toraja (NEW)
            [
                'category_id' => 8,
                'name' => 'Lukisan',
                'description' => 'Lukisan-Lukisan baik yang tradisional Toraja maupun lainnya',
                'price' => 650000,
                'stock' => 8,
                'image_url' => 'https://via.placeholder.com/400x400/8B4513/FFFFFF?text=Lukisan',
                'status' => 'active'
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                [
                    'name' => $product['name'],
                    'category_id' => $product['category_id']
                ],
                $product
            );
        }
    }
}