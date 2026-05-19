<?php

namespace Database\Factories;

use App\Enums\RentalStatus;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_amount' => fake()->numberBetween(200_000, 10_000_000),
            'status' => fake()->randomElement(RentalStatus::cases())->value,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RentalStatus::Pending->value,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RentalStatus::Active->value,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RentalStatus::Completed->value,
        ]);
    }
}
