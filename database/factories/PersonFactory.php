<?php

namespace Database\Factories;

use App\Enums\IdType;
use App\Enums\PersonType;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(PersonType::cases())->value,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'id_type' => fake()->randomElement(IdType::cases())->value,
            'id_file_path' => null,
        ];
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PersonType::Customer->value,
        ]);
    }

    public function employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PersonType::Employee->value,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PersonType::SuperAdmin->value,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PersonType::Admin->value,
        ]);
    }

    public function driver(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PersonType::Driver->value,
            'driver_fee_per_day' => fake()->numberBetween(50_000, 200_000),
        ]);
    }
}
