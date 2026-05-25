<?php

use App\Livewire\AvailabilityFilter;
use Livewire\Livewire;

test('availability filter component renders', function () {
    Livewire::test(AvailabilityFilter::class)
        ->assertOk()
        ->assertSee(__('Check Availability'));
});

test('availability filter initializes with default dates', function () {
    Livewire::test(AvailabilityFilter::class)
        ->assertSet('startDate', fn ($value) => $value !== null)
        ->assertSet('endDate', fn ($value) => $value !== null);
});

test('availability filter auto-corrects end date when before start date', function () {
    $startDate = now()->addDays(5)->format('Y-m-d\TH:i');
    $invalidEndDate = now()->addDays(1)->format('Y-m-d\TH:i');

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('endDate', $invalidEndDate)
        ->assertSet('endDate', fn ($value) => $value !== $invalidEndDate);
});

test('availability filter search redirects to cars index with params', function () {
    $startDate = now()->addDay()->format('Y-m-d\TH:i');
    $endDate = now()->addDays(3)->format('Y-m-d\TH:i');

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('endDate', $endDate)
        ->set('transmission', 'automatic')
        ->call('search')
        ->assertRedirect(route('cars.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'transmission' => 'automatic',
        ]));
});

test('availability filter search includes rental type param', function () {
    $startDate = now()->addDay()->format('Y-m-d\TH:i');
    $endDate = now()->addDays(3)->format('Y-m-d\TH:i');

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('endDate', $endDate)
        ->set('rentalType', 'with-driver')
        ->call('search')
        ->assertRedirect(route('cars.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'rentalType' => 'with-driver',
        ]));
});

test('availability filter search excludes empty transmission param', function () {
    $startDate = now()->addDay()->format('Y-m-d\TH:i');
    $endDate = now()->addDays(3)->format('Y-m-d\TH:i');

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('endDate', $endDate)
        ->set('transmission', '')
        ->call('search')
        ->assertRedirect(route('cars.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]));
});

test('availability filter transmission options include all types', function () {
    Livewire::test(AvailabilityFilter::class)
        ->assertSee(__('All Types'))
        ->assertSee(__('Automatic'))
        ->assertSee(__('Manual'));
});
