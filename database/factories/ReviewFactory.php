<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'location' => fake()->randomElement([
                'Colombo, Sri Lanka',
                'Kandy, Sri Lanka',
                'Galle, Sri Lanka',
                'Ella, Sri Lanka',
            ]),
            'rating' => fake()->numberBetween(4, 5),
            'comment' => fake()->sentence(14),
            'is_approved' => false,
        ];
    }
}
