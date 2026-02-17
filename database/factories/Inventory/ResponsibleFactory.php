<?php

namespace Database\Factories\Inventory;

use App\Models\Inventory\Responsible;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResponsibleFactory extends Factory
{
    protected $model = Responsible::class;

    public function definition()
    {
        return [
            'name'       => $this->faker->name,
            'identifier' => $this->faker->unique()->bothify('ID-####'),
            'position'   => $this->faker->jobTitle,
            'department' => $this->faker->randomElement([
                'Administración',
                'Finanzas',
                'Recursos Humanos',
                'Operaciones',
                'Sistemas'
            ]),
            'phone'      => $this->faker->phoneNumber,
            'email'      => $this->faker->unique()->safeEmail,
            'is_active'  => $this->faker->boolean(80),
        ];
    }
}
