<?php

use App\Livewire\AvailabilityFilter;
use Livewire\Livewire;

test('availability filter component renders', function () {
    Livewire::test(AvailabilityFilter::class)
        ->assertOk()
        ->assertSee(__('Check Availability'));
});

test('availability filter initializes with default dates and values', function () {
    Livewire::test(AvailabilityFilter::class)
        ->assertSet('startDate', fn ($value) => $value !== null)
        ->assertSet('startTime', fn ($value) => $value !== null)
        ->assertSet('endDate', fn ($value) => $value !== null)
        ->assertSet('endTime', fn ($value) => $value !== null)
        ->assertSet('transmission', 'automatic')
        ->assertSet('requiresDriver', '0');
});

test('availability filter start time is rounded to next whole hour', function () {
    Livewire::test(AvailabilityFilter::class)
        ->assertSet('startTime', fn ($value) => str_ends_with($value, ':00'))
        ->assertSet('endTime', fn ($value) => str_ends_with($value, ':00'));
});

test('availability filter auto-corrects end date when before start date', function () {
    $startDate = now()->addDays(5)->format('Y-m-d');
    $startTime = '10:00';
    $invalidEndDate = now()->addDays(1)->format('Y-m-d');
    $invalidEndTime = '12:00';

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('startTime', $startTime)
        ->set('endDate', $invalidEndDate)
        ->set('endTime', $invalidEndTime)
        ->assertSet('endDate', fn ($value) => $value !== $invalidEndDate);
});

test('availability filter search redirects to cars index with params', function () {
    $startDate = now()->addDay()->format('Y-m-d');
    $startTime = '09:00';
    $endDate = now()->addDays(3)->format('Y-m-d');
    $endTime = '18:00';

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('startTime', $startTime)
        ->set('endDate', $endDate)
        ->set('endTime', $endTime)
        ->set('transmission', 'automatic')
        ->call('search')
        ->assertRedirect(route('cars.index', [
            'startDate' => $startDate.'T'.$startTime,
            'endDate' => $endDate.'T'.$endTime,
            'transmission' => 'automatic',
            'requiresDriver' => '0',
        ]));
});

test('availability filter search includes driver option param', function () {
    $startDate = now()->addDay()->format('Y-m-d');
    $startTime = '09:00';
    $endDate = now()->addDays(3)->format('Y-m-d');
    $endTime = '18:00';

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('startTime', $startTime)
        ->set('endDate', $endDate)
        ->set('endTime', $endTime)
        ->set('requiresDriver', '1')
        ->call('search')
        ->assertRedirect(route('cars.index', [
            'startDate' => $startDate.'T'.$startTime,
            'endDate' => $endDate.'T'.$endTime,
            'transmission' => 'automatic',
            'requiresDriver' => '1',
        ]));
});

test('availability filter search excludes empty transmission param', function () {
    $startDate = now()->addDay()->format('Y-m-d');
    $startTime = '09:00';
    $endDate = now()->addDays(3)->format('Y-m-d');
    $endTime = '18:00';

    Livewire::test(AvailabilityFilter::class)
        ->set('startDate', $startDate)
        ->set('startTime', $startTime)
        ->set('endDate', $endDate)
        ->set('endTime', $endTime)
        ->set('transmission', '')
        ->call('search')
        ->assertRedirect(route('cars.index', [
            'startDate' => $startDate.'T'.$startTime,
            'endDate' => $endDate.'T'.$endTime,
            'requiresDriver' => '0',
        ]));
});

test('availability filter renders all option labels', function () {
    Livewire::test(AvailabilityFilter::class)
        ->assertSee(__('Semua'))
        ->assertSee(__('Automatic'))
        ->assertSee(__('Manual'))
        ->assertSee(__('Tanpa Sopir'))
        ->assertSee(__('Dengan Sopir'));
});
