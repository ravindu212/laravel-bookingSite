<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\HotelInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HotelInventory>
 */
class HotelInventoryFactory extends Factory
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
            'category' => fake()->randomElement(['Foods', 'Package', 'Transport']),
            'menu_type' => fake()->randomElement(['Breakfast', 'Lunch', 'Dinner', 'Package']),
            'name' => fake()->randomElement(['Sri Lankan breakfast', 'Airport pickup', 'Safari tour']),
            'description' => fake()->sentence(10),
            'price' => fake()->numberBetween(1500, 25000),
            'people_count' => fake()->numberBetween(1, 6),
        ];
    }
}
