<?php

namespace Database\Seeders;

use App\Enums\ExpenseCategory;
use App\Enums\PaymentMethod;
use App\Enums\PersonType;
use App\Enums\RentalStatus;
use App\Enums\TransmissionType;
use App\Models\Bank;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ─── Banks (idempotent) ─────────────────────────────
        $bankData = [
            ['name' => 'BCA', 'code' => '014', 'number' => '1234567890', 'account_holder' => 'Car Rental Indonesia'],
            ['name' => 'Bank Mandiri', 'code' => '008', 'number' => '0987654321', 'account_holder' => 'Car Rental Indonesia'],
            ['name' => 'BNI', 'code' => '009', 'number' => '1122334455', 'account_holder' => 'Car Rental Indonesia'],
            ['name' => 'BRI', 'code' => '002', 'number' => '5566778899', 'account_holder' => 'Car Rental Indonesia'],
        ];

        foreach ($bankData as $data) {
            Bank::firstOrCreate(
                ['code' => $data['code'], 'number' => $data['number']],
                $data,
            );
        }

        $banks = Bank::all();

        // ─── Users & People (4 core users) ──────────────────────
        $defaultUsers = [
            ['name' => 'Super Admin User', 'email' => 'superadmin@example.com', 'type' => PersonType::SuperAdmin],
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'type' => PersonType::Admin],
            ['name' => 'Employee User', 'email' => 'employee@example.com', 'type' => PersonType::Employee],
            ['name' => 'Customer User', 'email' => 'customer@example.com', 'type' => PersonType::Customer],
        ];

        foreach ($defaultUsers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ],
            );

            Person::updateOrCreate(
                ['user_id' => $user->id],
                ['type' => $data['type']->value],
            );
        }

        $superAdmin = User::where('email', 'superadmin@example.com')->first();
        $customer = User::where('email', 'customer@example.com')->first();

        // ─── Vehicles (idempotent) ──────────────────────────────
        $vehicleData = [
            ['name' => 'Toyota Avanza', 'year' => 2024, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 1234 ABC', 'rental_rate_per_day' => 350_000],
            ['name' => 'Toyota Innova', 'year' => 2023, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 2345 BCD', 'rental_rate_per_day' => 650_000],
            ['name' => 'Honda Brio', 'year' => 2024, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 3456 CDE', 'rental_rate_per_day' => 300_000],
            ['name' => 'Suzuki Ertiga', 'year' => 2022, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 4567 DEF', 'rental_rate_per_day' => 400_000],
            ['name' => 'Daihatsu Xenia', 'year' => 2023, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 5678 EFG', 'rental_rate_per_day' => 375_000],
            ['name' => 'Honda HR-V', 'year' => 2024, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 6789 FGH', 'rental_rate_per_day' => 700_000],
            ['name' => 'Mitsubishi Xpander', 'year' => 2023, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 7890 GHI', 'rental_rate_per_day' => 425_000],
            ['name' => 'Toyota Rush', 'year' => 2022, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 8901 HIJ', 'rental_rate_per_day' => 400_000],
            ['name' => 'Honda Civic', 'year' => 2025, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 9012 IJK', 'rental_rate_per_day' => 800_000],
            ['name' => 'Toyota Fortuner', 'year' => 2024, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 0123 JKL', 'rental_rate_per_day' => 1_200_000],
            ['name' => 'Suzuki Baleno', 'year' => 2023, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 1122 KLM', 'rental_rate_per_day' => 275_000],
            ['name' => 'Wuling Confero', 'year' => 2024, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 2233 LMN', 'rental_rate_per_day' => 325_000],
            ['name' => 'Mazda CX-5', 'year' => 2025, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 3344 MNO', 'rental_rate_per_day' => 1_000_000],
            ['name' => 'Daihatsu Terios', 'year' => 2022, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 4455 NOP', 'rental_rate_per_day' => 350_000],
            ['name' => 'Toyota Camry', 'year' => 2024, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 5566 OPQ', 'rental_rate_per_day' => 950_000],
            ['name' => 'Nissan Livina', 'year' => 2023, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 6677 PQR', 'rental_rate_per_day' => 380_000],
            ['name' => 'Hyundai Creta', 'year' => 2025, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 7788 QRS', 'rental_rate_per_day' => 550_000],
            ['name' => 'Suzuki Ignis', 'year' => 2024, 'transmission' => TransmissionType::Manual, 'license_plate' => 'B 8899 RST', 'rental_rate_per_day' => 300_000],
            ['name' => 'MG ZS', 'year' => 2023, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 9900 STU', 'rental_rate_per_day' => 450_000],
            ['name' => 'Toyota Alphard', 'year' => 2025, 'transmission' => TransmissionType::Automatic, 'license_plate' => 'B 0011 TUV', 'rental_rate_per_day' => 1_500_000],
        ];

        foreach ($vehicleData as $data) {
            Vehicle::firstOrCreate(
                ['license_plate' => $data['license_plate']],
                [
                    'name' => $data['name'],
                    'year' => $data['year'],
                    'transmission' => $data['transmission']->value,
                    'description' => $this->faker()->sentence(),
                    'rental_rate_per_day' => $data['rental_rate_per_day'],
                    'image' => null,
                ],
            );
        }

        $vehicles = Vehicle::all();

        // ─── Rentals (only when empty) ──────────────────────────
        if (Rental::count() > 0) {
            $this->summary();

            return;
        }

        $createRental = function (
            Vehicle $vehicle,
            RentalStatus $status,
            string $startOffset,
            int $days,
            ?PaymentMethod $paymentMethod = null,
        ) use ($customer, $banks) {
            $startDate = Carbon::parse($startOffset);
            $endDate = $startDate->copy()->addDays($days);
            $delivery = $this->faker()->randomElement(['pickup', 'delivery']);
            $totalAmount = $vehicle->rental_rate_per_day * $days;

            $rental = Rental::factory()->create([
                'user_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'rental_rate_per_day' => $vehicle->rental_rate_per_day,
                'status' => $status->value,
                'total_amount' => $totalAmount,
                'delivery_method' => $delivery,
                'delivery_address' => $delivery === 'delivery' ? $this->faker()->address() : null,
            ]);

            if ($paymentMethod) {
                $method = $paymentMethod->value;
                $isTransfer = $method === PaymentMethod::Transfer->value;

                Payment::factory()->create([
                    'rental_id' => $rental->id,
                    'amount' => $totalAmount,
                    'method' => $method,
                    'bank_id' => $isTransfer ? $banks->random()->id : null,
                    'date' => $startDate->toDateString(),
                ]);
            }
        };

        $createRental($vehicles[0], RentalStatus::Pending, now()->addDays(1)->toDateTimeString(), 3);
        $createRental($vehicles[1], RentalStatus::Confirmed, now()->addDays(3)->toDateTimeString(), 4, PaymentMethod::Transfer);
        $createRental($vehicles[2], RentalStatus::Active, now()->subDays(2)->toDateTimeString(), 5, PaymentMethod::Cash);
        $createRental($vehicles[3], RentalStatus::Active, now()->subDays(1)->toDateTimeString(), 3, PaymentMethod::Transfer);
        $createRental($vehicles[4], RentalStatus::Completed, now()->subDays(14)->toDateTimeString(), 5, PaymentMethod::Transfer);
        $createRental($vehicles[5], RentalStatus::Completed, now()->subDays(7)->toDateTimeString(), 3, PaymentMethod::Cash);
        $createRental($vehicles[6], RentalStatus::Cancelled, now()->subDays(5)->toDateTimeString(), 2);
        $createRental($vehicles[7], RentalStatus::Cancelled, now()->subDays(10)->toDateTimeString(), 4);

        // ─── Expenses (only when empty) ─────────────────────────
        if (Expense::count() > 0) {
            $this->summary();

            return;
        }

        $adminPerson = Person::whereHas('user', fn ($q) => $q->where('email', 'admin@example.com'))->first();

        Expense::create([
            'amount' => 8_000_000,
            'category' => ExpenseCategory::EmployeeSalary->value,
            'description' => 'Monthly salary — Admin User',
            'date' => Carbon::now()->startOfMonth(),
            'person_id' => $adminPerson->id,
            'created_by' => $superAdmin->id,
        ]);

        Expense::create([
            'amount' => 5_000_000,
            'category' => ExpenseCategory::OfficeRent->value,
            'description' => 'Monthly office rent',
            'date' => Carbon::now()->startOfMonth(),
            'created_by' => $superAdmin->id,
        ]);

        Expense::create([
            'amount' => 850_000,
            'category' => ExpenseCategory::Utilities->value,
            'description' => 'Electricity & internet',
            'date' => Carbon::now()->startOfMonth()->addDays(5),
            'created_by' => $superAdmin->id,
        ]);

        Expense::create([
            'amount' => 1_200_000,
            'category' => ExpenseCategory::Maintenance->value,
            'description' => 'Service — Toyota Avanza',
            'date' => Carbon::now()->startOfMonth()->addDays(3),
            'vehicle_id' => $vehicles[0]->id,
            'created_by' => $superAdmin->id,
        ]);

        Expense::create([
            'amount' => 2_800_000,
            'category' => ExpenseCategory::VehicleTax->value,
            'description' => 'Annual tax — '.$vehicles[1]->name.' ('.$vehicles[1]->license_plate.')',
            'date' => Carbon::now()->startOfMonth()->addDays(2),
            'vehicle_id' => $vehicles[1]->id,
            'created_by' => $superAdmin->id,
        ]);

        Expense::create([
            'amount' => 150_000,
            'category' => ExpenseCategory::Fuel->value,
            'description' => 'Fuel — '.$vehicles[2]->name,
            'date' => Carbon::now()->startOfMonth()->addDays(8),
            'vehicle_id' => $vehicles[2]->id,
            'created_by' => $superAdmin->id,
        ]);

        Expense::create([
            'amount' => 600_000,
            'category' => ExpenseCategory::Marketing->value,
            'description' => 'Social media ads',
            'date' => Carbon::now()->startOfMonth()->addDays(10),
            'created_by' => $superAdmin->id,
        ]);

        $this->summary();
    }

    private function faker(): Generator
    {
        return fake();
    }

    private function summary(): void
    {
        $this->command?->info('Seed complete!');
        $this->command?->info('Users: '.User::count().' | People: '.Person::count().' | Vehicles: '.Vehicle::count());
        $this->command?->info('Rentals: '.Rental::count().' | Payments: '.Payment::count().' | Banks: '.Bank::count());
        $this->command?->info('Expenses: '.Expense::count());
    }
}
