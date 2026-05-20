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
            'name' => $this->faker->randomElement(['Toyota Avanza', 'Honda Brio', 'Suzuki Ertiga', 'Daihatsu Xenia', 'Toyota Innova', 'Honda HR-V', 'Mitsubishi Xpander', 'Toyota Rush']),
            'year' => $this->faker->numberBetween(2019, 2025),
            'transmission' => $this->faker->randomElement(TransmissionType::cases())->value,
            'license_plate' => strtoupper($this->faker->bothify('?? #### ?##')),
            'description' => $this->faker->sentence(),
            'image' => null,
            'rental_rate_per_day' => $this->faker->numberBetween(200_000, 1_500_000),
        ];
    }
}
