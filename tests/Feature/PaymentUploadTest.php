<?php

use App\Enums\RentalStatus;
use App\Models\Person;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;

test('customer can view rental with upload payment action', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $vehicle = Vehicle::factory()->create();
    $rental = Rental::factory()->create([
        'user_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(3),
        'status' => RentalStatus::Pending->value,
    ]);

    $this->actingAs($customer);

    $response = $this->get("/dashboard/my-rentals/{$rental->id}");
    $response->assertOk();
});

test('payment proof upload updates existing payment record', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $vehicle = Vehicle::factory()->create();
    $rental = Rental::factory()->create([
        'user_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(3),
        'status' => RentalStatus::Pending->value,
    ]);

    $payment = $rental->payments()->create([
        'amount' => $rental->total_amount,
        'method' => 'transfer',
        'date' => now()->toDateString(),
    ]);

    expect($payment->proof_file_path)->toBeNull();

    $payment->update([
        'proof_file_path' => 'payment-proofs/test.jpg',
    ]);

    expect($payment->fresh()->proof_file_path)->toBe('payment-proofs/test.jpg');
});

test('rental status transitions work correctly', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $vehicle = Vehicle::factory()->create();
    $rental = Rental::factory()->create([
        'user_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(3),
        'status' => RentalStatus::Pending->value,
    ]);

    expect($rental->status)->toBe(RentalStatus::Pending->value);

    $rental->update(['status' => RentalStatus::Confirmed->value]);
    expect($rental->fresh()->status)->toBe(RentalStatus::Confirmed->value);

    $rental->update(['status' => RentalStatus::Active->value]);
    expect($rental->fresh()->status)->toBe(RentalStatus::Active->value);

    $rental->update(['status' => RentalStatus::Completed->value]);
    expect($rental->fresh()->status)->toBe(RentalStatus::Completed->value);
});

test('admin can view payment list', function () {
    $admin = User::factory()->has(Person::factory()->admin())->create();

    $this->actingAs($admin);

    $response = $this->get('/admin/payments');
    $response->assertOk();
});
