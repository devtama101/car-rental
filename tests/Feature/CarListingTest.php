<?php

use App\Livewire\CarListing;
use App\Models\Vehicle;
use Livewire\Livewire;

test('car listing page loads and shows vehicles', function () {
    $vehicles = Vehicle::factory()->count(3)->create();

    Livewire::test(CarListing::class)
        ->assertOk()
        ->assertSee($vehicles->first()->name)
        ->assertSee('Rp');
});

test('car listing filters by date range', function () {
    Vehicle::factory()->count(5)->create();

    Livewire::test(CarListing::class)
        ->set('startDate', now()->addHours(2)->format('Y-m-d\TH:i'))
        ->set('endDate', now()->addHours(14)->format('Y-m-d\TH:i'))
        ->assertOk();
});

test('car listing filters by transmission type', function () {
    Vehicle::factory()->create(['transmission' => 'automatic']);
    Vehicle::factory()->create(['transmission' => 'manual']);

    $automatic = Livewire::test(CarListing::class)
        ->set('transmission', 'automatic')
        ->get('availableVehicles');

    expect($automatic->count())->toBe(1);
    expect($automatic->first()->transmission)->toBe('automatic');

    $manual = Livewire::test(CarListing::class)
        ->set('transmission', 'manual')
        ->get('availableVehicles');

    expect($manual->count())->toBe(1);
    expect($manual->first()->transmission)->toBe('manual');
});

test('car listing shows all vehicles when transmission filter is empty', function () {
    Vehicle::factory()->count(3)->create(['transmission' => 'automatic']);
    Vehicle::factory()->count(2)->create(['transmission' => 'manual']);

    $vehicles = Livewire::test(CarListing::class)
        ->set('transmission', '')
        ->get('availableVehicles');

    expect($vehicles->total())->toBe(5);
});

test('selecting a vehicle shows booking wizard', function () {
    $vehicle = Vehicle::factory()->create();

    Livewire::test(CarListing::class)
        ->call('selectVehicle', $vehicle->id)
        ->assertSet('selectedVehicleId', $vehicle->id);
});

test('reset filters redirects to home', function () {
    Livewire::test(CarListing::class)
        ->call('resetFilters')
        ->assertRedirect(route('home'));
});

test('url parameters sync to component properties', function () {
    Vehicle::factory()->count(3)->create();

    $startDate = now()->addHours(2)->format('Y-m-d\TH:i');
    $endDate = now()->addHours(14)->format('Y-m-d\TH:i');

    Livewire::withQueryParams([
        'startDate' => $startDate,
        'endDate' => $endDate,
        'transmission' => 'automatic',
        'rentalType' => 'self-drive',
    ])
        ->test(CarListing::class)
        ->assertSet('startDate', $startDate)
        ->assertSet('endDate', $endDate)
        ->assertSet('transmission', 'automatic')
        ->assertSet('rentalType', 'self-drive');
});

test('car listing filters by rental type self-drive', function () {
    Vehicle::factory()->create(['rental_type' => 'self-drive']);
    Vehicle::factory()->create(['rental_type' => 'with-driver']);
    Vehicle::factory()->create(['rental_type' => 'both']);

    $result = Livewire::test(CarListing::class)
        ->set('rentalType', 'self-drive')
        ->get('availableVehicles');

    expect($result->total())->toBe(2);
    expect($result->pluck('rental_type')->map(fn ($v) => $v->value)->sort()->values()->toArray())->toBe(['both', 'self-drive']);
});

test('car listing filters by rental type with-driver', function () {
    Vehicle::factory()->create(['rental_type' => 'self-drive']);
    Vehicle::factory()->create(['rental_type' => 'with-driver']);
    Vehicle::factory()->create(['rental_type' => 'both']);

    $result = Livewire::test(CarListing::class)
        ->set('rentalType', 'with-driver')
        ->get('availableVehicles');

    expect($result->total())->toBe(2);
    expect($result->pluck('rental_type')->map(fn ($v) => $v->value)->sort()->values()->toArray())->toBe(['both', 'with-driver']);
});

test('car listing shows all vehicles when rental type filter is empty', function () {
    Vehicle::factory()->count(3)->create();

    $result = Livewire::test(CarListing::class)
        ->set('rentalType', '')
        ->get('availableVehicles');

    expect($result->total())->toBe(3);
});
