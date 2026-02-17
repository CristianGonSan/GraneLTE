<?php

namespace Database\Factories\Inventory;

use App\Models\Inventory\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->company,
            'contact_person'    => $this->faker->name,
            'email'             => $this->faker->unique()->safeEmail,
            'phone'             => $this->faker->phoneNumber,
            'address'           => $this->faker->address,
            'description'       => $this->faker->sentence,
            'is_active'         => $this->faker->boolean(80),
        ];
    }

    /**
     * Estado para proveedores inactivos
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
