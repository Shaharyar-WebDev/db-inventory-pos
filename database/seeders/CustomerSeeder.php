<?php

namespace Database\Seeders;

use App\Enums\CustomerType;
use App\Models\Master\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Walk-in Customer',
            'customer_type' => CustomerType::WALK_IN->value,
            'photo' => 'images/customers/photo/customer.webp',
        ]);
    }
}
