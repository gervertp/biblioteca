<?php

namespace Database\Factories;

use App\Models\Autor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Autor>
 */
class AutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'nationality' => $this->faker->country(),
            'birth_date' => $this->faker->date(),
            'bio' => $this->faker->paragraph(),
        ];
    }
}
