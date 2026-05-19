<?php

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Rental;
use App\Models\User;

test('customer can view rental with upload payment action', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $rental = Rental::factory()->create([
        'user_id' => $customer->id,
        'status' => RentalStatus::Pending->value,
    ]);

    $this->actingAs($customer);

    $response = $this->get("/dashboard/my-rentals/{$rental->id}");
    $response->assertOk();
});

test('payment proof upload creates payment record', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $rental = Rental::factory()->create([
        'user_id' => $customer->id,
        'status' => RentalStatus::Pending->value,
    ]);

    expect(Payment::count())->toBe(0);

    $rental->payments()->create([
        'amount' => $rental->total_amount,
        'method' => 'transfer',
        'proof_file_path' => 'payment-proofs/test.jpg',
        'status' => PaymentStatus::Pending->value,
        'date' => now()->toDateString(),
    ]);

    expect(Payment::count())->toBe(1);
    expect($rental->payments()->first()->proof_file_path)->toBe('payment-proofs/test.jpg');
});

test('rental status transitions work correctly', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $rental = Rental::factory()->create([
        'user_id' => $customer->id,
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

test('employee can access payment verification page', function () {
    $employee = User::factory()->has(Person::factory()->employee())->create();

    $this->actingAs($employee);

    $response = $this->get('/employee/payment-verification');
    $response->assertOk();
});
