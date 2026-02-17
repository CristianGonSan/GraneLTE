<?php

namespace Database\Factories\Inventory;

use App\Models\Inventory\Warehouse as InventoryWarehouse;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = InventoryWarehouse::class;

    public function definition(): array
    {
        return [
            'name'          => $this->faker->company . ' Warehouse',
            'location'      => $this->faker->address,
            'description'   => $this->faker->sentence(10),
            'is_active'     => $this->faker->boolean(80),
        ];
    }
}
