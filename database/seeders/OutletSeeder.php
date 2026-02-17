<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Outlet\Outlet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'status' => Status::ACTIVE->value,
                'attachments' => null,
            ],
            [
                'name' => 'Korangi',
                'address' => '456 North Avenue, North Side',
                'phone_number' => '+92-300-2345678',
                'status' => Status::ACTIVE->value,
                'attachments' => null,
            ],
            [
                'name' => 'Saddar',
                'address' => '789 South Road, South District',
                'phone_number' => '+92-300-3456789',
                'status' => Status::ACTIVE->value,
                'attachments' => null,
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }
    }
}
