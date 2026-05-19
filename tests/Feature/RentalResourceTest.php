<?php

use App\Filament\Resources\Rentals\Pages\CreateRental;
use App\Filament\Resources\Rentals\Pages\EditRental;
use App\Filament\Resources\Rentals\Pages\ListRentals;
use App\Filament\Resources\Rentals\Pages\ViewRental;
use App\Models\Person;
use App\Models\Rental;
use App\Models\User;
use Livewire\Livewire;

test('rental list page renders', function () {
    $user = User::factory()->has(Person::factory()->admin())->create();

    Livewire::actingAs($user);

    Livewire::test(ListRentals::class)
        ->assertOk();
});

test('rental create page renders', function () {
    $user = User::factory()->has(Person::factory()->admin())->create();

    Livewire::actingAs($user);

    Livewire::test(CreateRental::class)
        ->assertOk();
});

test('rental view page renders', function () {
    $user = User::factory()->has(Person::factory()->admin())->create();
    $rental = Rental::factory()->create();

    Livewire::actingAs($user);

    Livewire::test(ViewRental::class, ['record' => $rental->id])
        ->assertOk()
        ->assertSee($rental->user->name);
});

test('rental edit page renders', function () {
    $user = User::factory()->has(Person::factory()->admin())->create();
    $rental = Rental::factory()->create();

    Livewire::actingAs($user);

    Livewire::test(EditRental::class, ['record' => $rental->id])
        ->assertOk();
});
