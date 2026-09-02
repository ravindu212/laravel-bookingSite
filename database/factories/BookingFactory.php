<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '+94 77 123 4567',
            'check_in' => now()->addWeek()->toDateString(),
            'check_out' => now()->addDays(10)->toDateString(),
            'guests' => fake()->numberBetween(1, 6),
            'message' => fake()->sentence(),
        ];
    }
}
