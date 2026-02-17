<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Responsible;
use Illuminate\Database\Seeder;

class ResponsibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Responsible::factory()->count(20)->create();
    }
}
