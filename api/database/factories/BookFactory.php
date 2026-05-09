<?php

namespace Database\Factories;

use App\Models\Autor;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id'      => Autor::inRandomOrder()->first()->id,
            'title'          => $this->faker->sentence(3),
            'isbn'           => $this->faker->unique()->isbn13(),
            'genre'          => $this->faker->randomElement(['Novela', 'Ciencia ficción', 'Historia', 'Poesía', 'Terror']),
            'published_year' => $this->faker->numberBetween(1900, 2024),
            'stock'          => $this->faker->numberBetween(1, 20),
        ];
    }
}
