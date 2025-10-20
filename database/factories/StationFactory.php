<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_station_id' => null,
            'name' => $this->faker->company . ' Station',
            'address' => $this->faker->address,
            'location_x' => $this->faker->latitude,
            'location_y' => $this->faker->longitude,
            'opening_time' => $this->faker->time('H:i'),
            'closing_time' => $this->faker->time('H:i'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
