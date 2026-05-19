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

test('selecting a vehicle shows booking wizard', function () {
    $vehicle = Vehicle::factory()->create();

    Livewire::test(CarListing::class)
        ->call('selectVehicle', $vehicle->id)
        ->assertSet('selectedVehicleId', $vehicle->id);
});
