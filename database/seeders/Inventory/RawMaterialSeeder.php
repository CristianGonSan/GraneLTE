<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\RawMaterial;
use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RawMaterial::factory()->count(20)->create();
    }
}
