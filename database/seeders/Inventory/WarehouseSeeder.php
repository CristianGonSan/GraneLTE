<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::factory()->count(20)->create();
    }
}
