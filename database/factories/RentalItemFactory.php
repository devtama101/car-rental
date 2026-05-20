<?php

namespace Database\Factories;

use App\Enums\RentalItemStatus;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RentalItem>
 */
class RentalItemFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 week', '+1 week');

        return [
            'rental_id' => Rental::factory(),
            'vehicle_id' => Vehicle::factory(),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate, '+2 weeks'),
            'driver_id' => null,
            'driver_fee_per_day' => null,
            'rental_rate_per_day' => $this->faker->numberBetween(200_000, 1_500_000),
            'status' => $this->faker->randomElement(RentalItemStatus::cases())->value,
        ];
    }

    public function withDriver(): static
    {
        return $this->state(fn (array $attributes) => [
            'driver_fee_per_day' => $this->faker->numberBetween(50_000, 200_000),
        ]);
    }
}
