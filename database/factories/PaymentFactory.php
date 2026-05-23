<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
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
            'date' => $this->faker->date(),
        ];
    }
}
