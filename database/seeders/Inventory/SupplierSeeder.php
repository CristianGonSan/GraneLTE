<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::factory()->count(20)->create();
    }
}
