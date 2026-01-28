<?php

namespace Database\Seeders;

use App\Models\Master\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => '1.25 inch elastic',
                'code' => 'ELAST-001',
                'description' => 'High quality 1.25 inch wide elastic for clothing',
                'unit_id' => 3, // Meter
                'category_id' => 2, // Clothing
                'brand_id' => 1, // Brand A
                'cost_price' => 50.00,
                'selling_price' => 75.00,
                'tags' => ['elastic', 'clothing', 'fabric'],
            ],
            [
                'name' => 'Shirt Buttons',
                'code' => 'BTN-001',
                'description' => 'Premium shirt buttons, assorted colors',
                'unit_id' => 1, // Piece
                'category_id' => 2, // Clothing
                'brand_id' => 2, // Brand B
                'cost_price' => 10.00,
                'selling_price' => 20.00,
                'tags' => ['buttons', 'clothing', 'accessories'],
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
