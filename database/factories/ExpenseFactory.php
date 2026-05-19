<?php

namespace Database\Factories;

use App\Enums\ExpenseCategory;
use App\Models\Expense;
use App\Models\Person;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => fake()->numberBetween(100_000, 10_000_000),
            'category' => fake()->randomElement(ExpenseCategory::cases())->value,
            'description' => fake()->optional()->sentence(),
            'date' => fake()->dateTimeBetween('-3 months', 'now'),
            'proof_file_path' => null,
            'person_id' => null,
            'vehicle_id' => null,
            'created_by' => User::factory(),
        ];
    }

    public function forVehicle(Vehicle $vehicle): static
    {
        return $this->state(fn () => [
            'vehicle_id' => $vehicle->id,
            'category' => fake()->randomElement([
                ExpenseCategory::Maintenance,
                ExpenseCategory::VehicleTax,
                ExpenseCategory::VehicleInsurance,
                ExpenseCategory::Fuel,
                ExpenseCategory::Cleaning,
                ExpenseCategory::SpareParts,
            ])->value,
        ]);
    }

    public function forPerson(Person $person): static
    {
        return $this->state(fn () => [
            'person_id' => $person->id,
            'category' => fake()->randomElement([
                ExpenseCategory::EmployeeSalary,
                ExpenseCategory::DriverWage,
                ExpenseCategory::Overtime,
                ExpenseCategory::Bonus,
                ExpenseCategory::THR,
            ])->value,
        ]);
    }

    public function forCategory(ExpenseCategory $category): static
    {
        return $this->state(fn () => [
            'category' => $category->value,
        ]);
    }
}
