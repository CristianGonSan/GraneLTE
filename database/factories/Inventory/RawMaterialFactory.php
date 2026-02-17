<?php

namespace Database\Factories\Inventory;

use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\Unit;
use App\Models\Inventory\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class RawMaterialFactory extends Factory
{
    protected $model = RawMaterial::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'abbreviation'  => strtoupper($this->faker->unique()->lexify('RM-?????')),
            'description'   => $this->faker->optional()->sentence(),
            'minimum_stock' => $this->faker->randomFloat(3, 0, 100),
            'unit_id'       => Unit::query()->inRandomOrder()->value('id'),
            'category_id'   => Category::query()->inRandomOrder()->value('id'),
            'is_active'     => $this->faker->boolean(80),
        ];
    }
}
