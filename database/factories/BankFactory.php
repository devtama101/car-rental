<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
{
    protected $model = Bank::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company().' Bank',
            'code' => fake()->numerify('###'),
            'number' => fake()->numerify('##########'),
            'account_holder' => fake()->name(),
        ];
    }
}
