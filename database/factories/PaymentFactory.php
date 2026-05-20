<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rental_id' => Rental::factory(),
            'amount' => $this->faker->numberBetween(200_000, 10_000_000),
            'method' => $this->faker->randomElement(PaymentMethod::cases())->value,
            'status' => $this->faker->randomElement(PaymentStatus::cases())->value,
            'date' => $this->faker->date(),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Paid->value,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Pending->value,
        ]);
    }
}
