<?php

namespace Database\Factories;

use App\Enums\TransmissionType;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Toyota Avanza', 'Honda Brio', 'Suzuki Ertiga', 'Daihatsu Xenia', 'Toyota Innova', 'Honda HR-V', 'Mitsubishi Xpander', 'Toyota Rush']),
            'year' => fake()->numberBetween(2019, 2025),
            'transmission' => fake()->randomElement(TransmissionType::cases())->value,
            'license_plate' => strtoupper(fake()->bothify('?? #### ?##')),
            'description' => fake()->sentence(),
            'image' => null,
            'rental_rate_per_day' => fake()->numberBetween(200_000, 1_500_000),
        ];
    }
}
