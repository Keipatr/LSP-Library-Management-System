<?php

namespace Database\Factories;

use App\Models\Rental;
use App\Models\Books;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RentalDetail>
 */
class RentalDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rental_id' => Rental::factory(),
            'book_id' => Books::factory(),
        ];
    }
}
