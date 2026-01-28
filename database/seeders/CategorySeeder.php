<?php

namespace Database\Seeders;

use App\Models\Master\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fusing'],
            ['name' => 'ELastic'],
            ['name' => 'Button'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
