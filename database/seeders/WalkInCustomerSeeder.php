<?php

namespace Database\Seeders;

use App\Enums\CustomerType;
use App\Models\Master\Customer;
use Illuminate\Database\Seeder;

class WalkInCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Walk-in Customer',
            'phone' => null,
            'email' => null,
            'address' => null,
            'customer_type' => CustomerType::WALK_IN,
        ]);
    }
}
