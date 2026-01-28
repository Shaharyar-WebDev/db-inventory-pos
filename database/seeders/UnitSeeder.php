<?php

namespace Database\Seeders;

use App\Models\Master\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Piece', 'symbol' => 'Pc'],
            ['name' => 'Dozen', 'symbol' => 'dz'],
            ['name' => 'Meter', 'symbol' => 'mtr'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
