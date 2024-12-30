<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Books>
 */
class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'author' => $this->faker->name,
            'publisher' => $this->faker->company,
            'publication_year' => $this->faker->year,
            'isbn' => $this->faker->isbn13,
            'stock' => $this->faker->numberBetween(0, 10),
            'status_delete' => $this->faker->randomElement(['0', '1']), // 0 = active, 1 = deleted
        ];
    }
}
