<?php

namespace Database\Factories;

use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    public function definition(): array
    {
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(1, 14));

        return [
            'booking_reference' => 'BRK-'.strtoupper(Str::random(7)),
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'start_date' => $startDate,
            'end_date' => $startDate->copy()->addDays($this->faker->numberBetween(1, 7)),
            'rental_rate_per_day' => $this->faker->numberBetween(200_000, 1_500_000),
            'total_amount' => $this->faker->numberBetween(200_000, 10_000_000),
            'status' => 'pending',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
