<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Station;
use App\Models\WashType;
use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $washType = WashType::inRandomOrder()->first() ?? WashType::factory()->create();

        $start = $this->faker->dateTimeBetween('now', '+1 week');
        $end = (clone $start)->modify("+{$washType->duration} minutes");

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'station_id' => Station::inRandomOrder()->first()?->id ?? Station::factory(),
            'wash_type_id' => $washType->id,
            'date' => $start->format('Y-m-d'),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'status' => $this->faker->randomElement(array_map(fn($c) => $c->value, BookingStatusEnum::cases())),
        ];
    }
}
