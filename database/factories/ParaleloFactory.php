<?php

namespace Database\Factories;

use App\Models\Paralelo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParaleloFactory extends Factory
{
    protected $model = Paralelo::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->word()
        ];
    }
}
