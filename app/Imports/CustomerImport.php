<?php

namespace App\Imports;

use App\Models\Master\Area;
use App\Models\Master\City;
use App\Models\Master\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $area = null;
        $city = null;

        if (!empty($row['area'])) {
            $area = Area::with('city')
                ->where('name', trim($row['area']))
                ->first();

            $city = $area?->city;
        }

        if (!$area && !empty($row['city'])) {
            $city = City::where('name', trim($row['city']))->first();
        }

        return Customer::updateOrCreate([
            'name' => $row['name'],
        ], [
            'city_id' => $city?->id,
            'area_id' => $area?->id,
            'contact' => (string) $row['contact'] ?? null,
            'opening_balance' => $row['opening_balance'] ?? 0,
        ]);
    }
}
