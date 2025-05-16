<?php

namespace Database\Factories;

use App\Models\Estudiante;
use App\Models\Paralelo;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstudianteFactory extends Factory
{
    protected $model = Estudiante::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'cedula' => $this->faker->unique()->numerify('##########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'paralelos' => Paralelo::factory(),
        ];
    }
}
