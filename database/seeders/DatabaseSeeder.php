<?php

namespace Database\Seeders;

use App\Enums\ExpenseCategory;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PersonType;
use App\Enums\RentalItemStatus;
use App\Enums\RentalStatus;
use App\Enums\TransmissionType;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\User;
use App\Models\Vehicle;
use Faker\Generator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ─── Users & People (idempotent) ────────────────────────
        $defaultUsers = [
            ['name' => 'Super Admin User', 'email' => 'superadmin@example.com', 'type' => PersonType::SuperAdmin],
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'type' => PersonType::Admin],
            ['name' => 'Employee User', 'email' => 'employee@example.com', 'type' => PersonType::Employee],
            ['name' => 'Customer User', 'email' => 'customer@example.com', 'type' => PersonType::Customer],
        ];

        foreach ($defaultUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email_verified_at' => now(),
                    'password' => 'password',
                ],
            );

            Person::firstOrCreate(
                ['user_id' => $user->id],
                ['type' => $data['type']->value],
            );
        }

        // Extra employees (idempotent — fixed emails)
        for ($i = 2; $i <= 3; $i++) {
            $email = "employee{$i}@example.com";

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Employee {$i}",
                    'email_verified_at' => now(),
                    'password' => 'password',
                ],
            );

            Person::firstOrCreate(
                ['user_id' => $user->id],
                ['type' => PersonType::Employee->value],
            );
        }

        // Extra customers (idempotent — fixed emails)
        for ($i = 2; $i <= 11; $i++) {
            $email = "customer{$i}@example.com";

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Customer {$i}",
                    'email_verified_at' => now(),
                    'password' => 'password',
                ],
            );

            Person::firstOrCreate(
                ['user_id' => $user->id],
                ['type' => PersonType::Customer->value],
            );
        }

        // Drivers (idempotent — fixed emails)
        for ($i = 1; $i <= 5; $i++) {
            $email = "driver{$i}@example.com";

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Driver {$i}",
                    'email_verified_at' => now(),
                    'password' => 'password',
                ],
            );

            Person::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'type' => PersonType::Driver->value,
                    'driver_fee_per_day' => $this->driverFee($i),
                ],
            );
        }

        // Resolve seeded records into collections for downstream use
        $customers = User::whereIn('email', array_merge(
            ['customer@example.com'],
            array_map(fn ($i) => "customer{$i}@example.com", range(2, 11)),
        ))->get();

        $employees = User::whereIn('email', array_merge(
            ['employee@example.com'],
            ['employee2@example.com', 'employee3@example.com'],
        ))->get();

        $drivers = Person::where('type', PersonType::Driver->value)
            ->whereHas('user', fn ($q) => $q->where('email', 'like', 'driver%@example.com'))
            ->get();

        // ─── Vehicles (idempotent — license_plate is unique) ────
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

        // ─── Rentals (gate: only seed when empty) ────────────────
        if (Rental::count() > 0) {
            $this->summary();

            return;
        }

        // Helper to create a rental with items & payments
        $rentals = collect();

        $createRental = function (
            User $customer,
            RentalStatus $rentalStatus,
            array $itemConfigs,
            ?array $paymentConfig = null,
        ) use (&$rentals) {
            $delivery = $this->faker()->randomElement(['pickup', 'delivery']);

            $totalAmount = 0;
            foreach ($itemConfigs as $cfg) {
                $days = $cfg['days'];
                $totalAmount += $cfg['rate'] * $days + ($cfg['driver_fee'] ?? 0) * $days;
            }

            $rental = Rental::factory()->create([
                'user_id' => $customer->id,
                'status' => $rentalStatus->value,
                'total_amount' => $totalAmount,
                'delivery_method' => $delivery,
                'delivery_address' => $delivery === 'delivery' ? $this->faker()->address() : null,
            ]);

            $rentals->push($rental);

            foreach ($itemConfigs as $cfg) {
                $startDate = Carbon::parse($cfg['start']);
                $endDate = $startDate->copy()->addDays($cfg['days']);
                $itemStatus = $cfg['item_status'] ?? RentalItemStatus::Rented;

                RentalItem::factory()->create([
                    'rental_id' => $rental->id,
                    'vehicle_id' => $cfg['vehicle']->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'driver_id' => ($cfg['driver'] ?? null)?->id,
                    'driver_fee_per_day' => $cfg['driver_fee'] ?? null,
                    'rental_rate_per_day' => $cfg['rate'],
                    'status' => $itemStatus->value,
                ]);
            }

            if ($paymentConfig) {
                Payment::factory()->create([
                    'rental_id' => $rental->id,
                    'amount' => $paymentConfig['amount'] ?? $totalAmount,
                    'method' => ($paymentConfig['method'] ?? PaymentMethod::Cash)->value,
                    'status' => ($paymentConfig['status'] ?? PaymentStatus::Paid)->value,
                    'date' => $paymentConfig['date'] ?? now()->toDateString(),
                ]);
            }
        };

        // ── Pending ──
        $createRental($customers[0], RentalStatus::Pending, [
            ['vehicle' => $vehicles[0], 'start' => now()->addDays(1), 'days' => 3, 'rate' => $vehicles[0]->rental_rate_per_day],
        ]);
        $createRental($customers[1], RentalStatus::Pending, [
            ['vehicle' => $vehicles[1], 'start' => now()->addDays(2), 'days' => 4, 'rate' => $vehicles[1]->rental_rate_per_day, 'driver' => $drivers[0], 'driver_fee' => $drivers[0]->driver_fee_per_day],
        ]);
        $createRental($customers[2], RentalStatus::Pending, [
            ['vehicle' => $vehicles[5], 'start' => now()->addDays(5), 'days' => 2, 'rate' => $vehicles[5]->rental_rate_per_day],
            ['vehicle' => $vehicles[6], 'start' => now()->addDays(5), 'days' => 2, 'rate' => $vehicles[6]->rental_rate_per_day],
        ], [
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Pending,
        ]);

        // ── Confirmed ──
        $createRental($customers[3], RentalStatus::Confirmed, [
            ['vehicle' => $vehicles[2], 'start' => now()->addDays(1), 'days' => 5, 'rate' => $vehicles[2]->rental_rate_per_day],
        ], [
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Paid,
        ]);
        $createRental($customers[4], RentalStatus::Confirmed, [
            ['vehicle' => $vehicles[3], 'start' => now()->addDays(3), 'days' => 7, 'rate' => $vehicles[3]->rental_rate_per_day, 'driver' => $drivers[1], 'driver_fee' => $drivers[1]->driver_fee_per_day],
        ], [
            'method' => PaymentMethod::Cash,
            'status' => PaymentStatus::Pending,
        ]);
        $createRental($customers[0], RentalStatus::Confirmed, [
            ['vehicle' => $vehicles[8], 'start' => now()->addDays(1), 'days' => 3, 'rate' => $vehicles[8]->rental_rate_per_day],
        ]);

        // ── Active ──
        $createRental($customers[5], RentalStatus::Active, [
            ['vehicle' => $vehicles[4], 'start' => now()->subDays(2), 'days' => 5, 'rate' => $vehicles[4]->rental_rate_per_day],
        ], [
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(2),
        ]);
        $createRental($customers[6], RentalStatus::Active, [
            ['vehicle' => $vehicles[7], 'start' => now()->subDays(1), 'days' => 4, 'rate' => $vehicles[7]->rental_rate_per_day, 'driver' => $drivers[2], 'driver_fee' => $drivers[2]->driver_fee_per_day],
        ], [
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(1),
        ]);
        $createRental($customers[7], RentalStatus::Active, [
            ['vehicle' => $vehicles[9], 'start' => now()->subDays(3), 'days' => 6, 'rate' => $vehicles[9]->rental_rate_per_day],
            ['vehicle' => $vehicles[10], 'start' => now()->subDays(3), 'days' => 6, 'rate' => $vehicles[10]->rental_rate_per_day, 'driver' => $drivers[3], 'driver_fee' => $drivers[3]->driver_fee_per_day],
        ], [
            'amount' => 7_500_000,
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(3),
        ]);
        $createRental($customers[8], RentalStatus::Active, [
            ['vehicle' => $vehicles[12], 'start' => now()->subDays(1), 'days' => 2, 'rate' => $vehicles[12]->rental_rate_per_day],
        ], [
            'method' => PaymentMethod::Cash,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(1),
        ]);
        $createRental($customers[1], RentalStatus::Active, [
            ['vehicle' => $vehicles[16], 'start' => now()->subDays(4), 'days' => 7, 'rate' => $vehicles[16]->rental_rate_per_day, 'driver' => $drivers[4], 'driver_fee' => $drivers[4]->driver_fee_per_day],
        ], [
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(4),
        ]);

        // ── Completed ──
        $createRental($customers[9], RentalStatus::Completed, [
            ['vehicle' => $vehicles[0], 'start' => now()->subDays(10), 'days' => 4, 'rate' => $vehicles[0]->rental_rate_per_day, 'item_status' => RentalItemStatus::Returned],
        ], [
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(10),
        ]);
        $createRental($customers[2], RentalStatus::Completed, [
            ['vehicle' => $vehicles[1], 'start' => now()->subDays(14), 'days' => 5, 'rate' => $vehicles[1]->rental_rate_per_day, 'item_status' => RentalItemStatus::Returned],
            ['vehicle' => $vehicles[3], 'start' => now()->subDays(14), 'days' => 5, 'rate' => $vehicles[3]->rental_rate_per_day, 'driver' => $drivers[0], 'driver_fee' => $drivers[0]->driver_fee_per_day, 'item_status' => RentalItemStatus::Returned],
        ], [
            'amount' => 5_250_000,
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(14),
        ]);
        $createRental($customers[3], RentalStatus::Completed, [
            ['vehicle' => $vehicles[14], 'start' => now()->subDays(7), 'days' => 3, 'rate' => $vehicles[14]->rental_rate_per_day, 'item_status' => RentalItemStatus::Returned],
        ], [
            'method' => PaymentMethod::Cash,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(7),
        ]);
        $createRental($customers[4], RentalStatus::Completed, [
            ['vehicle' => $vehicles[11], 'start' => now()->subDays(20), 'days' => 7, 'rate' => $vehicles[11]->rental_rate_per_day, 'item_status' => RentalItemStatus::Returned],
        ], [
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(20),
        ]);
        $createRental($customers[0], RentalStatus::Completed, [
            ['vehicle' => $vehicles[17], 'start' => now()->subDays(5), 'days' => 2, 'rate' => $vehicles[17]->rental_rate_per_day, 'item_status' => RentalItemStatus::Returned],
            ['vehicle' => $vehicles[18], 'start' => now()->subDays(5), 'days' => 2, 'rate' => $vehicles[18]->rental_rate_per_day, 'driver' => $drivers[1], 'driver_fee' => $drivers[1]->driver_fee_per_day, 'item_status' => RentalItemStatus::Returned],
        ], [
            'method' => PaymentMethod::Cash,
            'status' => PaymentStatus::Paid,
            'date' => now()->subDays(5),
        ]);

        // ── Cancelled ──
        $createRental($customers[10], RentalStatus::Cancelled, [
            ['vehicle' => $vehicles[15], 'start' => now()->subDays(3), 'days' => 3, 'rate' => $vehicles[15]->rental_rate_per_day, 'item_status' => RentalItemStatus::Rented],
        ]);
        $createRental($customers[6], RentalStatus::Cancelled, [
            ['vehicle' => $vehicles[13], 'start' => now()->subDays(6), 'days' => 4, 'rate' => $vehicles[13]->rental_rate_per_day, 'item_status' => RentalItemStatus::Rented],
        ]);
        $createRental($customers[5], RentalStatus::Cancelled, [
            ['vehicle' => $vehicles[19], 'start' => now()->subDays(8), 'days' => 2, 'rate' => $vehicles[19]->rental_rate_per_day, 'driver' => $drivers[2], 'driver_fee' => $drivers[2]->driver_fee_per_day, 'item_status' => RentalItemStatus::Rented],
        ]);
        $createRental($customers[4], RentalStatus::Cancelled, [
            ['vehicle' => $vehicles[2], 'start' => now()->subDays(15), 'days' => 5, 'rate' => $vehicles[2]->rental_rate_per_day, 'item_status' => RentalItemStatus::Rented],
            ['vehicle' => $vehicles[4], 'start' => now()->subDays(15), 'days' => 5, 'rate' => $vehicles[4]->rental_rate_per_day, 'item_status' => RentalItemStatus::Rented],
        ]);

        // ─── Expenses (gate: only seed when empty) ───────────────
        if (Expense::count() > 0) {
            $this->summary();

            return;
        }

        $superAdmin = User::where('email', 'superadmin@example.com')->first();

        // Monthly employee salaries
        $adminPerson = Person::whereHas('user', fn ($q) => $q->where('email', 'admin@example.com'))->first();

        Expense::create([
            'amount' => 8_000_000,
            'category' => ExpenseCategory::EmployeeSalary->value,
            'description' => 'Monthly salary — '.$adminPerson->user->name,
            'date' => Carbon::now()->startOfMonth(),
            'person_id' => $adminPerson->id,
            'created_by' => $superAdmin->id,
        ]);

        $salaryAmounts = [3_500_000, 5_500_000];
        foreach ($employees as $i => $user) {
            Expense::create([
                'amount' => $salaryAmounts[$i] ?? 4_500_000,
                'category' => ExpenseCategory::EmployeeSalary->value,
                'description' => 'Monthly salary — '.$user->name,
                'date' => Carbon::now()->startOfMonth(),
                'person_id' => $user->person->id,
                'created_by' => $superAdmin->id,
            ]);
        }

        // THR for employees
        $thrAmounts = [3_000_000, 2_000_000];
        $thrDays = [5, 10];
        foreach ($employees as $i => $user) {
            Expense::create([
                'amount' => $thrAmounts[$i] ?? 2_500_000,
                'category' => ExpenseCategory::THR->value,
                'description' => 'THR — '.$user->name,
                'date' => Carbon::now()->startOfMonth()->addDays($thrDays[$i] ?? 1),
                'person_id' => $user->person->id,
                'created_by' => $superAdmin->id,
            ]);
        }

        // THR for drivers
        $driverThrAmounts = [2_500_000, 1_500_000, 3_000_000, 2_000_000, 3_500_000];
        $driverThrDays = [3, 7, 12, 5, 14];
        foreach ($drivers as $i => $driverPerson) {
            Expense::create([
                'amount' => $driverThrAmounts[$i] ?? 2_000_000,
                'category' => ExpenseCategory::THR->value,
                'description' => 'THR — '.$driverPerson->user->name,
                'date' => Carbon::now()->startOfMonth()->addDays($driverThrDays[$i] ?? 1),
                'person_id' => $driverPerson->id,
                'created_by' => $superAdmin->id,
            ]);
        }

        // Vehicle maintenance
        $maintenanceVehicles = [$vehicles[0], $vehicles[3], $vehicles[7]];
        $maintenanceAmounts = [1_200_000, 850_000, 2_100_000];
        $maintenanceDays = [3, 8, 15];
        foreach ($maintenanceVehicles as $i => $vehicle) {
            Expense::create([
                'amount' => $maintenanceAmounts[$i],
                'category' => ExpenseCategory::Maintenance->value,
                'description' => 'Service — '.$vehicle->name,
                'date' => Carbon::now()->startOfMonth()->addDays($maintenanceDays[$i]),
                'vehicle_id' => $vehicle->id,
                'created_by' => $superAdmin->id,
            ]);
        }

        // Vehicle tax
        $taxVehicles = [$vehicles[1], $vehicles[5]];
        $taxAmounts = [2_800_000, 3_200_000];
        $taxDays = [2, 7];
        foreach ($taxVehicles as $i => $vehicle) {
            Expense::create([
                'amount' => $taxAmounts[$i],
                'category' => ExpenseCategory::VehicleTax->value,
                'description' => 'Annual tax — '.$vehicle->name.' ('.$vehicle->license_plate.')',
                'date' => Carbon::now()->startOfMonth()->addDays($taxDays[$i]),
                'vehicle_id' => $vehicle->id,
                'created_by' => $superAdmin->id,
            ]);
        }

        // Fuel & cleaning
        $fuelCleanVehicles = [$vehicles[2], $vehicles[4], $vehicles[6], $vehicles[8], $vehicles[10]];
        $fuelCleanData = [
            ['amount' => 150_000, 'category' => ExpenseCategory::Fuel->value, 'label' => 'Fuel', 'day' => 4],
            ['amount' => 80_000, 'category' => ExpenseCategory::Cleaning->value, 'label' => 'Car wash', 'day' => 9],
            ['amount' => 200_000, 'category' => ExpenseCategory::Fuel->value, 'label' => 'Fuel', 'day' => 14],
            ['amount' => 75_000, 'category' => ExpenseCategory::Cleaning->value, 'label' => 'Car wash', 'day' => 18],
            ['amount' => 120_000, 'category' => ExpenseCategory::Fuel->value, 'label' => 'Fuel', 'day' => 22],
        ];
        foreach ($fuelCleanVehicles as $i => $vehicle) {
            Expense::create([
                'amount' => $fuelCleanData[$i]['amount'],
                'category' => $fuelCleanData[$i]['category'],
                'description' => $fuelCleanData[$i]['label'].' — '.$vehicle->name,
                'date' => Carbon::now()->startOfMonth()->addDays($fuelCleanData[$i]['day']),
                'vehicle_id' => $vehicle->id,
                'created_by' => $superAdmin->id,
            ]);
        }

        // Operational
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

    private function driverFee(int $index): int
    {
        return match ($index) {
            1 => 150_000,
            2 => 100_000,
            3 => 175_000,
            4 => 125_000,
            5 => 200_000,
        };
    }

    private function summary(): void
    {
        $this->command?->info('Seed complete!');
        $this->command?->info('Users: '.User::count().' | People: '.Person::count().' | Vehicles: '.Vehicle::count());
        $this->command?->info('Rentals: '.Rental::count().' | RentalItems: '.RentalItem::count().' | Payments: '.Payment::count());
        $this->command?->info('Expenses: '.Expense::count());
    }
}
