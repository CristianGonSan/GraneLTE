<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units =
            [
                [
                    'symbol' => 'kg',
                    'name' => 'Kilogramo'
                ],
                [
                    'symbol' => 'g',
                    'name' => 'Gramo'
                ],
                [
                    'symbol' => 'lb',
                    'name' => 'Libra'
                ],
                [
                    'symbol' => 'oz',
                    'name' => 'Onza'
                ],
                [
                    'symbol' => 'l',
                    'name' => 'Litro'
                ],
                [
                    'symbol' => 'ml',
                    'name' => 'Mililitro'
                ],
                [
                    'symbol' => 't',
                    'name' => 'Tonelada'
                ],
                [
                    'symbol' => 'mg',
                    'name' => 'Miligramo'
                ],
                [
                    'symbol' => 'm³',
                    'name' => 'Metro cúbico'
                ],
                [
                    'symbol' => 'gal',
                    'name' => 'Galón'
                ]
            ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['symbol' => $unit['symbol']], $unit);
        }
    }
}
