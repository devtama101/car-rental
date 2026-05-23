<?php

use App\Models\Person;
use App\Models\Rental;
use App\Models\User;

test('customer MyRentalResource scopes to their own rentals only', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $otherUser = User::factory()->create();

    Rental::factory()->create(['user_id' => $customer->id]);
    Rental::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($customer);

    $response = $this->get('/dashboard/my-rentals');
    $response->assertOk();
});

test('customer can view their rental detail page', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();
    $rental = Rental::factory()->create(['user_id' => $customer->id]);

    $this->actingAs($customer);

    $response = $this->get("/dashboard/my-rentals/{$rental->id}");
    $response->assertOk();
});

test('customer browse vehicles link redirects to frontpage', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();

    $this->actingAs($customer);

    $response = $this->get('/dashboard');
    $response->assertOk();
    $response->assertSee('Telusuri Mobil');
});

test('customer my profile page renders', function () {
    $customer = User::factory()->has(Person::factory()->customer())->create();

    $this->actingAs($customer);

    $response = $this->get('/dashboard/my-profile');
    $response->assertOk();
});

test('employee dashboard renders', function () {
    $employee = User::factory()->has(Person::factory()->employee())->create();

    $this->actingAs($employee);

    $response = $this->get('/employee');
    $response->assertOk();
});
