<?php

namespace Database\Seeders;

use App\Models\Inventory\Responsible;
use Database\Seeders\Inventory\CategorySeeder;
use Database\Seeders\Inventory\RawMaterialSeeder;
use Database\Seeders\Inventory\SupplierSeeder;
use Database\Seeders\Inventory\UnitSeeder;
use Database\Seeders\Inventory\WarehouseSeeder;
use Database\Seeders\Inventory\ResponsibleSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            //AdminSeeder::class,
            //UnitSeeder::class,
            //CategorySeeder::class,
            //SupplierSeeder::class,
            //WarehouseSeeder::class,
            //ResponsibleSeeder::class
            RolesAndPermissionsSeeder::class
        ]);
    }
}
