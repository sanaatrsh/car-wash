<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WashType>
 */
class WashTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->company . ' Station',
                'ar' => 'محطة ' . $this->faker->company,
            ],
            'duration' => 45,
            'price' => $this->faker->randomFloat(2, 5, 50),
            'description' => [
                'en' => $this->faker->sentence(),
                'ar' => $this->faker->sentence(),
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
