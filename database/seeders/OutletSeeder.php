<?php

namespace Database\Seeders;

use App\Models\Outlet\Outlet;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outlets = [
            [
                'name' => 'Landhi',
                'address' => '123 Main Street, Downtown',
                'phone_number' => '+92-300-1234567',
                'is_active' => true,
                'attachments' => null,
            ],
            [
                'name' => 'Korangi',
                'address' => '456 North Avenue, North Side',
                'phone_number' => '+92-300-2345678',
                'is_active' => true,
                'attachments' => null,
            ],
            [
                'name' => 'Saddar',
                'address' => '789 South Road, South District',
                'phone_number' => '+92-300-3456789',
                'is_active' => true,
                'attachments' => null,
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }
    }
}
