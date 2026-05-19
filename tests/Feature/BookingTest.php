<?php

use App\Enums\RentalItemStatus;
use App\Enums\RentalStatus;
use App\Livewire\BookingWizard;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Livewire;

test('booking wizard renders for a vehicle', function () {
    $vehicle = Vehicle::factory()->create();

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->assertOk()
        ->assertSee($vehicle->name)
        ->assertSee('Rental Details');
});

test('booking wizard validates rental details on incomplete submit', function () {
    $vehicle = Vehicle::factory()->create();

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->set('data.start_date', '')
        ->call('submit')
        ->assertHasErrors(['data.start_date']);
});

test('booking wizard validates customer details on submit', function () {
    $vehicle = Vehicle::factory()->create();

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->set('data.start_date', Carbon::now()->addHours(2)->format('Y-m-d\TH:i'))
        ->set('data.is_half_day', false)
        ->set('data.full_day_duration', 1)
        ->set('data.driver_option', 'self-drive')
        ->set('data.customer_name', '')
        ->set('data.customer_email', '')
        ->set('data.customer_password', '')
        ->set('data.payment_method', '')
        ->call('submit')
        ->assertHasErrors([
            'data.customer_name',
            'data.customer_email',
            'data.customer_password',
            'data.payment_method',
        ]);
});

test('half day booking calculates 1 block', function () {
    $vehicle = Vehicle::factory()->create(['rental_rate_per_day' => 100_000]);

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->set('data.is_half_day', true)
        ->set('data.start_date', Carbon::now()->addHours(2)->format('Y-m-d\TH:i'))
        ->assertSet('totalHours', 12)
        ->assertSet('totalBlocks', 1)
        ->assertSet('ratePerBlock', 50_000)
        ->assertSet('subtotal', 50_000);
});

test('full day booking calculates 2 blocks', function () {
    $vehicle = Vehicle::factory()->create(['rental_rate_per_day' => 100_000]);

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->set('data.is_half_day', false)
        ->set('data.full_day_duration', 1)
        ->set('data.start_date', Carbon::now()->addHours(2)->format('Y-m-d\TH:i'))
        ->assertSet('totalHours', 24)
        ->assertSet('totalBlocks', 2)
        ->assertSet('subtotal', 100_000);
});

test('multi-day full day booking calculates correctly', function () {
    $vehicle = Vehicle::factory()->create(['rental_rate_per_day' => 100_000]);

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->set('data.is_half_day', false)
        ->set('data.full_day_duration', 3)
        ->set('data.start_date', Carbon::now()->addHours(2)->format('Y-m-d\TH:i'))
        ->assertSet('totalHours', 72)
        ->assertSet('totalBlocks', 6)
        ->assertSet('subtotal', 300_000);
});

test('booking wizard completes full booking flow', function () {
    $vehicle = Vehicle::factory()->create(['rental_rate_per_day' => 100_000]);

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->set('data.is_half_day', true)
        ->set('data.start_date', Carbon::now()->addHours(2)->format('Y-m-d\TH:i'))
        ->set('data.driver_option', 'self-drive')
        ->set('data.customer_name', 'John Doe')
        ->set('data.customer_email', 'john@example.com')
        ->set('data.customer_password', 'Password1')
        ->set('data.customer_phone', '08123456789')
        ->set('data.customer_address', 'Jl. Merdeka No. 1')
        ->set('data.customer_id_type', 'ktp')
        ->set('data.payment_method', 'cash')
        ->call('submit')
        ->assertDispatched('booking-completed');
});

test('booking creates all related records', function () {
    $vehicle = Vehicle::factory()->create(['rental_rate_per_day' => 100_000]);

    Livewire::test(BookingWizard::class, ['vehicleId' => $vehicle->id])
        ->set('data.is_half_day', true)
        ->set('data.start_date', Carbon::now()->addHours(2)->format('Y-m-d\TH:i'))
        ->set('data.driver_option', 'self-drive')
        ->set('data.customer_name', 'Jane Doe')
        ->set('data.customer_email', 'jane@example.com')
        ->set('data.customer_password', 'Secret123')
        ->set('data.customer_phone', '087654321')
        ->set('data.customer_address', 'Jl. Sudirman No. 10')
        ->set('data.customer_id_type', 'sim')
        ->set('data.payment_method', 'transfer')
        ->call('submit');

    expect(User::where('email', 'jane@example.com')->exists())->toBeTrue();
    expect(Rental::where('status', RentalStatus::Pending->value)->exists())->toBeTrue();
    expect(RentalItem::where('vehicle_id', $vehicle->id)
        ->where('status', RentalItemStatus::Rented->value)
        ->exists()
    )->toBeTrue();
    expect(Payment::exists())->toBeTrue();
    expect(Person::where('phone', '087654321')->exists())->toBeTrue();
});
