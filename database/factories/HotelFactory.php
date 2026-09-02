<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Ella Misty Garden Stay',
                'Galle Fort Courtyard Villa',
                'Kandy Lake View Rest',
            ]),
            'description' => fake()->sentence(12),
            'image_url' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Nine%20Arches%20Bridge%20in%20Ella.jpg',
            'location' => fake()->randomElement([
                'Ella, Uva Province',
                'Galle Fort, Southern Province',
                'Kandy, Central Province',
            ]),
            'phone' => '+94 77 123 4567',
            'email' => fake()->safeEmail(),
            'website' => 'https://www.srilanka.travel/',
        ];
    }
}
