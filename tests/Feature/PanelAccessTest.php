<?php

use App\Enums\PersonType;
use App\Models\Person;
use App\Models\User;

test('admin can access admin panel', function () {
    $user = User::factory()->has(Person::factory()->admin())->create();

    expect($user->isAdmin())->toBeTrue();
    expect($user->isEmployee())->toBeFalse();
    expect($user->isCustomer())->toBeFalse();
});

test('employee can access employee panel', function () {
    $user = User::factory()->has(Person::factory()->employee())->create();

    expect($user->isEmployee())->toBeTrue();
    expect($user->isAdmin())->toBeFalse();
    expect($user->isCustomer())->toBeFalse();
});

test('customer can access customer panel', function () {
    $user = User::factory()->has(Person::factory()->customer())->create();

    expect($user->isCustomer())->toBeTrue();
    expect($user->isAdmin())->toBeFalse();
    expect($user->isEmployee())->toBeFalse();
});

test('person factory has all states', function () {
    $customer = Person::factory()->customer()->make();
    $employee = Person::factory()->employee()->make();
    $admin = Person::factory()->admin()->make();
    $driver = Person::factory()->driver()->make();

    expect($customer->type)->toBe(PersonType::Customer->value);
    expect($employee->type)->toBe(PersonType::Employee->value);
    expect($admin->type)->toBe(PersonType::Admin->value);
    expect($driver->type)->toBe(PersonType::Driver->value);
});

test('user without person record has no role', function () {
    $user = User::factory()->create();

    expect($user->isAdmin())->toBeFalse();
    expect($user->isEmployee())->toBeFalse();
    expect($user->isCustomer())->toBeFalse();
    expect($user->isDriver())->toBeFalse();
});
