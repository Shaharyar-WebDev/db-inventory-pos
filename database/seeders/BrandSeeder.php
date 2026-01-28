<?php

namespace Database\Seeders;

use App\Models\Master\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Master'],
            ['name' => 'Gold'],
            ['name' => 'Nalkee'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
