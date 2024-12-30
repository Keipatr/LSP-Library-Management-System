<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'borrowed_at' => $this->faker->dateTimeThisYear(),
            'due_date' => Carbon::now()->addDays(7),
            'returned_at' => $this->faker->optional()->dateTimeThisYear(),
            'rental_status' => $this->faker->randomElement(['0', '1']), // 0 = borrowed, 1 = returned
            'status_delete' => $this->faker->randomElement(['0', '1']), // 0 = active, 1 = deleted
        ];
    }
}
