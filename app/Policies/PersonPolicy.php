<?php

namespace App\Policies;

use App\Enums\PersonType;
use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isEmployee();
    }

    public function view(User $user, Person $person): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        if ($user->isEmployee()) {
            return $person->type !== PersonType::SuperAdmin->value && $person->type !== PersonType::Admin->value && $person->type !== PersonType::Employee->value;
        }

        return $person->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function update(User $user, Person $person): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        if ($user->isCustomer()) {
            return $person->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, Person $person): bool
    {
        return $user->isSuperAdmin();
    }

    public function restore(User $user, Person $person): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, Person $person): bool
    {
        return $user->isSuperAdmin();
    }
}
